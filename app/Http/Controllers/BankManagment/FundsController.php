<?php

namespace App\Http\Controllers\BankManagment;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\funds;

class FundsController extends Controller
{
    public function index()
    {
        return response()->json(funds::all());
    }
    public function indexview()
    {
        return view('financialaccounting.bank-managment.funds');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'cashier' => 'required|string',
            'location' => 'required|string',
            'balance' => 'required'
        ]);

        $funds = funds::create([
            'name' => $request->name,
            'cashier' => $request->cashier,
            'location' => $request->location,
            'balance' => $request->balance,
        ]);
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        // جلب الحساب من قاعدة البيانات
        $funds = funds::findOrFail($id);

        // إرجاع البيانات بتنسيق JSON
        return response()->json($funds);
    }

    public function update(Request $request, $id)
    {
        $fund = funds::findOrFail($id);
        $fund->update($request->all());
        return response()->json($fund);
    }

    public function destroy($id)
    {
        $fund = funds::findOrFail($id);
        $fund->delete();
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }

}
