<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;

class SubCategoryController extends Controller
{
    // Hiển thị danh sách danh mục con
    public function index()
    {
        $subCategories = SubCategory::all();
        return view('subcategories.index', compact('subCategories'));
    }

    // Hiển thị form tạo danh mục con mới
    public function create()
    {
        $categories = Category::all();
        return view('subcategories.create', compact('categories'));
    }

    // Lưu danh mục con mới vào CSDL
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id'   => 'required|exists:categories,id',
            'name'        => 'required|unique:sub_categories,name',
            'description' => 'nullable'
        ]);

        SubCategory::create($validated);
        return redirect()->route('subcategories.index')->with('success', 'Danh mục con đã được tạo thành công.');
    }

    // Hiển thị chi tiết danh mục con
    public function show(SubCategory $subCategory)
    {
        return view('subcategories.show', compact('subCategory'));
    }

    // Hiển thị form chỉnh sửa danh mục con
    public function edit(SubCategory $subCategory)
    {
        $categories = Category::all();
        return view('subcategories.edit', compact('subCategory', 'categories'));
    }

    // Cập nhật danh mục con
    public function update(Request $request, SubCategory $subCategory)
    {
        $validated = $request->validate([
            'parent_id'   => 'required|exists:categories,id',
            'name'        => 'required|unique:sub_categories,name,' . $subCategory->id,
            'description' => 'nullable'
        ]);

        $subCategory->update($validated);
        return redirect()->route('subcategories.index')->with('success', 'Danh mục con đã được cập nhật thành công.');
    }

    // Xóa danh mục con
    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();
        return redirect()->route('subcategories.index')->with('success', 'Danh mục con đã được xóa.');
    }
}
