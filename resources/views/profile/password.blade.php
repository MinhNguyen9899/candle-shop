<x-app-layout>
    <div style="background: #f8f5f0; min-height: 85vh; padding: 45px 20px;">

        <div style="max-width: 850px; margin: auto;">

            {{-- Tiêu đề --}}
            <div style="margin-bottom: 30px;">
                <p style="font-size: 12px; letter-spacing: 2px; color: #a18a72; font-weight: 700;">
                    ACCOUNT SECURITY
                </p>

                <h1 style="font-size: 30px; font-weight: 700; color: #493528;">
                    Đổi mật khẩu
                </h1>

                <p style="color: #89796d; margin-top: 8px;">
                    Cập nhật mật khẩu để bảo vệ tài khoản của bạn.
                </p>
            </div>

            {{-- Form đổi mật khẩu --}}
            <div style="background: white; padding: 30px; border-radius: 14px; box-shadow: 0 5px 20px rgba(70,50,30,0.06);">

                @if (session('status') === 'password-updated')
                    <div style="background: #e8f5e9; color: #256b35; padding: 13px 16px; border-radius: 8px; margin-bottom: 22px;">
                        Đổi mật khẩu thành công!
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    {{-- Mật khẩu hiện tại --}}
                    <div style="margin-bottom: 22px;">
                        <label class="block font-semibold mb-2" style="color: #493528;">
                            Mật khẩu hiện tại
                        </label>

                        <input
                            type="password"
                            name="current_password"
                            autocomplete="current-password"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm"
                        >

                        @error('current_password', 'updatePassword')
                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Mật khẩu mới --}}
                    <div style="margin-bottom: 22px;">
                        <label class="block font-semibold mb-2" style="color: #493528;">
                            Mật khẩu mới
                        </label>

                        <input
                            type="password"
                            name="password"
                            autocomplete="new-password"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm"
                        >

                        @error('password', 'updatePassword')
                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Xác nhận mật khẩu --}}
                    <div style="margin-bottom: 28px;">
                        <label class="block font-semibold mb-2" style="color: #493528;">
                            Xác nhận mật khẩu mới
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            autocomplete="new-password"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm"
                        >
                    </div>

                    {{-- Nút thao tác --}}
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">

                        <button
                            type="submit"
                            style="background: #654832; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 600;"
                        >
                            Cập nhật mật khẩu
                        </button>

                        <a
                            href="{{ route('profile.edit') }}"
                            style="background: #eee7df; color: #654832; padding: 12px 24px; border-radius: 8px; font-weight: 600;"
                        >
                            Quay lại hồ sơ
                        </a>

                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
