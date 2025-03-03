<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Dành cho admin: Liệt kê tất cả người dùng
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Hiển thị thông tin chi tiết của người dùng (cho người dùng tự quản lý)
    public function show(User $user)
    {
        if (Auth::id() !== $user->id) {
            abort(403);
        }
        return view('users.show', compact('user'));
    }

    // Hiển thị form chỉnh sửa thông tin người dùng
    public function edit(User $user)
    {
        if (Auth::id() !== $user->id) {
            abort(403);
        }
        return view('users.edit', compact('user'));
    }

    // Cập nhật thông tin người dùng
    public function update(Request $request, User $user)
    {
        if (Auth::id() !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'username'   => 'required|unique:users,username,' . $user->id,
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'password'   => 'nullable|min:6|confirmed'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return redirect()->route('users.show', $user->id)->with('success', 'Thông tin người dùng đã được cập nhật.');
    }

    // Xóa người dùng (chỉ dùng khi admin quản lý)
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Người dùng đã được xóa.');
    }
}
