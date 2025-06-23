<?php

namespace App\Http\Controllers\Property;

use App\Models\Payment;
use App\Models\Property;
use App\Models\transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PropertyController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');

        $properties = Property::when($search, function ($query, $search) {
            $query->where('property_name', 'like', "%$search%")
                ->orWhere('tenant_name', 'like', "%$search%")
                ->orWhere('landlord_name', 'like', "%$search%")
                ->orWhere('property_address', 'like', "%$search%");
        })->get();

        $payments = Payment::whereIn('property_id', $properties->pluck('id'))->get();
        $transactions = transaction::whereIn('property_id', $properties->pluck('id'))->get();

        return response()->json([
            'properties' => $properties,
            'payments' => $payments,
            'transactions' => $transactions,
        ]);
    }






    public function store(Request $request)
    {
        $request->validate([
            'property_name' => 'required|string',
            'tenant_name' => 'required|string',
            'landlord_name' => 'required|string',
            'property_address' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'rent_amount' => 'required|numeric',
            'payment_cycle' => 'required|string',
            'contract_details' => 'nullable|string',
        ]);

        Property::create([
            'property_name' => $request->property_name,
            'tenant_name' => $request->tenant_name,
            'landlord_name' => $request->landlord_name,
            'property_address' => $request->property_address,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'rent_amount' => $request->rent_amount,
            'payment_cycle' => $request->payment_cycle,
            'contract_details' => $request->contract_details,
        ]);

        return response()->json(['message' => 'تم حفظ العقار بنجاح!']);
    }



    public function show($id)
    {
        return Property::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $property->update($request->all());
        return response()->json(['message' => 'تم التحديث']);
    }

    public function destroy($id)
    {
        Property::findOrFail($id)->delete();
        return response()->json(['message' => 'تم الحذف']);
    }

}
