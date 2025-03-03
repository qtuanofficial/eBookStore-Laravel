<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Xử lý request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Giả sử User có quan hệ roles() và role "admin" được đặt tên là 'admin'
        if (Auth::check() && Auth::user()->roles->contains('name', 'admin')) {
            return $next($request);
        }

        return redirect()->route('dashboard')
            ->with('error', 'Bạn không có quyền truy cập vào trang này.');
    }
}
