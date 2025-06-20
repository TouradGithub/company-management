<?php

namespace App\Http\Controllers\BankManagment;
use App\Models\funds;

use App\Models\Account;
use App\Models\bankAccount;
use Illuminate\Http\Request;
use App\Models\Transaction_Acount;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function index($account_id)
    {
        $account = bankAccount::with('transactions')->findOrFail($account_id);
        return response()->json($account);
    }
    public function indexfund($fund_id)
    {
        $funds = funds::with('transactions')->findOrFail($fund_id);
        return response()->json($funds);
    }

    public function store(Request $request, $account_id)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|string|in:إيداع,تحويل,عهدة',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'beneficiary_name' => 'nullable|string',
        ]);

        $account = bankAccount::findOrFail($account_id);

        if (in_array($request->type, ['تحويل', 'عهدة']) && $account->balance < $request->amount) {
            return response()->json(['message' => 'الرصيد غير كافٍ لإتمام العملية.'], 400);
        }

        // تحديد المبلغ بناءً على نوع المعاملة
        $amount = $request->amount;
        if (in_array($request->type, ['تحويل', 'عهدة'])) {
            $amount = -1 * $amount;
        }

        // إنشاء المعاملة
        $transaction = $account->transactions()->create([
            'date' => $request->date,
            'type' => $request->type,
            'amount' => $amount,
            'description' => $request->description,
            'beneficiary_name' => $request->beneficiary_name,
        ]);

        // تحديث رصيد الحساب
        $account->balance += $amount;
        $account->save();

        return response()->json([
            'transaction' => $transaction,
            'accounts' => bankAccount::with('transactions')->get(), // أو أي طريقة لجلب الحسابات محدثة
        ]);
    }
    public function storefund(Request $request, $fund_id)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|string|',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'beneficiary_name' => 'nullable|string',
        ]);

        $fund = funds::findOrFail($fund_id);

        if (in_array($request->type, ['تحويل', 'عهدة']) && $fund->balance < $request->amount) {
            return response()->json(['message' => 'الرصيد غير كافٍ لإتمام العملية.'], 400);
        }

        // تحديد المبلغ بناءً على نوع المعاملة
        $amount = $request->amount;
        if (in_array($request->type, ['تحويل', 'عهدة'])) {
            $amount = -1 * $amount;
        }

        // إنشاء المعاملة
        $transaction = $fund->transactions()->create([
            'date' => $request->date,
            'type' => $request->type,
            'amount' => $amount,
            'description' => $request->description,
            'beneficiary_name' => $request->beneficiary_name,
        ]);

        // تحديث رصيد الحساب
        $fund->balance += $amount;
        $fund->save();

        return response()->json([
            'transaction' => $transaction,
            'funds' => funds::with('transactions')->get(), // أو أي طريقة لجلب الحسابات محدثة
        ]);
    }



    public function destroy($id)
    {
        $transaction = Transaction_Acount::findOrFail($id);
        $account = $transaction->account;

        // تحديث الرصيد قبل الحذف
        $account->balance -= $transaction->amount;
        $account->save();

        $transaction->delete();

        return response()->json(['message' => 'تم حذف المعاملة']);
    }
}
