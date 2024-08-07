@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
    @if(Auth::user()->role === 'admin')
    <div class="flex -mx-2">
        <div class="w-1/3 px-2">
            <!-- Nội dung của cột 1 chia làm 2 row -->
            <div class="flex flex-col h-full">
                <div class="flex-1 bg-gray-200 p-4 mb-2">
                     <!-- Biểu đồ cột -->
                    <canvas id="barChart" width="400" height="200"></canvas>
                </div>
                <div class="flex-1 bg-gray-200 p-4">
                    <!-- Bảng dữ liệu -->
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-300">
                            <tr class="border-b border-gray-300" style="background-color: #D3D4CF">
                                <th class="px-4 py-2 text-left">STT</th>
                                <th class="px-4 py-2 text-left">Tên văn bản</th>
                                <th class="px-4 py-2 text-left">Thời gian thực hiện</th>
                                <th class="px-4 py-2 text-left">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200 bg-blue-100">
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">Văn bản A</td>
                                <td class="px-4 py-2">01/08/2024</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 bg-orange-100">
                                <td class="px-4 py-2">2</td>
                                <td class="px-4 py-2">Văn bản B</td>
                                <td class="px-4 py-2">02/08/2024</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 bg-blue-100">
                                <td class="px-4 py-2">3</td>
                                <td class="px-4 py-2">Văn bản C</td>
                                <td class="px-4 py-2">03/08/2024</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 bg-orange-100">
                                <td class="px-4 py-2">4</td>
                                <td class="px-4 py-2">Văn bản D</td>
                                <td class="px-4 py-2">04/08/2024</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <!-- Thêm các hàng khác nếu cần -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="w-1/3 px-2">
            <!-- Nội dung của cột 2 chia làm 2 row -->
            <div class="flex flex-col h-full">
                <div class="flex-1 bg-gray-200 p-4 mb-2">
                    <canvas id="barChart2" width="400" height="200"></canvas>
                </div>
                <div class="flex-1 bg-gray-200 p-4">
                     <!-- Bảng dữ liệu -->
                     <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-300">
                            <tr class="border-b border-gray-300" style="background-color: #D3D4CF">
                                <th class="px-4 py-2 text-left">STT</th>
                                <th class="px-4 py-2 text-left">Tên văn bản</th>
                                <th class="px-4 py-2 text-left">Thời gian thực hiện</th>
                                <th class="px-4 py-2 text-left">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200 bg-blue-100">
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">Văn bản A</td>
                                <td class="px-4 py-2">01/08/2024</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 bg-orange-100">
                                <td class="px-4 py-2">2</td>
                                <td class="px-4 py-2">Văn bản B</td>
                                <td class="px-4 py-2">02/08/2024</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 bg-blue-100">
                                <td class="px-4 py-2">3</td>
                                <td class="px-4 py-2">Văn bản C</td>
                                <td class="px-4 py-2">03/08/2024</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 bg-orange-100">
                                <td class="px-4 py-2">4</td>
                                <td class="px-4 py-2">Văn bản D</td>
                                <td class="px-4 py-2">04/08/2024</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <!-- Thêm các hàng khác nếu cần -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="w-1/3 px-2">
             <!-- Nội dung của cột 3 chia làm 2 row -->
             <div class="flex flex-col h-full">
                <div class="flex-1 bg-gray-200 p-4 mb-2">
                    <canvas id="barChart3" width="400" height="200"></canvas>
                </div>
                <div class="flex-1 bg-gray-200 p-4">
                     <!-- Bảng dữ liệu -->
                     <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-300">
                            <tr class="border-b border-gray-300" style="background-color: #D3D4CF">
                                <th class="px-4 py-2 text-left">STT</th>
                                <th class="px-4 py-2 text-left">Tên văn bản</th>
                                <th class="px-4 py-2 text-left">Thời gian thực hiện</th>
                                <th class="px-4 py-2 text-left">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200 bg-blue-100">
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">Văn bản A</td>
                                <td class="px-4 py-2">01/08/2024</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 bg-orange-100">
                                <td class="px-4 py-2">2</td>
                                <td class="px-4 py-2">Văn bản B</td>
                                <td class="px-4 py-2">02/08/2024</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 bg-blue-100">
                                <td class="px-4 py-2">3</td>
                                <td class="px-4 py-2">Văn bản C</td>
                                <td class="px-4 py-2">03/08/2024</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 bg-orange-100">
                                <td class="px-4 py-2">4</td>
                                <td class="px-4 py-2">Văn bản D</td>
                                <td class="px-4 py-2">04/08/2024</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <!-- Thêm các hàng khác nếu cần -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @else
    <h1>Trang chủ</h1>
    @endif
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('barChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3'], // Các nhãn tháng
                datasets: [{
                    label: 'Văn bản đã hoàn thành',
                    data: [10, 20, 15], // Dữ liệu cho văn bản đã hoàn thành
                    backgroundColor: 'rgba(0, 123, 255, 0.7)', // Màu xanh đậm hơn và đặc hơn
                    borderColor: 'rgba(0, 123, 255, 1)', // Màu xanh đậm
                    borderWidth: 1
                }, {
                    label: 'Văn bản chưa hoàn thành',
                    data: [5, 15, 10], // Dữ liệu cho văn bản chưa hoàn thành
                    backgroundColor: 'rgba(253, 126, 20, 0.7)', // Màu cam đậm hơn và đặc hơn
                    borderColor: 'rgba(253, 126, 20, 1)', // Màu cam đậm
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom', // Đặt chú thích ở dưới biểu đồ
                        labels: {
                            boxWidth: 20, // Kích thước hộp chú thích
                            padding: 15 // Khoảng cách giữa các chú thích
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            // text: 'Tháng' // Tiêu đề của trục X
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            // text: 'Số lượng' // Tiêu đề của trục Y
                        }
                    }
                }
            }
        });


        var ctx2 = document.getElementById('barChart2').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3'], // Các tháng
                datasets: [
                    {
                        label: 'Chưa hoàn thành',
                        data: [1, 4, 3], // Dữ liệu cho từng tháng
                        backgroundColor: 'rgba(153, 102, 255, 0.7)', // Tím
                        stack: 'stack1'
                    },
                    {
                        label: 'Đang thực hiện - Trong hạn',
                        data: [4, 8, 6],
                        backgroundColor: 'rgba(0, 123, 255, 0.7)', // Xanh da trời
                        stack: 'stack2'
                    },
                    {
                        label: 'Đang thực hiện - Quá hạn',
                        data: [2, 5, 4],
                        backgroundColor: 'rgba(255, 69, 0, 1)', // Đỏ
                        stack: 'stack2'
                    },
                    {
                        label: 'Hoàn thành - Đúng hạn',
                        data: [5, 10, 7],
                        backgroundColor: 'rgba(0, 123, 255, 0.7)', // Xanh
                        stack: 'stack3'
                    },
                    {
                        label: 'Hoàn thành - Quá hạn',
                        data: [3, 7, 5],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Vàng
                        stack: 'stack3'
                    }
                ]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 20,
                            padding: 15
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        title: {
                            display: true,
                            //text: 'Tháng'
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            //text: 'Số lượng'
                        }
                    }
                }
            }
        });

        var ctx3 = document.getElementById('barChart3').getContext('2d');
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3'], // Các tháng
                datasets: [
                    {
                        label: 'Chưa hoàn thành',
                        data: [1, 4, 3], // Dữ liệu cho từng tháng
                        backgroundColor: 'rgba(153, 102, 255, 0.7)', // Tím
                        stack: 'stack1'
                    },
                    {
                        label: 'Đang thực hiện - Trong hạn',
                        data: [4, 8, 6],
                        backgroundColor: 'rgba(0, 123, 255, 0.7)', // Xanh da trời
                        stack: 'stack2'
                    },
                    {
                        label: 'Đang thực hiện - Quá hạn',
                        data: [2, 5, 4],
                        backgroundColor: 'rgba(255, 69, 0, 1)', // Đỏ
                        stack: 'stack2'
                    },
                    {
                        label: 'Hoàn thành - Đúng hạn',
                        data: [5, 10, 7],
                        backgroundColor: 'rgba(0, 123, 255, 0.7)', // Xanh
                        stack: 'stack3'
                    },
                    {
                        label: 'Hoàn thành - Quá hạn',
                        data: [3, 7, 5],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Vàng
                        stack: 'stack3'
                    }
                ]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 20,
                            padding: 15
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        title: {
                            display: true,
                            //text: 'Tháng'
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            //text: 'Số lượng'
                        }
                    }
                }
            }
        });

    });
</script>



@endsection
