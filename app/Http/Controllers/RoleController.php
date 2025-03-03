<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    // Hiển thị danh sách vai trò
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    // Hiển thị form tạo vai trò mới
    public function create()
    {
        return view('roles.create');
    }

    // Lưu vai trò mới vào CSDL
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|unique:roles,name',
            'description' => 'nullable'
        ]);

        Role::create($validated);
        return redirect()->route('roles.index')->with('success', 'Vai trò đã được tạo thành công.');
    }

    // Hiển thị chi tiết vai trò
    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    // Hiển thị form chỉnh sửa vai trò
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    // Cập nhật vai trò
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name'        => 'required|unique:roles,name,' . $role->id,
            'description' => 'nullable'
        ]);

        $role->update($validated);
        return redirect()->route('roles.index')->with('success', 'Vai trò đã được cập nhật.');
    }

    // Xóa vai trò
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Vai trò đã được xóa.');
    }
}
