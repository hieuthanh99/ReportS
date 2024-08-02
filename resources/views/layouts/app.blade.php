<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            #success-message {
                position: fixed;
                top: 1rem; /* Khoảng cách từ cạnh trên */
                right: 1rem; /* Khoảng cách từ cạnh phải */
                padding: 1rem;
                border-radius: 0.375rem;
                background-color: #48bb78; /* Màu xanh lá cây */
                color: #ffffff;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 1000; /* Đảm bảo thông báo nằm trên cùng */
                opacity: 1;
                transition: opacity 0.5s ease, transform 0.5s ease; /* Hiệu ứng mờ dần và dịch chuyển */
            }

            #success-message button {
                position: absolute;
                top: 0.5rem;
                right: 0.2rem;
                background: none;
                border: none;
                cursor: pointer;
                font-size: 1rem;
            }

            #success-message button i {
                font-size: 1.25rem;
            }

        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('breadcrumbs')

                @yield('content')
            </main>
            <script>
               document.addEventListener('DOMContentLoaded', (event) => {
                    const messageElement = document.getElementById('success-message');
                    if (messageElement) {
                        // Tự động ẩn thông báo sau 5 giây
                        setTimeout(() => {
                            messageElement.style.opacity = '0';
                            messageElement.style.transform = 'translateY(-20px)'; // Di chuyển thông báo lên khi ẩn
                            setTimeout(() => {
                                messageElement.style.display = 'none';
                            }, 500); // Thời gian để chuyển dần biến mất
                        }, 5000); // Thời gian hiển thị thông báo

                        // Xử lý nút đóng thông báo
                        const closeButton = document.getElementById('close-message');
                        if (closeButton) {
                            closeButton.addEventListener('click', () => {
                                messageElement.style.opacity = '0';
                                messageElement.style.transform = 'translateY(-20px)'; // Di chuyển thông báo lên khi đóng
                                setTimeout(() => {
                                    messageElement.style.display = 'none';
                                }, 500); // Thời gian để chuyển dần biến mất
                            });
                        }
                    }
                });

            </script>
        </div>
    </body>
</html>
