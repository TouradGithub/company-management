<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transfer;
use App\Models\TransferItem;
use Illuminate\Http\Request;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{

    public function getTransfers()
    {
        $transfers = Transfer::with('items')->orderBy('transfer_date', 'desc')->get();

        return response()->json($transfers);
    }

    public function storeTransfer(Request $request)
    {
        $data = $request->validate([
            'transferDate' => 'required|date',
            'fromBranch' => 'required|integer',
            'toBranch' => 'required|integer|different:fromBranch',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'totalItems' => 'required|integer',
            'totalQuantity' => 'required|numeric',
            'totalCost' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            // الحصول على آخر رقم تحويل
            $lastTransfer = Transfer::orderBy('transfer_number', 'desc')->first();

            // إذا كان هناك تحويلات سابقة، زيادة الرقم بمقدار 1، وإذا لا يوجد تحويلات تبدأ من 1001
            $nextTransferNumber = $lastTransfer ? $lastTransfer->transfer_number + 1 : 1001;

            // إضافة التحويل الجديد مع رقم التحويل المتزايد
            $transfer = Transfer::create([
                'transfer_number' => $nextTransferNumber,
                'transfer_date' => $data['transferDate'],
                'from_branch_id' => $data['fromBranch'],
                'to_branch_id' => $data['toBranch'],
                'notes' => $data['notes'] ?? null,
                'total_items' => $data['totalItems'],
                'total_quantity' => $data['totalQuantity'],
                'total_cost' => $data['totalCost'],
            ]);

            // إضافة العناصر الخاصة بالتحويل
            foreach ($data['items'] as $item) {
                // إنشاء سجل تحويل العنصر
                TransferItem::create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $item['id'],  // استخدام product_id بدلاً من product_code
                    'product_name' => $item['name'],
                    'product_code' => $item['code'],
                    'category' => $item['category'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unitCost'],
                    'total_cost' => $item['totalCost'],
                ]);

                // تحديث مخزون الفرع المرسل (نقصان الكمية)
                $fromStock = ProductWarehouse::where('product_id', $item['id'])
                    ->where('warehouse_id', $data['fromBranch'])
                    ->first();

                // إذا لم يتم العثور على السجل، نرفض العملية
                if (!$fromStock) {
                    return response()->json([
                        'error' => 'لا يوجد مخزون لهذا المنتج في الفرع المرسل',
                        'product_id' => $item['id'],
                        'product_name' => $item['name']
                    ], 422);
                }

                // تحقق من أن الكمية متوفرة في المخزون
                if ($fromStock->stock >= $item['quantity']) {
                    $fromStock->decrement('stock', $item['quantity']);
                } else {
                    return response()->json([
                        'error' => 'الكمية غير كافية في الفرع المرسل للمنتج:',
                        'product_id' => $item['id'],
                        'available' => $fromStock->stock,
                        'requested' => $item['quantity'],
                        'product_name' => $item['name']
                    ], 422);
                }

                // تحديث مخزون الفرع المستقبل (زيادة الكمية)
                $toStock = ProductWarehouse::where('product_id', $item['id'])
                ->where('warehouse_id', $data['toBranch'])
                ->first();

                if ($toStock) {
                // إذا وُجد سجل، زِد الكمية
                $toStock->increment('stock', $item['quantity']);
                } else {
                // إذا لم يوجد، أنشئ سجل جديد فقط إذا كان المنتج موجودًا
                ProductWarehouse::create([
                    'product_id' => $item['id'],
                    'warehouse_id' => $data['toBranch'],
                    'stock' => $item['quantity'],
                ]);
                }

            }

            DB::commit();
            return response()->json(['message' => 'تم حفظ التحويل بنجاح'], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'حدث خطأ أثناء الحفظ', 'details' => $e->getMessage()], 500);
        }
    }



}
