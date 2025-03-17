<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\CategoryInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategorieInvoiceController extends Controller
{
    public function index(){
        $categorie_invoices = CategoryInvoice::where('company_id', Auth::user()->model_id)->get();
        return view('financialaccounting.categorieInvoices.index', compact('categorie_invoices'));
    }

    public function getCategories()
    {
        $categories  = CategoryInvoice::where('company_id', Auth::user()->model_id)->get();

        return response()->json($categories);
    }


    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string|max:500',
            'parent_id' => 'nullable',

        ]);

        // إنشاء التصنيف
        $category = CategoryInvoice::create([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'company_id'=>Auth::user()->model_id
        ]);

        return response()->json([
            'success' => true,
            'category' => $category
        ], 201);
    }

    // CategoryController.php
    public function delete($id)
    {
        $category = CategoryInvoice::find($id);
//
//        if($category->products()->count() > 0){
//            return response()->json([
//                'success' => false,
//                'message' => '   لا يمكن حذف التصنيف الان يحتوي على منتجات.'
//            ]);
//        }

        if($category->children()->count() > 0){
            return response()->json([
                'success' => false,
                'message' => '   لا يمكن حذف التصنيف الان يحتوي على فروع.'
            ]);
        }

        if ($category) {
            $category->delete();
            return response()->json(['success' => true, 'message' => 'تم حذف التصنيف بنجاح.']);
        }

        return response()->json(['success' => false, 'message' => 'التصنيف غير موجود.']);
    }
    public function edit($id)
    {
        $category = CategoryInvoice::find($id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'التصنيف غير موجود'], 404);
        }

        return response()->json(['success' => true, 'category' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = CategoryInvoice::find($id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'التصنيف غير موجود'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json(['success' => true, 'message' => 'تم تحديث التصنيف بنجاح']);
    }


}
