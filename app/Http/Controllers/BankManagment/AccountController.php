<?php

namespace App\Http\Controllers\BankManagment;


use App\Models\bankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function index()
    {
        return response()->json(bankAccount::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'bankName' => 'required|string',
            'accountNumber' => 'required|string|unique:bank_accounts',
            'balance' => 'required|numeric'
        ]);

        $account = bankAccount::create([
            'bankName' => $request->bankName,
            'accountNumber' => $request->accountNumber,
            'balance' => $request->balance,
        ]);
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        // جلب الحساب من قاعدة البيانات
        $account = bankAccount::findOrFail($id);

        // إرجاع البيانات بتنسيق JSON
        return response()->json($account);
    }

    public function update(Request $request, $id)
    {
        $account = bankAccount::findOrFail($id);
        $account->update($request->all());
        return response()->json($account);
    }

    public function destroy($id)
    {
        $account = bankAccount::findOrFail($id);
        $account->delete();
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }

}
