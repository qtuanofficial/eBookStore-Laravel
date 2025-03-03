<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    // Thêm sản phẩm vào giỏ hàng
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id'  => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Tìm giỏ hàng của người dùng hoặc tạo mới nếu chưa tồn tại
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['total' => 0]
        );

        $book = Book::findOrFail($validated['book_id']);
        $price = $book->price;
        // Kiểm tra nếu sản phẩm đã có trong giỏ
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('book_id', $validated['book_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id'  => $cart->id,
                'book_id'  => $validated['book_id'],
                'quantity' => $validated['quantity'],
                'price'    => $price
            ]);
        }

        // Cập nhật tổng giá trị giỏ hàng
        $cart->total += $price * $validated['quantity'];
        $cart->save();

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart = $cartItem->cart;
        // Tính lại tổng: trừ số tiền của sản phẩm cũ và cộng số tiền mới
        $oldQuantity = $cartItem->quantity;
        $price = $cartItem->price;
        $cart->total -= $price * $oldQuantity;
        
        $cartItem->quantity = $validated['quantity'];
        $cartItem->save();

        $cart->total += $price * $validated['quantity'];
        $cart->save();

        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được cập nhật.');
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }
        $cart = $cartItem->cart;
        $cart->total -= $cartItem->price * $cartItem->quantity;
        $cart->save();
        $cartItem->delete();
        
        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được loại bỏ khỏi giỏ hàng.');
    }
}
