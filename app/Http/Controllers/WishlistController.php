<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Hiển thị danh sách yêu thích của người dùng hiện tại
    public function index()
    {
        $wishlistItems = Wishlist::with('book')
            ->where('user_id', Auth::id())
            ->get();
        return view('wishlist.index', compact('wishlistItems'));
    }

    // Hiển thị form thêm mục vào danh sách yêu thích (nếu cần)
    public function create()
    {
        return view('wishlist.create');
    }

    // Lưu mục vào danh sách yêu thích
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        $validated['user_id'] = Auth::id();
        Wishlist::create($validated);
        return redirect()->route('wishlist.index')->with('success', 'Sản phẩm đã được thêm vào danh sách yêu thích.');
    }

    // (Tùy chọn) Hiển thị chi tiết mục yêu thích
    public function show(Wishlist $wishlist)
    {
        return view('wishlist.show', compact('wishlist'));
    }

    // (Tùy chọn) Chỉnh sửa mục yêu thích
    public function edit(Wishlist $wishlist)
    {
        return view('wishlist.edit', compact('wishlist'));
    }

    // (Tùy chọn) Cập nhật mục yêu thích
    public function update(Request $request, Wishlist $wishlist)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);
        $wishlist->update($validated);
        return redirect()->route('wishlist.index')->with('success', 'Danh sách yêu thích đã được cập nhật.');
    }

    // Xóa mục khỏi danh sách yêu thích
    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }
        $wishlist->delete();
        return redirect()->route('wishlist.index')->with('success', 'Sản phẩm đã được xóa khỏi danh sách yêu thích.');
    }
}
