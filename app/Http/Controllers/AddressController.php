<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    // Hiển thị danh sách địa chỉ của người dùng hiện tại
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return view('addresses.index', compact('addresses'));
    }

    // Hiển thị form tạo địa chỉ mới
    public function create()
    {
        return view('addresses.create');
    }

    // Lưu địa chỉ mới vào CSDL
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required',
            'address_line_1'  => 'required',
            'address_line_2'  => 'nullable',
            'country'         => 'required',
            'city'            => 'required',
            'postal_code'     => 'required',
            'landmark'        => 'nullable',
            'phone_number'    => 'required'
        ]);

        // Gán user_id từ người dùng đang đăng nhập
        $validated['user_id'] = Auth::id();
        Address::create($validated);
        return redirect()->route('addresses.index')->with('success', 'Địa chỉ đã được tạo thành công.');
    }

    // Hiển thị chi tiết địa chỉ
    public function show(Address $address)
    {
        // Kiểm tra quyền sở hữu
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        return view('addresses.show', compact('address'));
    }

    // Hiển thị form chỉnh sửa địa chỉ
    public function edit(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        return view('addresses.edit', compact('address'));
    }

    // Cập nhật địa chỉ
    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'           => 'required',
            'address_line_1'  => 'required',
            'address_line_2'  => 'nullable',
            'country'         => 'required',
            'city'            => 'required',
            'postal_code'     => 'required',
            'landmark'        => 'nullable',
            'phone_number'    => 'required'
        ]);

        $address->update($validated);
        return redirect()->route('addresses.index')->with('success', 'Địa chỉ đã được cập nhật thành công.');
    }

    // Xóa địa chỉ
    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        $address->delete();
        return redirect()->route('addresses.index')->with('success', 'Địa chỉ đã được xóa.');
    }
}
