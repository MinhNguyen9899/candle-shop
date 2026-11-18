<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        // Kiểm tra đăng nhập
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Kiểm tra trạng thái tài khoản
        if ($user->status !== 'active') {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa.',
                ]);
        }

        // Kiểm tra vai trò
        if (!in_array($user->role, $roles, true)) {
            abort(403, 'Bạn không có quyền truy cập chức năng này.');
        }

        return $next($request);
    }
}
