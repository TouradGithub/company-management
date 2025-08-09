<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\bills;
use App\Models\Product;
use App\Models\Productlite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $products = Productlite::with(['category', 'brand','warehouse']) // تأكد أن العلاقات معرفة
            ->latest()
            ->get();

        return response()->json($products);
    }
    public function salesView()
    {
        return view('bills.sales');
    }

    public function purchaseView()
    {
        return view('bills.purchase');
    }

    public function getSalesData()
    {
        $items = \App\Models\items::select(
                'items.name as product_name',
                'items.code as product_code',
                'items.quantity',
                'items.total',
                'items.tax_rate',
                'product_inventories.stock',
                'bills.date',
                'bills.customer',
                'bills.number as bill_number'
            )
            ->join('bills', 'items.bills_id', '=', 'bills.id')
            ->leftJoin('product_inventories', 'items.product_id', '=', 'product_inventories.id')
            ->where('bills.type', 'مبيعات')
            ->orderBy('bills.date', 'desc')
            ->get()
            ->map(function ($item, $index) {
                $tax = $item->total * $item->tax_rate / 100;
                $total_with_tax = $item->total + $tax;

                // حساب المتوسط
                $midel = ($item->quantity > 0) ? $item->total / $item->quantity : 0;

                return [
                    'serial' => $index + 1,
                    'date' => $item->date,
                    'customer' => $item->customer,
                    'product_name' => $item->product_name,
                    'product_code' => $item->product_code,
                    'bill_number' => $item->bill_number,
                    'quantity' => $item->quantity,
                    'total' => number_format($item->total, 2),
                    'tax' => number_format($tax, 2),
                    'total_with_tax' => number_format($total_with_tax, 2),
                    'midel' => number_format($midel, 2), // إضافة المتوسط
                    'stock' => $item->product->stock ?? 'غير متوفر'
                ];
            });

        return response()->json($items);
    }
    public function getPurchaseData()
    {
        $items = \App\Models\items::select(
                'items.name as product_name',
                'items.code as product_code',
                'items.quantity',
                'items.total',
                'items.tax_rate',
                'product_inventories.stock',
                'bills.date',
                'bills.customer',
                'bills.number as bill_number'
            )
            ->join('bills', 'items.bills_id', '=', 'bills.id')
            ->leftJoin('product_inventories', 'items.product_id', '=', 'product_inventories.id')
            ->where('bills.type', 'مشتريات')
            ->orderBy('bills.date', 'desc')
            ->get()
            ->map(function ($item, $index) {
                $tax = $item->total * $item->tax_rate / 100;
                $total_with_tax = $item->total + $tax;

                // حساب المتوسط
                $midel = ($item->quantity > 0) ? $item->total / $item->quantity : 0;

                return [
                    'serial' => $index + 1,
                    'date' => $item->date,
                    'customer' => $item->customer,
                    'product_name' => $item->product_name,
                    'product_code' => $item->product_code,
                    'bill_number' => $item->bill_number,
                    'quantity' => $item->quantity,
                    'total' => number_format($item->total, 2),
                    'tax' => number_format($tax, 2),
                    'total_with_tax' => number_format($total_with_tax, 2),
                    'midel' => number_format($midel, 2), // إضافة المتوسط
                    'stock' => $item->product->stock ?? 'غير متوفر'
                ];
            });

        return response()->json($items);
    }


    public function profitReport()
    {
            $items = DB::table('items')
                ->join('bills', 'items.bills_id', '=', 'bills.id')
                ->join('product_inventories', 'items.product_id', '=', 'product_inventories.id')
                ->where('bills.type', 'مبيعات')
                ->select(
                    'items.product_id',
                    'product_inventories.name as product_name',
                    'product_inventories.purchase_price',
                    DB::raw('SUM(items.quantity) as quantity_sold'),
                    DB::raw('SUM(items.total) as total_selling')
                )
                ->groupBy('items.product_id', 'product_inventories.name', 'product_inventories.purchase_price')
                ->get();

        $report = [];
        $index = 1;

        foreach ($items as $item) {
            $avgSellingPrice = $item->quantity_sold > 0 ? $item->total_selling / $item->quantity_sold : 0;
            $totalPurchase = $item->purchase_price * $item->quantity_sold;
            $profit = $item->total_selling - $totalPurchase;

            $report[] = [
                'index' => $index++,
                'name' => $item->product_name,
                'product_id' => $item->product_id,
                'quantity_sold' => $item->quantity_sold,
                'avg_selling_price' => number_format($avgSellingPrice, 2),
                'total_selling' => number_format($item->total_selling, 2),
                'avg_purchase_price' => number_format($item->purchase_price, 2),
                'total_purchase' => number_format($totalPurchase, 2),
                'profit' => number_format($profit, 2),
            ];
        }

        return response()->json($report);
    }


    public function profitPerBill()
    {
        $bills = DB::table('bills')
            ->where('type', 'مبيعات')
            ->join('items', 'bills.id', '=', 'items.bills_id')
            ->join('product_inventories', 'items.product_id', '=', 'product_inventories.id')
            ->select(
                'bills.id',
                'bills.number',
                'bills.date',
                DB::raw('SUM(items.total) as total_selling'),
                DB::raw('SUM(items.quantity * product_inventories.purchase_price) as total_purchase')
            )
            ->groupBy('bills.id', 'bills.number', 'bills.date')
            ->get();

        $report = [];

        foreach ($bills as $bill) {
            $profit = $bill->total_selling - $bill->total_purchase;
            $profitPercentage = $bill->total_selling > 0 ? ($profit / $bill->total_selling) * 100 : 0;

            $report[] = [
                'number' => $bill->number,
                'date' => $bill->date,
                'total_selling' => number_format($bill->total_selling, 2),
                'total_purchase' => number_format($bill->total_purchase, 2),
                'profit' => number_format($profit, 2),
                'profit_percentage' => number_format($profitPercentage, 2) . '%',
            ];
        }

        return response()->json($report);
    }

    public function getDailySales()
    {
        $bills = DB::table('bills')
            ->leftJoin('payment_methods', 'bills.payment_method_id', '=', 'payment_methods.id')
            ->leftJoin('delivery_types', 'bills.delivery_type_id', '=', 'delivery_types.id')
            ->selectRaw('
                DATE(bills.date) as date,
                SUM(bills.amount) as totalSales,
                SUM(CASE WHEN payment_methods.name IN ("نقدي", "فيزا") THEN bills.amount ELSE 0 END) as cashPayment,
                SUM(CASE WHEN payment_methods.name IN ("مدى", "اجل") THEN bills.amount ELSE 0 END) as electronicPayment,
                SUM(CASE WHEN delivery_types.name = "محلي" THEN bills.amount ELSE 0 END) as messenger,
                SUM(CASE WHEN delivery_types.name = "هنجراستيشن" THEN bills.amount ELSE 0 END) as hungerstation,
                SUM(CASE WHEN delivery_types.name = "توصيل" THEN bills.amount ELSE 0 END) as toyou,
                SUM(CASE WHEN delivery_types.name = "كيتا" THEN bills.amount ELSE 0 END) as kita
            ')
            ->groupBy(DB::raw('DATE(bills.date)'))
            ->get()
            ->map(function ($bill) {
                $date = Carbon::parse($bill->date);
                $dayName = $date->translatedFormat('l');

                $salesBeforeTax = $bill->totalSales / 1.15;
                $tax = $bill->totalSales - $salesBeforeTax;

                return [
                    'date' => $bill->date,
                    'dayName' => $dayName,
                    'salesBeforeTax' => round($salesBeforeTax, 2),
                    'tax' => round($tax, 2),
                    'totalSales' => round($bill->totalSales, 2),
                    'cashPayment' => round($bill->cashPayment, 2),
                    'electronicPayment' => round($bill->electronicPayment, 2),
                    'messenger' => round($bill->messenger, 2),
                    'hungerstation' => round($bill->hungerstation, 2),
                    'toyou' => round($bill->toyou, 2),
                    'kita' => round($bill->kita, 2),
                ];
            });

        return response()->json($bills);
    }

    public function getStats()
    {
        // إجمالي المبيعات
        $totalSales = Bills::where('type', 'مبيعات')->sum('amount');

        // مبيعات نقدية
        $cashSales = Bills::where('type', 'مبيعات')
            ->where('payment_method_id', 1) // نقدي
            ->sum('amount');

        // مبيعات دفع إلكتروني
        $electronicSales = Bills::where('type', 'مبيعات')
            ->where('payment_method_id', 2) // إلكتروني
            ->sum('amount');

        // إجمالي المشتريات
        $totalPurchases = Bills::where('type', 'مشتريات')->sum('amount');

        // مردودات المشتريات
        // $purchaseReturns = Bills::where('type', 'purchase_return')->sum('amount');

        // // مردودات المبيعات
        // $salesReturns = Bills::where('type', 'sale_return')->sum('amount');

        // قيمة المخزون الحالي
        $currentInventoryValue = Productlite::sum(DB::raw('purchase_price * stock'));

        return response()->json([
            'sales' => $totalSales,
            'cashSales' => $cashSales,
            'electronicSales' => $electronicSales,
            'totalPurchases' => $totalPurchases,
            // 'purchaseReturns' => $purchaseReturns,
            // 'salesReturns' => $salesReturns,
            'currentInventoryValue' => $currentInventoryValue
        ]);
    }
}
