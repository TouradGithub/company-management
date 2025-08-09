<?php

namespace App\Http\Controllers;

use App\Models\brand;
use App\Models\items;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Category;
use App\Models\Productlite;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'code' => 'nullable',
            'category_id' => 'required|integer',
            'brand_id' => 'nullable|integer',
            'barcode' => 'nullable',
            'status' => 'required|in:0,1,2',
            'description' => 'nullable',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'tax_rate' => 'required|numeric',
            'price_unit' => 'required|string',
            'has_discount' => 'boolean',
            'stock' => 'required|integer',
            'stock_alert' => 'nullable|integer',
            'warehouse_id' => 'required|integer',
            'main_image' => 'nullable|image',
            'additional_images.*' => 'image|nullable',
        ]);

        // حفظ الصورة الرئيسية
        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        // حفظ الصور الإضافية
        if ($request->hasFile('additional_images')) {
            $images = [];
            foreach ($request->file('additional_images') as $image) {
                $images[] = $image->store('products', 'public');
            }
            $data['additional_images'] = $images;
        }

        $product = Productlite::create($data);
        ProductWarehouse::create([
            'product_id' => $product->id,
            'warehouse_id' => $data['warehouse_id'],
            'stock' => $data['stock'],
        ]);
        return response()->json(['success' => true, 'message' => 'تمت إضافة المنتج بنجاح!', 'product_id' => $product->id]);
    }


    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = Productlite::where('name', 'LIKE', "%$query%")
            ->select('id', 'name', 'selling_price','code','tax_rate','stock','purchase_price') // يمكنك إضافة الحقول المطلوبة
            ->limit(10)
            ->get();

        return response()->json($products);
    }



    public function fetchProducts()
    {
        $products = Productlite::with('category')->get();

        $data = $products->map(function ($product) {
            $unitProfit = $product->selling_price - $product->purchase_price;
            $totalCost = $product->purchase_price * $product->stock;
            $totalProfit = $unitProfit * $product->stock;

            return [
                'name' => $product->name,
                'id' => (string)$product->id,
                'quantity' => (int)$product->stock,
                'unitPrice' => (float)$product->selling_price,
                'unitCost' => (float)$product->purchase_price,
                'unitProfit' => (float)$unitProfit,
                'totalCost' => (float)$totalCost,
                'totalProfit' => (float)$totalProfit,
                'category' => optional($product->category)->name ?? 'غير محدد',
            ];
        });

        return response()->json($data);
    }

    public function fetchCostDetails()
    {
        $products = Productlite::with('category')->get();

        $data = $products->map(function ($product) {
            $purchase_price = $product->purchase_price ?? 0;

            // كمية البداية
            $qtyBeginning = ($product->transfers ?? 0) + ($product->damages ?? 0) + ($product->stock ?? 0);

            // رصيد البداية
            $invBeginning = $qtyBeginning * $purchase_price;

            // المشتريات
            $purchaseItems = items::where('product_id', $product->id)
            ->whereHas('bill', fn($q) => $q->where('type', 'مشتريات'))->get();

            $qtyPurchase = $purchaseItems->sum('quantity');
            $valuePurchase = $purchaseItems->sum('total'); // ← مجموع السعر الإجمالي الفعلي

            // المبيعات
            $saleItems = items::where('product_id', $product->id)
            ->whereHas('bill', fn($q) => $q->where('type', 'مبيعات'))->get();

            $qtySales = $saleItems->sum('quantity');
            $valueSales = $saleItems->sum('total');

            // التحويلات
            $transfers = $product->transfers ?? 0;
            $valueTransfers = $transfers * $purchase_price;

            // التلفيات
            $damages = $product->damages ?? 0;
            $valueDamages = $damages * $purchase_price;

            // القيمة الدفترية
            $valueBook = $invBeginning + $valuePurchase - $valueTransfers - $valueSales - $valueDamages;

            // العدد الدفتري
            $qtyBook = $qtyBeginning + $qtyPurchase - $transfers - $qtySales - $damages;

            return [
                'productNumber' => $product->code ?? $product->id,
                'productName' => $product->name,
                'category' => $product->category->name ?? '-',
                'invUnit' => $product->price_unit ?? '-',
                'qtyBeginning' => $qtyBeginning,
                'invBeginning' => number_format($invBeginning) . ' ريال',
                'valuePurchase' => number_format($valuePurchase) . ' ريال',
                'qtyPurchase' => $qtyPurchase,
                'transfers' => $transfers,
                'valueTransfers' => number_format($valueTransfers) . ' ريال',
                'qtySales' => $qtySales,
                'valueSales' => number_format($valueSales) . ' ريال',
                'damages' => $damages,
                'valueDamages' => number_format($valueDamages) . ' ريال',
                'valueBook' => number_format($valueBook) . ' ريال',
                'qtyBook' => $qtyBook,
            ];
        });

        return response()->json($data);
    }




    public function getTransferProducts()
    {
        $products = Productlite::with('category')->get();

        // تنسيق البيانات
        $mapped = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'code' => $product->code ?? "P" . str_pad($product->id, 4, "0", STR_PAD_LEFT),
                'name' => $product->name,
                'category' => optional($product->category)->name ?? 'غير محدد',
                'quantity' => (float) $product->stock,
                'unitCost' => (float) $product->purchase_price,
            ];

        });

        // استخراج الفئات
        $categories = $mapped->pluck('category')->unique()->values();

        return response()->json([
            'products' => $mapped,
            'categories' => $categories
        ]);
    }
    public function getCategoryData()
    {
        $categories = ProductCategory::with('products')->get();

        $categoryData = $categories->map(function ($category) {
            $purchaseTotal = 0;
            $salesTotal = 0;

            foreach ($category->products as $product) {
                $items = \App\Models\items::where('product_id', $product->id)
                    ->with('bill')
                    ->get();

                foreach ($items as $item) {
                    if (!$item->bill) continue;

                    if ($item->bill->type === 'مشتريات') {
                        $purchaseTotal += $item->total;
                    } elseif ($item->bill->type === 'مبيعات') {
                        $salesTotal += $item->total;
                    }
                }
            }

            $variance = $salesTotal - $purchaseTotal;

            return [
                'category' => $category->name,
                'purchasePrice' => round($purchaseTotal, 2),
                'productionValue' => round($salesTotal, 2),
                'variance' => round($variance, 2),
            ];
        });

        return response()->json($categoryData);
    }



    public function getProducts()
    {
        $products = Productlite::with('category')->get()->map(function ($product) {
            return [
                'code' => $product->code,
                'name' => $product->name,
                'category' => $product->category->name,
                'unitCost' => $product->purchase_price,
                'currentCount' => $product->stock
            ];
        });
        $categories = ProductCategory::all();

        // return response()->json($products,$categories);
        return response()->json([
            'products' => $products,
            'categories' => $categories
        ]);
    }
    public function updateStock(Request $request)
    {
        $items = $request->input('items');

        foreach ($items as $item) {
            Productlite::where('code', $item['code'])->update([
                'stock' => $item['actualCount']
            ]);
        }

        return response()->json(['message' => 'تم تحديث الكميات بنجاح']);
    }

    public function show($id)
    {
        $product = Productlite::findOrFail($id);
        $brands = brand::all(['id', 'name']);
        $warehouse = Branch::all(['id', 'name']);
        $categories = ProductCategory::all(['id', 'name']);

        return response()->json([
            'product' => $product,
            'brands' => $brands,
            'categories' => $categories,
            'warehouse' => $warehouse
        ]);
    }
    public function getSelects()
    {
        $brands = brand::all(['id', 'name']);
        $warehouse = Branch::all(['id', 'name']);
        $categories = ProductCategory::all(['id', 'name']);

        return response()->json([
            'brands' => $brands,
            'categories' => $categories,
            'warehouse' => $warehouse
        ]);
    }


    public function update(Request $request, $id)
    {
        $product = Productlite::findOrFail($id);
        $product->update($request->all());

        if ($request->filled('warehouse_id') && $request->filled('stock')) {
            // إيجاد السجل القديم أولاً
            $existingStock = ProductWarehouse::where('product_id', $product->id)->first();

            if ($existingStock) {
                // تحديث السجل القديم بمعلومات جديدة
                $existingStock->update([
                    'warehouse_id' => $request->warehouse_id,
                    'stock' => $request->stock,
                ]);
            } else {
                // إذا لا يوجد سجل، أنشئ واحدًا جديدًا
                ProductWarehouse::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $request->warehouse_id,
                    'stock' => $request->stock,
                ]);
            }
        }

        return response()->json(['status' => 'updated']);
    }


    public function destroy($id)
    {
        Productlite::destroy($id);
        return response()->json(['status' => 'deleted']);
    }

}
