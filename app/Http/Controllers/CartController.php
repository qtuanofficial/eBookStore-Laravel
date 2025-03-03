<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Hiển thị giỏ hàng của người dùng hiện tại
    public function index()
    {
        $cart = Cart::with('items.book')->where('user_id', Auth::id())->first();
        if (!$cart) {
            $cart = Cart::create(['user_id' => Auth::id(), 'total' => 0]);
        }
        return view('cart.index', compact('cart'));
    }

    // Xóa toàn bộ nội dung giỏ hàng (làm mới giỏ hàng)
    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }
        $cart->items()->delete();
        $cart->update(['total' => 0]);
        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được làm mới.');
    }
}
