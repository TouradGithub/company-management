<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::where('company_id' , Auth::user()->model_id)->get();

        $products = Product::where('company_id' , Auth::user()->model_id)->get();
        $customers = Customer::where('company_id', Auth::user()->model_id)->get();
        return view('financialaccounting.invoices.index', compact('products','branches' , 'customers'));
    }

    public function create(Request $request)
    {
        $branches = Branch::where('company_id' , Auth::user()->model_id)->get();

        $products = Product::where('company_id' , Auth::user()->model_id)->get();
        $customers = Customer::where('company_id', Auth::user()->model_id)->get();

        $mainCustomerAccount = Account::where('company_id', Auth::user()->model_id)
            ->where('account_type_id', 1)
            ->first();

        // If there is a main account, get its complete tree
        if ($mainCustomerAccount) {
            // Collect all account IDs in the tree starting from this account
            $accountIds =getAccountTreeIds(collect([$mainCustomerAccount]));

            // Get all accounts that belong to this tree
            $accounts = Account::where('company_id', Auth::user()->model_id)
                ->whereIn('id', $accountIds)
                ->get();
        } else {
            $accounts = collect(); // Return empty collection if no main account
        }
        return view('financialaccounting.invoices.create', compact('products','branches' , 'customers' , 'accounts'));
    }

    public function getInvoices(Request $request) {
        $query = Invoice::query();
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        $invoices = $query->with(['branch','customer'])->get();
        return response()->json($invoices);
    }
    public function destroy($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'الفاتورة غير موجودة.'], 404);
        }

        $invoice->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف الفاتورة بنجاح.']);
    }
    public function updateStatus(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'الفاتورة غير موجودة.'], 404);
        }

        $request->validate([
            'status' => 'required',
        ]);

        $invoice->status = $request->status;
        $invoice->save();

        return response()->json([
            'success' => true,
            'new_status' => $invoice->status,
            'message' => 'تم تحديث حالة الفاتورة بنجاح.'
        ]);
    }


    public function store(Request $request)
    {

        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'customer_id' => 'required',
            'branch_id' => 'required',
            'items' => 'required|array',
            'items.*.productId' => 'required',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric',
            'items.*.total' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
        ], [
            'invoice_date.required' => 'تاريخ الفاتورة مطلوب.',
            'invoice_date.date' => 'تاريخ الفاتورة يجب أن يكون تاريخًا صالحًا.',
            'customer_id.required' => 'العميل مطلوب.',
            'branch_id.required' => 'الفرع مطلوب.',
            'items.required' => 'الأصناف مطلوبة.',
            'items.array' => 'الأصناف يجب أن تكون مصفوفة.',
            'items.*.productId.required' => 'معرف المنتج مطلوب لكل صنف.',
            'items.*.quantity.required' => 'الكمية مطلوبة لكل صنف.',
            'items.*.quantity.numeric' => 'الكمية يجب أن تكون رقمًا.',
            'items.*.quantity.min' => 'الكمية يجب أن لا تقل عن 1.',
            'items.*.price.required' => 'السعر مطلوب لكل صنف.',
            'items.*.price.numeric' => 'السعر يجب أن يكون رقمًا.',
            'items.*.total.required' => 'الإجمالي للصنف مطلوب.',
            'items.*.total.numeric' => 'الإجمالي للصنف يجب أن يكون رقمًا.',
            'subtotal.required' => 'الإجمالي الفرعي مطلوب.',
            'subtotal.numeric' => 'الإجمالي الفرعي يجب أن يكون رقمًا.',
            'discount.required' => 'الخصم مطلوب.',
            'discount.numeric' => 'الخصم يجب أن يكون رقمًا.',
            'tax.required' => 'الضريبة مطلوبة.',
            'tax.numeric' => 'الضريبة يجب أن تكون رقمًا.',
            'total.required' => 'الإجمالي مطلوب.',
            'total.numeric' => 'الإجمالي يجب أن يكون رقمًا.',
        ]);
        $errors = [];
        foreach ($validated['items'] as $key => $item) {
            $product = Product::find($item['productId']);
            if (!$product || $product->stock < $item['quantity']) {
                $errors["items.$key.quantity"] = "الكمية المطلوبة للمنتج {$product->name} غير متوفرة، المتاح فقط: {$product->stock}.";
            }
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateEntryNumber(Auth::user()->model_id),
            'invoice_date' => $validated['invoice_date'],
            'customer_id' => $validated['customer_id'],
            'branch_id' =>$validated['branch_id'],
            'company_id' => Auth::user()->model_id,
            'employee_id' => Auth::user()->name,
            'subtotal' => $validated['subtotal'],
            'discount' => $validated['discount'] ?? 0,
            'tax' => $validated['tax']??0,
            'total' => $validated['total'],
            'invoice_type'=>'Sales',
            'status' =>Invoice::STATUS_CONFIRMED,
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['productId']);
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['productId'],
                'product_name' => $item['productId'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);
            $product->decrement('stock', $item['quantity']);
        }
        return response()->json([
            'status' =>true,
            'message' =>"تم إضافة الفاتورة بنجاح"
        ], 201);
    }

    public function purchases(Request $request)
    {

        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'customer_id' => 'required',
            'branch_id' => 'required',
            'items' => 'required|array',
            'items.*.productId' => 'required',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric',
            'items.*.total' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
        ], [
            'invoice_date.required' => 'تاريخ الفاتورة مطلوب.',
            'invoice_date.date' => 'تاريخ الفاتورة يجب أن يكون تاريخًا صالحًا.',
            'customer_id.required' => 'العميل مطلوب.',
            'branch_id.required' => 'الفرع مطلوب.',
            'items.required' => 'الأصناف مطلوبة.',
            'items.array' => 'الأصناف يجب أن تكون مصفوفة.',
            'items.*.productId.required' => 'معرف المنتج مطلوب لكل صنف.',
            'items.*.quantity.required' => 'الكمية مطلوبة لكل صنف.',
            'items.*.quantity.numeric' => 'الكمية يجب أن تكون رقمًا.',
            'items.*.quantity.min' => 'الكمية يجب أن لا تقل عن 1.',
            'items.*.price.required' => 'السعر مطلوب لكل صنف.',
            'items.*.price.numeric' => 'السعر يجب أن يكون رقمًا.',
            'items.*.total.required' => 'الإجمالي للصنف مطلوب.',
            'items.*.total.numeric' => 'الإجمالي للصنف يجب أن يكون رقمًا.',
            'subtotal.required' => 'الإجمالي الفرعي مطلوب.',
            'subtotal.numeric' => 'الإجمالي الفرعي يجب أن يكون رقمًا.',
            'discount.required' => 'الخصم مطلوب.',
            'discount.numeric' => 'الخصم يجب أن يكون رقمًا.',
            'tax.required' => 'الضريبة مطلوبة.',
            'tax.numeric' => 'الضريبة يجب أن تكون رقمًا.',
            'total.required' => 'الإجمالي مطلوب.',
            'total.numeric' => 'الإجمالي يجب أن يكون رقمًا.',
        ]);
        $errors = [];
        foreach ($validated['items'] as $key => $item) {
            $product = Product::find($item['productId']);
            if (!$product || $product->stock < $item['quantity']) {
                $errors["items.$key.quantity"] = "الكمية المطلوبة للمنتج {$product->name} غير متوفرة، المتاح فقط: {$product->stock}.";
            }
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateEntryNumber(Auth::user()->model_id),
            'invoice_date' => $validated['invoice_date'],
            'customer_id' => $validated['customer_id'],
            'branch_id' =>$validated['branch_id'],
            'company_id' => Auth::user()->model_id,
            'employee_id' => Auth::user()->name,
            'subtotal' => $validated['subtotal'],
            'discount' => $validated['discount'] ?? 0,
            'tax' => $validated['tax']??0,
            'total' => $validated['total'],
            'invoice_type'=>'Purchases',
            'status' =>Invoice::STATUS_CONFIRMED,
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['productId']);
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['productId'],
                'product_name' => $item['productId'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);
            $product->decrement('stock', $item['quantity']);
        }
        return response()->json([
            'status' =>true,
            'message' =>"تم إضافة الفاتورة بنجاح"
        ], 201);
    }
    public function salesReturn(Request $request)
    {

        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'customer_id' => 'required',
            'original_invoice_number'=>'required',
            'branch_id' => 'required',
            'items' => 'required|array',
            'items.*.productId' => 'required',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric',
            'items.*.total' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
        ],
            [
            'invoice_date.required' => 'تاريخ الفاتورة مطلوب.',
            'invoice_date.date' => 'تاريخ الفاتورة يجب أن يكون تاريخًا صالحًا.',
            'original_invoice_number.required' => 'قم الفاتورة المرتجعه مطلوب.',
            'customer_id.required' => 'العميل مطلوب.',
            'branch_id.required' => 'الفرع مطلوب.',
            'items.required' => 'الأصناف مطلوبة.',
            'items.array' => 'الأصناف يجب أن تكون مصفوفة.',
            'items.*.productId.required' => 'معرف المنتج مطلوب لكل صنف.',
            'items.*.quantity.required' => 'الكمية مطلوبة لكل صنف.',
            'items.*.quantity.numeric' => 'الكمية يجب أن تكون رقمًا.',
            'items.*.quantity.min' => 'الكمية يجب أن لا تقل عن 1.',
            'items.*.price.required' => 'السعر مطلوب لكل صنف.',
            'items.*.price.numeric' => 'السعر يجب أن يكون رقمًا.',
            'items.*.total.required' => 'الإجمالي للصنف مطلوب.',
            'items.*.total.numeric' => 'الإجمالي للصنف يجب أن يكون رقمًا.',
            'subtotal.required' => 'الإجمالي الفرعي مطلوب.',
            'subtotal.numeric' => 'الإجمالي الفرعي يجب أن يكون رقمًا.',
            'discount.required' => 'الخصم مطلوب.',
            'discount.numeric' => 'الخصم يجب أن يكون رقمًا.',
            'tax.required' => 'الضريبة مطلوبة.',
            'tax.numeric' => 'الضريبة يجب أن تكون رقمًا.',
            'total.required' => 'الإجمالي مطلوب.',
            'total.numeric' => 'الإجمالي يجب أن يكون رقمًا.',
        ]);
        $errors = [];
        foreach ($validated['items'] as $key => $item) {
            $product = Product::find($item['productId']);
            if (!$product || $product->stock < $item['quantity']) {
                $errors["items.$key.quantity"] = "الكمية المطلوبة للمنتج {$product->name} غير متوفرة، المتاح فقط: {$product->stock}.";
            }
        }
        $parentInvoice = Invoice::where('invoice_number', $validated['original_invoice_number'])
            ->where('invoice_type','Sales')
            ->first();
        if(!$parentInvoice){
            return response()->json([
                'status' =>false,
                'message' =>'رقم الفاتورة غير موجود'
            ],422);
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateEntryNumber(Auth::user()->model_id),
            'parent_invoice_id' => $parentInvoice->id,
            'invoice_date' => $validated['invoice_date'],
            'customer_id' => $validated['customer_id'],
            'branch_id' =>$validated['branch_id'],
            'company_id' => Auth::user()->model_id,
            'employee_id' => Auth::user()->name,
            'subtotal' => $validated['subtotal'],
            'discount' => $validated['discount'] ?? 0,
            'tax' => $validated['tax']??0,
            'total' => $validated['total'],
            'invoice_type'=>'SalesReturn',
            'status' =>Invoice::STATUS_CONFIRMED,
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['productId']);
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['productId'],
                'product_name' => $item['productId'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);
            $product->increment('stock', $item['quantity']);
        }
        return response()->json([
            'status' =>true,
            'message' =>"تم إضافة الفاتورة بنجاح"
        ], 201);
    }
    public function purchaseReturn(Request $request)
    {

        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'customer_id' => 'required',
            'original_invoice_number'=>'required',
            'branch_id' => 'required',
            'items' => 'required|array',
            'items.*.productId' => 'required',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric',
            'items.*.total' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
        ], [
            'invoice_date.required' => 'تاريخ الفاتورة مطلوب.',
            'original_invoice_number.required' => 'قم الفاتورة المرتجعه مطلوب.',
            'invoice_date.date' => 'تاريخ الفاتورة يجب أن يكون تاريخًا صالحًا.',
            'customer_id.required' => 'العميل مطلوب.',
            'branch_id.required' => 'الفرع مطلوب.',
            'items.required' => 'الأصناف مطلوبة.',
            'items.array' => 'الأصناف يجب أن تكون مصفوفة.',
            'items.*.productId.required' => 'معرف المنتج مطلوب لكل صنف.',
            'items.*.quantity.required' => 'الكمية مطلوبة لكل صنف.',
            'items.*.quantity.numeric' => 'الكمية يجب أن تكون رقمًا.',
            'items.*.quantity.min' => 'الكمية يجب أن لا تقل عن 1.',
            'items.*.price.required' => 'السعر مطلوب لكل صنف.',
            'items.*.price.numeric' => 'السعر يجب أن يكون رقمًا.',
            'items.*.total.required' => 'الإجمالي للصنف مطلوب.',
            'items.*.total.numeric' => 'الإجمالي للصنف يجب أن يكون رقمًا.',
            'subtotal.required' => 'الإجمالي الفرعي مطلوب.',
            'subtotal.numeric' => 'الإجمالي الفرعي يجب أن يكون رقمًا.',
            'discount.required' => 'الخصم مطلوب.',
            'discount.numeric' => 'الخصم يجب أن يكون رقمًا.',
            'tax.required' => 'الضريبة مطلوبة.',
            'tax.numeric' => 'الضريبة يجب أن تكون رقمًا.',
            'total.required' => 'الإجمالي مطلوب.',
            'total.numeric' => 'الإجمالي يجب أن يكون رقمًا.',
        ]);
        $errors = [];
        foreach ($validated['items'] as $key => $item) {
            $product = Product::find($item['productId']);
            if (!$product || $product->stock < $item['quantity']) {
                $errors["items.$key.quantity"] = "الكمية المطلوبة للمنتج {$product->name} غير متوفرة، المتاح فقط: {$product->stock}.";
            }
        }
        $parentInvoice = Invoice::where('invoice_number', $validated['original_invoice_number'])
            ->where('invoice_type','Purchases')
            ->first();
        if(!$parentInvoice){
            return response()->json([
                'status' =>false,
                'message' =>'رقم الفاتورة غير موجود'
            ],422);
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateEntryNumber(Auth::user()->model_id),
            'parent_invoice_id' => $parentInvoice->id,
            'invoice_date' => $validated['invoice_date'],
            'customer_id' => $validated['customer_id'],
            'branch_id' =>$validated['branch_id'],
            'company_id' => Auth::user()->model_id,
            'employee_id' => Auth::user()->name,
            'subtotal' => $validated['subtotal'],
            'discount' => $validated['discount'] ?? 0,
            'tax' => $validated['tax']??0,
            'total' => $validated['total'],
            'invoice_type'=>'PurchasesReturn',
            'status' =>Invoice::STATUS_CONFIRMED,
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['productId']);
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['productId'],
                'product_name' => $item['productId'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);
            $product->increment('stock', $item['quantity']);
        }
        return response()->json([
            'status' =>true,
            'message' =>"تم إضافة الفاتورة بنجاح"
        ], 201);
    }

    public function getInvoiceByNumber($invoiceNumber)
    {
        $invoice = Invoice::where('invoice_number', $invoiceNumber)
            ->where('invoice_type', "Sales")
            ->with('items')->first();
        if (!$invoice) {
            return response()->json(['message' => 'الفاتورة غير موجودة'], 404);
        }
        return response()->json([
            'customer_id' => $invoice->customer_id,
            'invoice_date' => $invoice->invoice_date,
            'branch_id' => $invoice->branch_id,
            'items' => $invoice->items->map(function ($item) {
                return [
                    'productId' => $item->product_id,
                    'unit' => 1,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ];
            }),
        ]);
    }

    public function getPurchaseByNumber($invoiceNumber)
    {
        $purchase = Invoice::where('invoice_number', $invoiceNumber)
            ->where('invoice_type', "Purchases")
            ->with('items')->first();
        if (!$purchase) {
            return response()->json(['message' => ' الفاتورة غير موجودة'], 404);
        }
        return response()->json([
            'customer_id' => $purchase->customer_id,
            'supplier_id' => $purchase->customer_id,
            'invoice_date' => $purchase->invoice_date,
            'branch_id' => $purchase->branch_id,
            'items' => $purchase->items->map(function ($item) {
                return [
                    'productId' => $item->product_id,
                    'unit' => $item->unit,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ];
            }),
        ]);
    }
}
