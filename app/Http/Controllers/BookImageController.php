<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookImage;
use App\Models\Book;

class BookImageController extends Controller
{
    // Hiển thị danh sách ảnh
    public function index()
    {
        $images = BookImage::with('book')->get();
        return view('book_images.index', compact('images'));
    }

    // Hiển thị form tải ảnh lên mới
    public function create()
    {
        $books = Book::all();
        return view('book_images.create', compact('books'));
    }

    // Lưu ảnh mới vào CSDL
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id'  => 'required|exists:books,id',
            'image_url'=> 'required|image',
            'alt_text' => 'nullable|string'
        ]);

        // Xử lý upload ảnh
        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('book_images', 'public');
            $validated['image_url'] = $path;
        }

        BookImage::create($validated);
        return redirect()->route('book_images.index')->with('success', 'Ảnh của sách đã được tải lên.');
    }

    // Hiển thị chi tiết ảnh
    public function show(BookImage $bookImage)
    {
        return view('book_images.show', compact('bookImage'));
    }

    // Hiển thị form chỉnh sửa ảnh
    public function edit(BookImage $bookImage)
    {
        $books = Book::all();
        return view('book_images.edit', compact('bookImage', 'books'));
    }

    // Cập nhật ảnh
    public function update(Request $request, BookImage $bookImage)
    {
        $validated = $request->validate([
            'book_id'  => 'required|exists:books,id',
            'image_url'=> 'nullable|image',
            'alt_text' => 'nullable|string'
        ]);

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('book_images', 'public');
            $validated['image_url'] = $path;
        }

        $bookImage->update($validated);
        return redirect()->route('book_images.index')->with('success', 'Ảnh của sách đã được cập nhật.');
    }

    // Xóa ảnh
    public function destroy(BookImage $bookImage)
    {
        $bookImage->delete();
        return redirect()->route('book_images.index')->with('success', 'Ảnh của sách đã được xóa.');
    }
}
