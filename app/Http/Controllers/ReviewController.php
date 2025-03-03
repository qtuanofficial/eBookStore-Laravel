<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Hiển thị danh sách đánh giá (có thể dùng cho admin)
    public function index()
    {
        $reviews = Review::with('user', 'book')->get();
        return view('reviews.index', compact('reviews'));
    }

    // Hiển thị form tạo đánh giá mới
    public function create()
    {
        $books = Book::all();
        return view('reviews.create', compact('books'));
    }

    // Lưu đánh giá mới vào CSDL
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable'
        ]);

        $validated['user_id'] = Auth::id();
        Review::create($validated);
        return redirect()->route('reviews.index')->with('success', 'Đánh giá đã được gửi.');
    }

    // Hiển thị chi tiết đánh giá
    public function show(Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    // Hiển thị form chỉnh sửa đánh giá (chỉ cho chủ đánh giá)
    public function edit(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }
        return view('reviews.edit', compact('review'));
    }

    // Cập nhật đánh giá
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable'
        ]);

        $review->update($validated);
        return redirect()->route('reviews.index')->with('success', 'Đánh giá đã được cập nhật.');
    }

    // Xóa đánh giá
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }
        $review->delete();
        return redirect()->route('reviews.index')->with('success', 'Đánh giá đã được xóa.');
    }
}
