<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckOrganization
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Kiểm tra organization_id
        if (!$user || !$user->organization_id) {
            // Nếu không có, chuyển hướng kèm thông báo lỗi
            return redirect()->route('dashboard')->with('error', 'Bạn không có quyền truy cập do không thuộc tổ chức nào.');
        }

        return $next($request);
    }
}
