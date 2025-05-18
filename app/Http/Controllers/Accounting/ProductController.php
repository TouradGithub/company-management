<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Models\Branch;
use App\Models\CategoryInvoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(){
        $categories = CategoryInvoice::where('company_id' , getCompanyId())->get();
        $branches = Branch::where('company_id' , getCompanyId())->get();
        return view('financialaccounting.products.index' ,compact('categories' , 'branches'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'productName' => 'required|string|max:255',
            'productCategory' => 'required|integer',
            'branch_id' => 'required',
            'stock' => 'required|integer|min:0',
            'productDescription' => 'nullable|string',
            'productPrice' => 'required|numeric|min:0',
            'productCost' => 'required|numeric|min:0',
            'productMinPrice' => 'nullable|numeric|min:0',
            'productTax' => 'nullable|numeric|min:0',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('images')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->productName,
            'category_id' => $request->productCategory,
            'branch_id' => $request->branch_id,
            'stock' => $request->stock,
            'description' => $request->productDescription,
            'price' => $request->productPrice,
            'cost' => $request->productCost,
            'min_price' => $request->productMinPrice,
            'tax' => $request->productTax,
            'image' => $imagePath,
            'company_id' => getCompanyId(),
            'created_by' => Auth::user()->name,
        ]);

        return response()->json(['message' => 'تمت إضافة المنتج بنجاح' ,'status'=>201], 201);
    }
    public function getProducts()
    {
        $products = Product::with('category')->where('company_id' , getCompanyId())->latest()->get();

        return response()->json($products);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return response()->json(['success' => true, 'product' => $product]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'productName' => 'required|string|max:255',
            'productCategory' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'productPrice' => 'required|numeric|min:0',
            'productCost' => 'required|numeric|min:0',
            'branch_id' => 'required',
            'images.*' => 'image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->errors()], 422);
        }

        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->productName,
            'category_id' => $request->productCategory,
            'stock' => $request->stock,
            'description' => $request->productDescription,
            'price' => $request->productPrice,
            'cost' => $request->productCost,
            'min_price' => $request->productMinPrice,
            'tax' => $request->productTax,
            'branch_id' => $request->branch_id,
        ]);

        if ($request->hasFile('images')) {
            // Optionally delete old images if needed
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return response()->json(['status' => 200, 'message' => 'Product updated successfully']);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
    }

    public function importProducts(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file',
        ]);
        if (!$request->hasFile('import_file')) {
            return back()->with('error', 'No file uploaded.');
        }
        try {
            $file = $request->file('import_file');
            $destinationPath = storage_path('app/imports');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $fullPath = $destinationPath . '/' . $fileName;
            if (!file_exists($fullPath)) {
                return response()->json(['success' => false, 'msg' => 'File could not be found.']);
            }
            Excel::import(new ProductsImport(),  $fullPath);
            return back()->with('success', 'تم استيراد المنتجات بنجاح ✅');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }

}
