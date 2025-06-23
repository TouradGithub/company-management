<?php

namespace App\Http\Controllers\BankManagment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class vouchersContainer extends Controller
{
    public function index()
    {
        return response()->json(\App\Models\vouchersContainer::all());
    }
    public function storeReceipt(Request $request)
    {
        $request->validate([
            'number' => 'required',
            'date' => 'required|date',
            'fromTo' => 'required',
            'amount' => 'required|numeric',
            'paymentMethod' => 'required',
            'description' => 'required'
        ]);

        \App\Models\vouchersContainer::create([
            'type' => 'قبض', // تمييز نوع السند
            'number' => $request->number,
            'date' => $request->date,
            'fromTo' => $request->fromTo,
            'amount' => $request->amount,
            'paymentMethod' => $request->paymentMethod,
            'description' => $request->description,
        ]);

        return response()->json(['status' => 'success', 'message' => 'تم حفظ سند القبض بنجاح']);
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'number' => 'required',
            'date' => 'required|date',
            'fromTo' => 'required',
            'amount' => 'required|numeric',
            'paymentMethod' => 'required',
            'description' => 'required'
        ]);

        \App\Models\vouchersContainer::create([
            'type' => 'صرف', // تمييز نوع السند
            'number' => $request->number,
            'date' => $request->date,
            'fromTo' => $request->fromTo,
            'amount' => $request->amount,
            'paymentMethod' => $request->paymentMethod,
            'description' => $request->description,
        ]);

        return response()->json(['status' => 'success', 'message' => 'تم حفظ سند الصرف بنجاح']);
    }

    public function updateVoucher(Request $request, $id)
    {
        $request->validate([
            'number' => 'required',
            'date' => 'required|date',
            'fromTo' => 'required',
            'amount' => 'required|numeric',
            'paymentMethod' => 'required',
            'description' => 'required',
            'type' => 'required|in:قبض,صرف',
        ]);

        $voucher = \App\Models\vouchersContainer::findOrFail($id);

        $voucher->update([
            'number' => $request->number,
            'date' => $request->date,
            'fromTo' => $request->fromTo,
            'amount' => $request->amount,
            'paymentMethod' => $request->paymentMethod,
            'description' => $request->description,
            'type' => $request->type,
        ]);

        return response()->json(['status' => 'success', 'message' => 'تم تحديث السند بنجاح']);
    }

    public function delete($id)
    {
        $voucher = \App\Models\vouchersContainer::find($id);

        if (!$voucher) {
            return response()->json(['status' => 'error', 'message' => 'السند غير موجود'], 404);
        }

        $voucher->delete();

        return response()->json(['status' => 'success', 'message' => 'تم حذف السند بنجاح']);
    }

}

