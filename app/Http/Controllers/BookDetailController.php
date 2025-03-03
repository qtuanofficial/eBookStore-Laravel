<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookDetail;
use App\Models\Book;

class BookDetailController extends Controller
{
    // Hiển thị danh sách chi tiết sách
    public function index()
    {
        $details = BookDetail::with('book')->get();
        return view('book_details.index', compact('details'));
    }

    // Hiển thị form tạo chi tiết sách mới
    public function create()
    {
        $books = Book::all();
        return view('book_details.create', compact('books'));
    }

    // Lưu chi tiết sách mới vào CSDL
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id'          => 'required|exists:books,id|unique:book_detail,book_id',
            'description'      => 'nullable',
            'summary'          => 'nullable',
            'isbn'             => 'required|unique:book_detail,isbn',
            'publisher'        => 'nullable',
            'publication_date' => 'nullable|date',
            'pages'            => 'nullable|integer',
            'file_url'         => 'nullable|url'
        ]);

        BookDetail::create($validated);
        return redirect()->route('book_details.index')->with('success', 'Chi tiết sách đã được tạo.');
    }

    // Hiển thị chi tiết của 1 bản ghi chi tiết sách
    public function show(BookDetail $bookDetail)
    {
        return view('book_details.show', compact('bookDetail'));
    }

    // Hiển thị form chỉnh sửa chi tiết sách
    public function edit(BookDetail $bookDetail)
    {
        $books = Book::all();
        return view('book_details.edit', compact('bookDetail', 'books'));
    }

    // Cập nhật chi tiết sách
    public function update(Request $request, BookDetail $bookDetail)
    {
        $validated = $request->validate([
            'book_id'          => 'required|exists:books,id|unique:book_detail,book_id,' . $bookDetail->id,
            'description'      => 'nullable',
            'summary'          => 'nullable',
            'isbn'             => 'required|unique:book_detail,isbn,' . $bookDetail->id,
            'publisher'        => 'nullable',
            'publication_date' => 'nullable|date',
            'pages'            => 'nullable|integer',
            'file_url'         => 'nullable|url'
        ]);

        $bookDetail->update($validated);
        return redirect()->route('book_details.index')->with('success', 'Chi tiết sách đã được cập nhật.');
    }

    // Xóa chi tiết sách
    public function destroy(BookDetail $bookDetail)
    {
        $bookDetail->delete();
        return redirect()->route('book_details.index')->with('success', 'Chi tiết sách đã được xóa.');
    }
}
