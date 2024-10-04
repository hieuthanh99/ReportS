@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">

    @if(Auth::user()->role !== 'staff')
    <div class="flex -mx-2">

        <div class="w-1/2 px-2">
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
                            @foreach ($tasksTable as $index => $task)
                            @php
                            // Xác định màu sắc dựa trên status_code
                            $bgColor = match ($task->status_code) {
                                'not_completed' => 'rgba(153, 102, 255, 0.3)', // Tím nhạt
                                'in_progress_in_time' => 'rgba(0, 123, 255, 0.3)', // Xanh da trời nhạt
                                'in_progress_overdue' => 'rgba(255, 69, 0, 0.3)', // Đỏ nhạt
                                'completed_in_time' => 'rgba(0, 123, 255, 0.3)', // Xanh nhạt
                                'completed_overdue' => 'rgba(255, 193, 7, 0.3)', // Vàng nhạt
                                default => 'rgba(255, 255, 255, 1)', // Trắng
                            };
                        @endphp
                                <tr class="border-b border-gray-200" style="background-color: {{ $bgColor }}">
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $task->name }}</td>
                                    <td class="px-4 py-2">{{ $task->getDateFromToTextAttribute() }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <a  onclick="window.location.href='{{ route('tasks.show-details', ['code' => $task->code, 'type' => $task->type]) }}'" class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                      
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $tasksTable->links() }} <!-- Render pagination links -->
                    </div>
                </div>
            </div>
        </div>
        <div class="w-1/2 px-2">
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
                            @foreach ($targetsTable as $index => $task)
                            @php
                            // Xác định màu sắc dựa trên status_code
                            $bgColor = match ($task->status_code) {
                                'not_completed' => 'rgba(153, 102, 255, 0.3)', // Tím nhạt
                                'in_progress_in_time' => 'rgba(0, 123, 255, 0.3)', // Xanh da trời nhạt
                                'in_progress_overdue' => 'rgba(255, 69, 0, 0.3)', // Đỏ nhạt
                                'completed_in_time' => 'rgba(0, 123, 255, 0.3)', // Xanh nhạt
                                'completed_overdue' => 'rgba(255, 193, 7, 0.3)', // Vàng nhạt
                                default => 'rgba(255, 255, 255, 1)', // Trắng
                            };
                        @endphp
                                <tr class="border-b border-gray-200" style="background-color: {{ $bgColor }}">
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $task->name }}</td>
                                    <td class="px-4 py-2">{{ $task->getDateFromToTextAttribute() }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <a  onclick="window.location.href='{{ route('tasks.show-details', ['code' => $task->code, 'type' => $task->type]) }}'" class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $targetsTable->links() }} <!-- Render pagination links -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="flex -mx-2">
        <div class="">
            <div class=" bg-gray-200 p-4 mb-2">
                <canvas id="barChart4" width="400" height="200"></canvas>
            </div>
            <div class="bg-gray-200 p-4">
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
                        @foreach ($staffTable as $index => $task)
                        @php
                        // Xác định màu sắc dựa trên status_code
                        $bgColor = match ($task->status_code) {
                            'not_completed' => 'rgba(153, 102, 255, 0.3)', // Tím nhạt
                            'in_progress_in_time' => 'rgba(0, 123, 255, 0.3)', // Xanh da trời nhạt
                            'in_progress_overdue' => 'rgba(255, 69, 0, 0.3)', // Đỏ nhạt
                            'completed_in_time' => 'rgba(0, 123, 255, 0.3)', // Xanh nhạt
                            'completed_overdue' => 'rgba(255, 193, 7, 0.3)', // Vàng nhạt
                            default => 'rgba(255, 255, 255, 1)', // Trắng
                        };
                    @endphp
                            <tr class="border-b border-gray-200" style="background-color: {{ $bgColor }}">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $task->name }}</td>
                                <td class="px-4 py-2">{{ $task->getDateFromToTextAttribute() }}</td>
                                <td class="px-4 py-2 text-center">
                                    <a  onclick="window.location.href='{{ route('tasks.show-details', ['code' => $task->code, 'type' => $task->type]) }}'" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                  
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $staffTable->links() }} <!-- Render pagination links -->
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    
        var chartData = {!! $chartDataJson !!};
        var chartDataTargetJson = {!! $chartDataTargetJson !!};
        var chartDataStaff= {!! $chartDataStaffJson !!};

        var ctxStaff = document.getElementById('barChart4');
        new Chart(ctxStaff, {
            type: 'bar',
            data: {
                labels: 
                chartDataStaff.months, // Các tháng
                datasets: [
                    {
                        label: 'Chưa hoàn thành',
                        data: 
                        chartDataStaff.not_completed, // Dữ liệu cho từng tháng
                        backgroundColor: 'rgba(153, 102, 255, 0.7)', // Tím
                        stack: 'stack1'
                    },
                    {
                        label: 'Đang thực hiện - Trong hạn',
                        data: 
                        chartDataStaff.in_progress_in_time,
                        backgroundColor: 'rgba(0, 123, 255, 0.7)', // Xanh da trời
                        stack: 'stack2'
                    },
                    {
                        label: 'Đang thực hiện - Quá hạn',
                        data: 
                        chartDataStaff.in_progress_overdue,
                        backgroundColor: 'rgba(255, 69, 0, 1)', // Đỏ
                        stack: 'stack2'
                    },
                    {
                        label: 'Hoàn thành - Đúng hạn',
                        data: 
                        chartDataStaff.completed_in_time,
                        backgroundColor: 'rgba(0, 123, 255, 0.7)', // Xanh
                        stack: 'stack3'
                    },
                    {
                        label: 'Hoàn thành - Quá hạn',
                        data: 
                        chartDataStaff.completed_overdue,
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
                            text: 'Tổng hợp'
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số lượng'
                        }
                    }
                }
            }
        });
            
       
// Cập nhật biểu đồ
var ctx2 = document.getElementById('barChart2').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: chartData.months, // Các tháng
        datasets: [
            {
                label: 'Chưa hoàn thành',
                data: chartData.not_completed, // Dữ liệu cho từng tháng
                backgroundColor: 'rgba(153, 102, 255, 0.7)', // Tím
                stack: 'stack1'
            },
            {
                label: 'Đang thực hiện - Trong hạn',
                data: chartData.in_progress_in_time,
                backgroundColor: 'rgba(0, 123, 255, 0.7)', // Xanh da trời
                stack: 'stack2'
            },
            {
                label: 'Đang thực hiện - Quá hạn',
                data: chartData.in_progress_overdue,
                backgroundColor: 'rgba(255, 69, 0, 1)', // Đỏ
                stack: 'stack2'
            },
            {
                label: 'Hoàn thành - Đúng hạn',
                data: chartData.completed_in_time,
                backgroundColor: 'rgba(0, 123, 255, 0.7)', // Xanh
                stack: 'stack3'
            },
            {
                label: 'Hoàn thành - Quá hạn',
                data: chartData.completed_overdue,
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
                    text: 'Nhiệm vụ'
                }
            },
            y: {
                stacked: true,
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Số lượng'
                }
            }
        }
    }
});


var ctx3 = document.getElementById('barChart3').getContext('2d');
new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: chartDataTargetJson.months, // Các tháng
        datasets: [
            {
                label: 'Chưa hoàn thành',
                data: chartDataTargetJson.not_completed, // Dữ liệu cho từng tháng
                backgroundColor: 'rgba(153, 102, 255, 0.7)', // Tím
                stack: 'stack1'
            },
            {
                label: 'Đang thực hiện - Trong hạn',
                data: chartDataTargetJson.in_progress_in_time,
                backgroundColor: 'rgba(0, 123, 255, 0.7)', // Xanh da trời
                stack: 'stack2'
            },
            {
                label: 'Đang thực hiện - Quá hạn',
                data: chartDataTargetJson.in_progress_overdue,
                backgroundColor: 'rgba(255, 69, 0, 1)', // Đỏ
                stack: 'stack2'
            },
            {
                label: 'Hoàn thành - Đúng hạn',
                data: chartDataTargetJson.completed_in_time,
                backgroundColor: 'rgba(0, 123, 255, 0.7)', // Xanh
                stack: 'stack3'
            },
            {
                label: 'Hoàn thành - Quá hạn',
                data: chartDataTargetJson.completed_overdue,
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
                    text: 'Chỉ tiêu'
                }
            },
            y: {
                stacked: true,
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Số lượng'
                }
            }
        }
    }
});

    });
</script>



@endsection
