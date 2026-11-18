
<x-app-layout>
    <style>
        .account-page {
            min-height: 85vh;
            background: #F7F2EA;
            padding: 45px 22px 70px;
            font-family: 'DM Sans', sans-serif;
        }

        .account-container {
            max-width: 900px;
            margin: auto;
        }

        .account-heading {
            margin-bottom: 28px;
        }

        .account-eyebrow {
            color: #A1846B;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            margin-bottom: 8px;
        }

        .account-heading h1 {
            color: #30241E;
            font-size: 29px;
            font-weight: 700;
        }

        .account-heading p {
            color: #887B70;
            font-size: 13px;
            margin-top: 8px;
        }

        .account-card {
            background: #FFFFFF;
            border: 1px solid #E8DED2;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(48, 36, 30, .04);
        }

        .account-card-title {
            color: #30241E;
            font-size: 18px;
            font-weight: 700;
            padding-bottom: 18px;
            margin-bottom: 24px;
            border-bottom: 1px solid #E8DED2;
        }

        .account-field {
            margin-bottom: 22px;
        }

        .account-field label {
            display: block;
            color: #49382C;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 9px;
        }

        .account-field input,
        .account-field textarea {
            width: 100%;
            border: 1px solid #E5DBD0;
            border-radius: 8px;
            padding: 12px 14px;
            color: #49382C;
            background: #FFFEFC;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            outline: none;
            transition: .2s;
        }

        .account-field input:focus,
        .account-field textarea:focus {
            border-color: #A1846B;
            box-shadow: 0 0 0 3px rgba(161, 132, 107, .10);
        }

        .account-field textarea {
            resize: vertical;
            line-height: 1.7;
        }

        .account-field input::placeholder,
        .account-field textarea::placeholder {
            color: #B4A79B;
        }

        .account-field .error-message {
            color: #B5473C;
            font-size: 12px;
            margin-top: 6px;
        }

        .account-hint {
            color: #887B70;
            font-size: 12px;
            margin-top: 9px;
            line-height: 1.6;
        }

        .account-success {
            background: #EDF7EE;
            border: 1px solid #D7E9D9;
            color: #39784A;
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 22px;
            font-size: 13px;
        }

        .account-save-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #654832;
            color: #FFFFFF;
            border: none;
            border-radius: 8px;
            padding: 13px 26px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .4px;
            cursor: pointer;
            transition: .2s;
        }

        .account-save-btn:hover {
            background: #493528;
            transform: translateY(-1px);
        }

        @media (max-width: 600px) {
            .account-page {
                padding: 30px 14px 45px;
            }

            .account-heading h1 {
                font-size: 25px;
            }

            .account-card {
                padding: 20px;
            }
        }
    </style>

    <div class="account-page">
        <div class="account-container">

            {{-- Tiêu đề --}}
            <div class="account-heading">
                <p class="account-eyebrow">MY ACCOUNT</p>
                <h1>Thông tin tài khoản</h1>
                <p>Quản lý thông tin cá nhân và địa chỉ nhận hàng của bạn.</p>
            </div>

            {{-- Thông tin cá nhân --}}
            <div class="account-card">
                <h2 class="account-card-title">Thông tin cá nhân</h2>

                @if (session('status') === 'profile-updated')
                    <div class="account-success">
                        Cập nhật thông tin thành công!
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    {{-- Họ và tên --}}
                    <div class="account-field">
                        <label for="name">Họ và tên</label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Nhập họ và tên"
                            required
                        >

                        @error('name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="account-field">
                        <label for="email">Email</label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            placeholder="Nhập địa chỉ email"
                            required
                        >

                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Số điện thoại --}}
                    <div class="account-field">
                        <label for="phone">Số điện thoại</label>

                        <input
                            id="phone"
                            type="text"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            placeholder="Nhập số điện thoại"
                        >

                        @error('phone')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Địa chỉ mặc định --}}
                    @if ($user->role === 'customer')
                        <div class="account-field">
                            <label for="default_shipping_address">
                                Địa chỉ nhận hàng mặc định
                            </label>

                            <textarea
                                id="default_shipping_address"
                                name="default_shipping_address"
                                rows="3"
                                placeholder="Nhập số nhà, tên đường, phường/xã, tỉnh/thành phố..."
                            >{{ old('default_shipping_address', $user->default_shipping_address) }}</textarea>

                            @error('default_shipping_address')
                                <p class="error-message">{{ $message }}</p>
                            @enderror

                            <p class="account-hint">
                                Địa chỉ này sẽ được sử dụng làm địa chỉ nhận hàng mặc định khi đặt hàng.
                            </p>
                        </div>
                    @endif

                    <button type="submit" class="account-save-btn">
                        LƯU THAY ĐỔI
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
