<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentDetail;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    // Hiển thị danh sách thông tin thanh toán (thường dùng cho admin)
    public function index()
    {
        $payments = PaymentDetail::with('order')->get();
        return view('payments.index', compact('payments'));
    }

    // Hiển thị form thanh toán cho đơn hàng
    public function create(OrderDetail $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('payments.create', compact('order'));
    }

    // Lưu thông tin thanh toán
    public function store(Request $request, OrderDetail $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'amount'   => 'required|numeric',
            'provider' => 'required|string',
            'status'   => 'required|string'
        ]);

        $validated['order_id'] = $order->id;
        PaymentDetail::create($validated);
        return redirect()->route('orders.show', $order->id)->with('success', 'Thanh toán đã được xử lý.');
    }

    // Hiển thị chi tiết thông tin thanh toán
    public function show(PaymentDetail $payment)
    {
        // Kiểm tra xem đơn hàng của payment có thuộc về người dùng hiện tại không
        if ($payment->order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('payments.show', compact('payment'));
    }
}
