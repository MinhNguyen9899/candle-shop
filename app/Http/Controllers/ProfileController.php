<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // Hiển thị thông tin cá nhân và địa chỉ nhận hàng
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    // Cập nhật thông tin cá nhân
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user->id),
            ],

            // Địa chỉ nhận hàng mặc định
            'default_shipping_address' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    // Hiển thị trang đổi mật khẩu riêng
    public function editPassword(): View
    {
        return view('profile.password');
    }
}
