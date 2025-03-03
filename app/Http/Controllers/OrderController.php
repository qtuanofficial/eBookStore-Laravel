<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng của người dùng hiện tại
    public function index()
    {
        $orders = OrderDetail::with('items.book')
            ->where('user_id', Auth::id())
            ->get();
        return view('orders.index', compact('orders'));
    }

    // Hiển thị giao diện thanh toán (checkout) dựa trên giỏ hàng
    public function create()
    {
        $cart = Cart::with('items.book')->where('user_id', Auth::id())->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }
        return view('orders.create', compact('cart'));
    }

    // Tạo đơn hàng từ giỏ hàng hiện tại
    public function store(Request $request)
    {
        $cart = Cart::with('items')->where('user_id', Auth::id())->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $order = OrderDetail::create([
            'user_id' => Auth::id(),
            'total'   => $cart->total,
            'status'  => 'pending'
        ]);

        // Tạo các mục đơn hàng tương ứng
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id'  => $item->book_id,
                'quantity' => $item->quantity,
                'price'    => $item->price
            ]);
        }

        // Xóa các mục giỏ hàng và đặt lại tổng = 0
        $cart->items()->delete();
        $cart->update(['total' => 0]);

        return redirect()->route('orders.show', $order->id)->with('success', 'Đơn hàng đã được tạo.');
    }

    // Hiển thị chi tiết đơn hàng
    public function show(OrderDetail $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->load('items.book', 'payment');
        return view('orders.show', compact('order'));
    }

    // (Tùy chọn) Hiển thị form chỉnh sửa đơn hàng (ví dụ: hủy đơn hàng)
    public function edit(OrderDetail $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('orders.edit', compact('order'));
    }

    // (Tùy chọn) Cập nhật đơn hàng
    public function update(Request $request, OrderDetail $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|string'
        ]);

        $order->update($validated);
        return redirect()->route('orders.index')->with('success', 'Đơn hàng đã được cập nhật.');
    }

    // Xóa hoặc hủy đơn hàng (nếu được phép)
    public function destroy(OrderDetail $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Đơn hàng đã được hủy.');
    }
}
