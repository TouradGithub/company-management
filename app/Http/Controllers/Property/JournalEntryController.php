<?php

namespace App\Http\Controllers\Property;

use Illuminate\Http\Request;
use App\Models\journal_lines;
use App\Models\journal_entries;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JournalEntryController extends Controller
{
    // نموذج JournalEntry
    public function createRentAccrual($propertyId, $amount, $date) {
        DB::transaction(function () use ($propertyId, $amount, $date) {
            // إنشاء قيد يومية جديد
            $journalEntry = journal_entries::create([
                'date' => $date,
                'amount' => $amount,
                'description' => "استحقاق إيجار - العقار رقم: {$propertyId}",
                'entry_type' => 'rent',  // نوع القيد استحقاق
            ]);

            // إضافة التفاصيل في جدول journal_lines
            journal_lines::create([
                'journal_entry_id' => $journalEntry->id,
                'account_name' => 'ذمم مستأجرين',
                'debit' => $amount,
                'credit' => 0,
            ]);

            journal_lines::create([
                'journal_entry_id' => $journalEntry->id,
                'account_name' => 'إيرادات إيجار',
                'debit' => 0,
                'credit' => $amount,
            ]);
        });
    }
    // نموذج JournalEntry
    public function createRentPayment($propertyId, $amount, $paymentDate) {
        DB::transaction(function () use ($propertyId, $amount, $paymentDate) {
            // إنشاء قيد يومية جديد
            $journalEntry = journal_entries::create([
                'date' => $paymentDate,
                'amount' => $amount,
                'description' => "دفعة إيجار - العقار رقم: {$propertyId}",
                'entry_type' => 'payment',  // نوع القيد دفع
            ]);

            // إضافة التفاصيل في جدول journal_lines
            journal_lines::create([
                'journal_entry_id' => $journalEntry->id,
                'account_name' => 'الصندوق',
                'debit' => $amount,
                'credit' => 0,
            ]);

            journal_lines::create([
                'journal_entry_id' => $journalEntry->id,
                'account_name' => 'ذمم مستأجرين',
                'debit' => 0,
                'credit' => $amount,
            ]);
        });
    }
    public function create(Request $request) {
        $paymentDetails = $request->validate([
            'property_id' => 'required|integer',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
        ]);

        // تحديد إذا كان القيد استحقاق أو دفع بناءً على المعطيات
        if ($paymentDetails['entry_type'] === 'payment') {
            $this->createRentPayment($paymentDetails['property_id'], $paymentDetails['amount'], $paymentDetails['payment_date']);
        } else {
            $this->createRentAccrual($paymentDetails['property_id'], $paymentDetails['amount'], $paymentDetails['payment_date']);
        }

        return response()->json(['success' => true]);
    }


    public function fetchJournalEntries()
    {
        $entries = \App\Models\journal_entries::with(['property', 'lines'])->get();

        return response()->json($entries);
    }
}
