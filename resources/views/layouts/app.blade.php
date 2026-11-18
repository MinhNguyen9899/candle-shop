
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Candle Shop | Nến thơm và quà tặng</title>

    <meta name="description" content="Candle Shop - Khám phá bộ sưu tập nến thơm, nến trang trí và những món quà tinh tế dành cho bạn.">

    {{-- Biểu tượng website --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    {{-- Tải font chữ Luxury --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- CSS và JavaScript --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .candle-page {
            min-height: 100vh;
            background: var(--candle-ivory);
        }

        .candle-header {
            background: var(--candle-white);
            border-bottom: 1px solid var(--candle-border);
        }

        main {
            min-height: calc(100vh - 70px);
        }
    </style>
</head>

<body class="antialiased">
    <div class="candle-page">

        {{-- Thanh điều hướng --}}
        @include('layouts.navigation')

        {{-- Tiêu đề trang nếu có --}}
        @isset($header)
            <header class="candle-header">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Nội dung chính --}}
        <main>
            {{ $slot }}
        </main>

    </div>
</body>
</html>
