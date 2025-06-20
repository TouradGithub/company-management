<?php

namespace App\Http\Controllers\BankManagment;
use App\Models\funds;

use Illuminate\Http\Request;

use App\Models\TransactionFunds;
use App\Http\Controllers\Controller;

class TransactionFundController extends Controller
{
    public function storeIncome(Request $request)
    {
        $validated = $request->validate([
            'voucher_number' => 'required|string',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'funds_id' => 'required|exists:funds,id',
        ]);

        $validated['type'] = 'income'; // سند قبض
        // زيادة الرصيد في الصندوق
        $fund = funds::find($validated['funds_id']);
        $fund->balance += $validated['amount'];
        $fund->save();
        TransactionFunds::create($validated);

        return response()->json(['status' => 'success', 'message' => 'تم حفظ سند القبض بنجاح.']);
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'voucher_number' => 'required|string',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'funds_id' => 'required|exists:funds,id',
        ]);

        $validated['type'] = 'expense'; // سند صرف

        // إنقاص الرصيد من الصندوق
        $fund = Funds::find($validated['funds_id']);

        if ($fund->balance < $validated['amount']) {
            return response()->json(['status' => 'error', 'message' => 'الرصيد في الصندوق غير كافٍ لإتمام العملية.'], 400);
        }

        $fund->balance -= $validated['amount'];
        $fund->save();

        TransactionFunds::create($validated);

        return response()->json(['status' => 'success', 'message' => 'تم حفظ سند الصرف بنجاح.']);
    }
}
