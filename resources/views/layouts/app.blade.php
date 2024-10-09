<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Nền tảng quản lý nhiệm vụ Ủy ban quốc gia về chuyển đổi số</title>
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">

        <!-- Thêm vào phần cuối của <body> hoặc trước thẻ </body> -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
        <script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css','resources/js/app.js'])
        <style>
 .select2-container--default .select2-selection--single {
        border: 1px solid #D1D5DB; /* Border gray-300 */
        border-radius: 0.5rem; /* Rounded-lg */
        height: 42px; /* Chiều cao */
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px; /* Giữ cho nội dung nằm giữa */
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px; /* Chiều cao của mũi tên dropdown */
        right: 10px; /* Căn chỉnh mũi tên */
    }

    /* Tùy chỉnh mũi tên dropdown */
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #555 transparent; /* Mũi tên màu xám */
    }
            input[readonly] {
    background-color: #e9ecef; /* Màu nền giống như input disabled */
    color: #6c757d;            /* Màu chữ nhạt hơn */
    border-color: #ced4da;      /* Màu viền nhạt hơn */
    cursor: not-allowed;        /* Đổi con trỏ thành biểu tượng cấm */
    pointer-events: none;       /* Ngăn tương tác người dùng */
}
.menu-tree {
    --spacing: 1.5rem;
    --radius: 10px;
}

.menu-tree li {
    display: block;
    position: relative;
    padding-left: calc(2 * var(--spacing) - var(--radius) - 2px);
}

.menu-tree li summary {
    line-height: 35px;
}

/*.menu-tree li a {*/
/*    line-height: 40px;*/
/*}*/

.menu-tree ul {
    display: block;
    margin-left: calc(var(--radius) - var(--spacing));
    padding-left: 10px;
    position: initial;
    min-width: auto;
}

.menu-tree ul li {
    border-left: 2px solid #ddd;
}

.menu-tree ul li:last-child {
    border-color: transparent;
}
.w-1\/3 {
    width: 32.333333%;
}

            /* public/css/app.css */
            .breadcrumb {
                padding: 0;
                margin: 0 0 10px 0;
                list-style: none;
                display: flex;
                flex-wrap: wrap;
            }

            .breadcrumb li {
                margin: 0;
            }

            .breadcrumb li + li::before {
                content: ">";
                margin: 0 0.5em;
                color: #6c757d;
            }

            .breadcrumb a {
                text-decoration: none;
                color: #007bff;
            }

            .breadcrumb a:hover {
                text-decoration: underline;
            }

            .breadcrumb li.active {
                color: #6c757d;
            }

            .button-approved, .button-reject{
                width: 75px;
            }
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
            nav{
                margin: 5px 10px;
            }
            nav{
                border-radius: 5px;
            }
            nav ul{
                display: flex;
            }
            nav> ul li{
                list-style: none; /* bỏ dấu chấm mặc định của li */
            }
            nav> ul li a{
                cursor: pointer;
                display: block;
                padding: 0 25px;
                line-height: 50px;
                color: #222;
                text-decoration: none;
            }
            nav ul li:first-child {
                border-left: none; 
            }
            nav> ul li:first-child a{
                cursor: pointer;
                border-bottom-left-radius: 5px;
                border-top-left-radius: 5px;
            }
            /* Khi hover đến li, tô màu cho thẻ a */
            nav ul li:hover>a{
                /* background: red; */
                opacity: .7;
                color: #2c2929;
                cursor: pointer;
            }
            /*menu con*/
            /*Ẩn các menu con cấp 1,2,3*/
            nav li ul{
                display: none;
                min-width: 350px;
                position: absolute;
            }
            nav li>ul li{
                width: 100%;
                border: none;
                border-bottom: 1px solid #ccc;
                background: #dfdcdc;
                cursor: pointer;
                text-align: left;
            }
            nav li>ul li:first-child a{
                border-bottom-left-radius: 0px;
                border-top-left-radius: 0px;
            }
            nav li>ul li:last-child {
                border-bottom-left-radius: 5px;
                border-bottom-right-radius: 5px;
            }
            nav li>ul li:last-child a{
                border-bottom-left-radius: 5px;
                border-bottom-right-radius: 5px;
            }
            /*khi hover thì hiện menu con*/
            nav  li:hover > ul{
                display:  block;
            }
            /*Hiển thị menu con cấp 2,3,4 bên cạnh phải*/
            nav > ul li > ul li >ul{
                margin-left: 352px;
                margin-top: -50px;
            }
            .hover\:text-gray-700:hover {
                width: 100%;
            }
                        /* CSS cho dropdown */
            .group:hover .dropdown-menu,
            .dropdown-menu:hover {
                visibility: visible;
                opacity: 1;
            }

            .dropdown-menu {
                visibility: hidden;
                opacity: 0;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }

            input:disabled {
                background-color: #f0f0f0;
                cursor: not-allowed;
            }
            input:readonly {
                background-color: #f9f9f9;
                cursor: default;
            }
           #loading {
                display: none !important; /* Đảm bảo rằng phần tử này không hiển thị khi trang tải */
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            }

            #loading .fa-spinner {
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
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
            .ease-in-out {
                width: 100%;
            }
            @media (max-width: 768px) {
                .header-title h3 {
                    display: none;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <div id="loading" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-spinner fa-spin text-white text-2xl"></i>
                    <div class="text-white">Đang xử lý...</div>
                </div>
            </div>
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




                    const warningMessageElement = document.getElementById('warning-message');
                    if (warningMessageElement) {
                        // Tự động ẩn thông báo sau 5 giây
                        setTimeout(() => {
                            warningMessageElement.style.opacity = '0';
                            warningMessageElement.style.transform = 'translateY(-20px)'; // Di chuyển thông báo lên khi ẩn
                            setTimeout(() => {
                                warningMessageElement.style.display = 'none';
                            }, 500); // Thời gian để chuyển dần biến mất
                        }, 5000); // Thời gian hiển thị thông báo

                        // Xử lý nút đóng thông báo
                        const closeButton = document.getElementById('close-message');
                        if (closeButton) {
                            closeButton.addEventListener('click', () => {
                                warningMessageElement.style.opacity = '0';
                                warningMessageElement.style.transform = 'translateY(-20px)'; // Di chuyển thông báo lên khi đóng
                                setTimeout(() => {
                                    warningMessageElement.style.display = 'none';
                                }, 500); // Thời gian để chuyển dần biến mất
                            });
                        }
                    }
                });
                document.addEventListener('DOMContentLoaded', function() {
                    // Chọn tất cả các thông báo
                    const messages = document.querySelectorAll('.error-message, .success-message');
                    
                    messages.forEach(message => {
                        // Kiểm tra xem thông báo có hiển thị không
                        if (message.style.display !== 'none') {
                            // Đặt timeout để ẩn thông báo sau 5 giây
                            setTimeout(() => {
                                message.style.opacity = '0';
                                message.style.transition = 'opacity 0.5s ease-out';
                                setTimeout(() => {
                                    message.style.display = 'none';
                                }, 500); // Thời gian trễ cho hiệu ứng chuyển tiếp
                            }, 5000); // 5 giây
                        }
                    });
                });
                $(document).ready(function() {
            $('.select2').select2({
                allowClear: true
            });
        });
            </script>
        </div>
    </body>
</html>
