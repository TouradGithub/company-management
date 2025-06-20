<?php

namespace App\Http\Controllers\Assets;

use Illuminate\Http\Request;
use App\Models\CategoryManagement;
use App\Http\Controllers\Controller;

class CategoryManagementController extends Controller
{
    public function index()
    {
        return response()->json(CategoryManagement::all());
    }

    public function show()
    {
        //
    }
    public function create()
    {
        //
    }
    public function edit()
    {
        //
    }

    public function store(Request $request)
    {
        $category = CategoryManagement::create([
            'categorycode' => $request->categorycode,
            'categorylifespan' => $request->categorylifespan,
            'categoryrate' => $request->categoryrate,
            'name' => $request->name,
        ]);
        return response()->json(['message' => 'تمت الإضافة بنجاح', 'category' => $category]);
    }
// ALTER TABLE vouchers_containers MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
// ALTER TABLE wastes
// MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY;

    public function update(Request $request, $id)
    {
        $category = CategoryManagement::findOrFail($id);
        $category->update($request->all());
        return response()->json(['message' => 'تم التحديث بنجاح', 'category' => $category]);
    }

    public function destroy($id)
    {
        CategoryManagement::destroy($id);
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}
