<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Xác thực tài khoản
        $request->authenticate();

        // Tạo lại session
        $request->session()->regenerate();

        // Lấy đường dẫn quay lại
        $redirect = $request->input('redirect');

        // Chỉ cho phép chuyển hướng đến đường dẫn nội bộ
        if (
            is_string($redirect)
            && str_starts_with($redirect, '/')
            && !str_starts_with($redirect, '//')
            && !str_contains($redirect, '\\')
        ) {
            return redirect()->to($redirect);
        }

        // Mặc định chuyển đến dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
