<?php

namespace App\Http\Controllers\BankManagment;

use App\Models\bills;
use App\Models\Product;
use App\Models\Productlite;
use App\Models\DeliveryType;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BillsController extends Controller
{
    //
    public function index()
    {
        $bills = bills::with(['items','deliveryType', 'paymentMethod'])->get();

        return response()->json($bills);
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required',
            'type' => 'required|in:مبيعات,مشتريات',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'customer' => 'nullable|string',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.code' => 'required',
            'items.*.tax_rate' => 'required',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.product_id' => 'required|exists:product_inventories,id',
            'items.*.total' => 'required|numeric|min:0',
            'delivery_type_id' => 'nullable|exists:delivery_types,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
        ]);

        // التحقق من الكمية المتوفرة إذا كانت الفاتورة مبيعات
        if ($request->type === 'مبيعات') {
            foreach ($request->items as $item) {
                $product = Productlite::find($item['product_id']);
                if (!$product) {
                    return response()->json(['message' => "المنتج غير موجود"], 400);
                }

                if ($item['quantity'] > $product->stock) {
                    return response()->json([
                        'message' => "الكمية المدخلة للصنف {$product->name} أكبر من الكمية المتوفرة في المخزون ({$product->stock})"
                    ], 422);
                }
            }
        }

        // إنشاء الفاتورة
        $bill = bills::create([
            'number' => $request->number,
            'type' => $request->type,
            'date' => $request->date,
            'amount' => $request->amount,
            'description' => $request->description,
            'customer' => $request->customer,
            'delivery_type_id' => $request->delivery_type_id,
            'payment_method_id' => $request->payment_method_id,
        ]);

        // حفظ البنود وتحديث المخزون إن كانت مبيعات
        foreach ($request->items as $item) {
            $createdItem = $bill->items()->create($item);

            $product = Productlite::find($item['product_id']);
            if ($product) {
                if ($request->type === 'مبيعات') {
                    $product->stock -= $item['quantity']; // خصم الكمية
                } elseif ($request->type === 'مشتريات') {
                    $product->stock += $item['quantity']; // إضافة الكمية
                }
                $product->save();
            }
        }


        return response()->json(['message' => 'تم حفظ الفاتورة بنجاح']);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'number' => 'required',
            'type' => 'required|in:مبيعات,مشتريات',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'customer' => 'nullable|string',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.code' => 'required',
            'items.*.tax_rate' => 'required',
            'items.*.product_id' => 'required',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        $bill = bills::findOrFail($id);

        // إرجاع الكميات القديمة للمخزون قبل الحذف
        foreach ($bill->items as $oldItem) {
            $product = Productlite::find($oldItem->product_id);
            if ($product) {
                if ($bill->type === 'مبيعات') {
                    $product->stock += $oldItem->quantity; // استرجاع الكمية
                } elseif ($bill->type === 'مشتريات') {
                    $product->stock -= $oldItem->quantity; // حذف الكمية المضافة سابقًا
                }
                $product->save();
            }
        }

        // حذف البنود القديمة
        $bill->items()->delete();

        // تحديث بيانات الفاتورة
        $bill->update([
            'number' => $request->number,
            'type' => $request->type,
            'date' => $request->date,
            'amount' => $request->amount,
            'description' => $request->description,
            'customer' => $request->customer,
            'delivery_type_id' => $request->delivery_type_id,
            'payment_method_id' => $request->payment_method_id,
        ]);

        // إضافة البنود الجديدة وتعديل المخزون
        foreach ($request->items as $item) {
            $product = Productlite::find($item['product_id']);

            if ($product) {
                if ($request->type === 'مبيعات') {
                    if ($product->stock < $item['quantity']) {
                        return response()->json([
                            'message' => "الكمية غير كافية للصنف {$product->name}"
                        ], 422);
                    }
                    $product->stock -= $item['quantity'];
                } elseif ($request->type === 'مشتريات') {
                    $product->stock += $item['quantity'];
                }
                $product->save();
            }

            $bill->items()->create($item);
        }

        return response()->json(['message' => 'تم تحديث الفاتورة بنجاح']);
    }


    public function delete($id)
    {
        $bill = bills::find($id);

        if (!$bill) {
            return response()->json(['status' => 'error', 'message' => 'الفاتورة غير  موجود'], 404);
        }

        $bill->delete();

        return response()->json(['status' => 'success', 'message' => 'تم حذف الفاتورة بنجاح']);
    }
    public function deliveryTypes()
    {
        $deliveryType = DeliveryType::all();
        return response()->json($deliveryType);
    }
    public function paymentMethods()
    {
        $paymentMethods = PaymentMethod::all();
        return response()->json($paymentMethods);
    }

    // public function getSalesData()
    // {
    //     $bills = Bills::with('items')->orderBy('date')->get();

    //     $report = [];
    //     $serial = 1;

    //     foreach ($bills as $bill) {
    //         foreach ($bill->items as $item) {
    //             $taxAmount = $item->total * ($item->tax_rate / 100);
    //             $totalAfterTax = $item->total + $taxAmount;

    //             $report[] = [
    //                 'serial' => $serial++,
    //                 'date' => $bill->date,
    //                 'customer' => $bill->customer,
    //                 'item_name' => $item->name,
    //                 'item_code' => $item->code,
    //                 'invoice_number' => $bill->number,
    //                 'quantity' => $item->quantity,
    //                 'item_total' => round($item->total, 2),
    //                 'tax' => round($taxAmount, 2),
    //                 'total_after_tax' => round($totalAfterTax, 2),
    //                 'current_stock' => $item->quantity
    //             ];
    //         }
    //     }

    //     return response()->json($report);
    // }


}
