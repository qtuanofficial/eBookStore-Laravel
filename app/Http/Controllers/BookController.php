<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\SubCategory;

class BookController extends Controller
{
    // Hiển thị danh sách sách
    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    // Hiển thị form tạo sách mới
    public function create()
    {
        $subCategories = SubCategory::all();
        return view('books.create', compact('subCategories'));
    }

    // Lưu sách mới vào CSDL
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required',
            'author'          => 'required',
            'cover'           => 'nullable|image', // upload hình (nếu có)
            'sub_category_id' => 'required|exists:sub_categories,id',
            'price'           => 'required|numeric'
        ]);

        // Xử lý upload file nếu có
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $validated['cover'] = $path;
        }

        Book::create($validated);
        return redirect()->route('books.index')
            ->with('success', 'Sách đã được tạo thành công.');
    }

    // Hiển thị chi tiết sách
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    // Hiển thị form chỉnh sửa sách
    public function edit(Book $book)
    {
        $subCategories = SubCategory::all();
        return view('books.edit', compact('book', 'subCategories'));
    }

    // Cập nhật dữ liệu sách
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'           => 'required',
            'author'          => 'required',
            'cover'           => 'nullable|image',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'price'           => 'required|numeric'
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $validated['cover'] = $path;
        }

        $book->update($validated);

        return redirect()->route('books.index')
            ->with('success', 'Sách đã được cập nhật thành công.');
    }

    // Xóa sách
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')
            ->with('success', 'Sách đã được xóa thành công.');
    }
}
