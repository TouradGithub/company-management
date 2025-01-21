<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('company_id',auth()->user()->model_id)->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * عرض صفحة إنشاء فئة جديدة.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * تخزين الفئة الجديدة في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:categories,code|max:255',
        ]);

        Category::create([
            'name'=> $validated['name'],
            'code'=> $validated['code'],
            'company_id'=> auth()->user()->model_id,
        ]
        );


        return redirect()->route('categories.index')->with('success', 'Category added successfully!');
    }

    /**
     * عرض صفحة تعديل الفئة.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * تحديث بيانات الفئة.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:categories,code,' . $id . '|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * حذف الفئة.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
