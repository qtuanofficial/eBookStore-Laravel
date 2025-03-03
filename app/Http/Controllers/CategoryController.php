<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Hiển thị danh sách danh mục
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // Hiển thị form tạo danh mục mới
    public function create()
    {
        return view('categories.create');
    }

    // Lưu danh mục mới vào CSDL
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|unique:categories,name',
            'description' => 'nullable'
        ]);

        Category::create($validated);
        return redirect()->route('categories.index')
            ->with('success', 'Danh mục đã được tạo thành công.');
    }

    // Hiển thị chi tiết danh mục
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Cập nhật dữ liệu danh mục
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|unique:categories,name,' . $category->id,
            'description' => 'nullable'
        ]);

        $category->update($validated);
        return redirect()->route('categories.index')
            ->with('success', 'Danh mục đã được cập nhật thành công.');
    }

    // Xóa danh mục
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Danh mục đã được xóa thành công.');
    }
}
