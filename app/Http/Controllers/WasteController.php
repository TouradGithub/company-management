<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// app/Http/Controllers/WasteController.php
use App\Models\Waste;
use App\Models\WasteItem;


class WasteController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'wasteNumber' => 'required|string',
            'wasteDate' => 'required|date',
            'branch' => 'required|integer',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'totalItems' => 'required|integer',
            'totalQuantity' => 'required|numeric',
            'totalCost' => 'required|numeric',
            'items' => 'required|array',
            'items.*.id' => 'required',
            'items.*.code' => 'required|string',
            'items.*.name' => 'required|string',
            'items.*.category' => 'required|string',
            'items.*.quantity' => 'required|numeric',
            'items.*.unitCost' => 'required|numeric',
            'items.*.totalCost' => 'required|numeric',
        ]);
        $lastWaste = Waste::orderBy('waste_number', 'desc')->first();

        $nextWasteNumber = $lastWaste? $lastWaste->waste_number + 1 : 1001;

        // حفظ سجل الإتلاف الرئيسي
        $waste = Waste::create([
            'waste_number' => $nextWasteNumber,
            'waste_date' => $data['wasteDate'],
            'branch_id' => $data['branch'],
            'reason' => $data['reason'],
            'notes' => $data['notes'] ?? null,
            'total_items' => $data['totalItems'],
            'total_quantity' => $data['totalQuantity'],
            'total_cost' => $data['totalCost'],
        ]);

        foreach ($data['items'] as $item) {
            // حفظ تفاصيل الإتلاف
            WasteItem::create([
                'waste_id' => $waste->id,
                'product_code' => $item['code'],
                'product_name' => $item['name'],
                'category' => $item['category'],
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unitCost'],
                'total_cost' => $item['totalCost'],
            ]);


            // تقليل الكمية من المنتج إن وُجد
            $product = \App\Models\Productlite::where('id', $item['id'])->first();
            if ($product) {
                $newQty = $product->stock - $item['quantity'];
                $product->stock = max(0, $newQty); // لا تسمح بكميات سالبة
                $product->damages = $product->damages + $item['quantity']; // إضافة الكمية المتلفة إلى الحقل "damages"
                $product->save();
            }
        }

        return response()->json(['status' => 'success']);
    }

}
