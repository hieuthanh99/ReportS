<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Nền tảng quản lý nhiệm vụ Ủy ban quốc gia về chuyển đổi số</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .header-title h3 {
                font-family: 'Roboto', sans-serif;
            }
            .header-title {
                width: 100%;
                text-align: center;
                padding: 20px;
                color: #222;
                margin: 20px 0;
            }

            .header-title h3 {
                font-size: 18px;
                margin: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                letter-spacing: 1px;
                text-transform: uppercase;
                font-weight: bold;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <div class="flex justify-center mb-4" style="flex-direction:  column;">
                    <a href="/" style="text-align: center">
                        <img src="{{ asset('logo/logo2.png') }}" alt="Logo" class="w-auto" style="max-height: 150px; margin: 0 auto">
                    </a>
                    <div class="flex-shrink-0 px-4 header-title">
                        <h3>Nền tảng quản lý nhiệm vụ Ủy ban quốc gia về chuyển đổi số</h3>
                    </div>
                </div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>