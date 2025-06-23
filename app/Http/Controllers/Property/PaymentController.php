<?php

namespace App\Http\Controllers\Property;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\journal_lines;
use App\Models\journal_entries;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    //

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $payment = Payment::create($validated);
        // 2. إنشاء القيد المحاسبي بعد الدفع
        $this->createJournalEntryForPayment($payment);

        return response()->json([
            'success' => true,
            'payment' => $payment
        ]);
    }

    protected function createJournalEntryForPayment($payment)
    {

        $methodLabel = [
            'cash' => 'نقدًا',
            'check' => 'عن طريق شيك',
            'transfer' => 'عن طريق تحويل بنكي',
        ];

        $type = 'إيراد إيجار';

        // إنشاء القيد الأساسي
        $entry = \App\Models\journal_entries::create([
            'date' => $payment->date,
            'property_id' => $payment->property_id,
            'description' => 'دفعة إيجار للعقار #' . $payment->property_id . ' ' . ($methodLabel[$payment->method] ?? ''),
            'type' => $type,
        ]);


        // إنشاء القيود الفرعية (المدين والدائن)
        \App\Models\journal_lines::create([
            'journal_entry_id' => $entry->id,
            'account_name' => 'نقدية', // أو 'الصندوق' أو 'البنك'
            'debit' => $payment->amount,
            'credit' => 0,
        ]);

        \App\Models\journal_lines::create([
            'journal_entry_id' => $entry->id,
            'account_name' => 'إيرادات الإيجار',
            'debit' => 0,
            'credit' => $payment->amount,
        ]);
    }
}
