@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
        @if ($errors->any())
            <div class="error-message bg-red-500 text-white p-4 rounded-lg mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="error-message bg-red-500 text-white p-4 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="success-message bg-green-500 text-white p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'supper_admin')
            <div class="flex -mx-2">

                <div class="w-1/2 px-2">
                    <!-- Nội dung của cột 2 chia làm 2 row -->
                    <div class="flex flex-col h-full">
                        <div class="flex-1 bg-gray-200 p-4 mb-2">
                            <canvas id="taskChart" width="200" height="200"></canvas>
                            <div class="task-link" style="text-align: center">
                                <a style="color: blue; text-align: center" href="">Xem chi tiết</a>
                            </div>
                        </div>
                        <div class="flex-1 bg-gray-200 p-4">
                            <!-- Bảng dữ liệu -->
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-300">
                                    <tr class="border-b border-gray-300" style="background-color: #D3D4CF">
                                        <th class="px-4 py-2 text-left">STT</th>
                                        <th class="px-4 py-2 text-left">Tên nhiệm vụ</th>
                                        <th class="px-4 py-2 text-left">Trạng thái báo cáo</th>
                                        <th class="px-4 py-2 text-left">Phê duyệt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tableTask as $index => $task)
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
                                            <td class="px-4 py-2"></td>
                                            <td class="px-4 py-2">{{ $task->name }}</td>
                                            <td class="px-4 py-2">{{ $task->getStatusTaskTarget() }}</td>
                                            {{-- <td class="px-4 py-2 text-center">
                                                <a onclick="window.location.href='{{ route('tasks.show-details', ['code' => $task->code, 'type' => $task->type]) }}'"
                                                    class="text-blue-500 hover:text-blue-700">
                                                    <i class="fas fa-chevron-right"></i>
                                                </a>

                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $tableTask->links() }} <!-- Render pagination links -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-1/2 px-2">
                    <!-- Nội dung của cột 3 chia làm 2 row -->
                    <div class="flex flex-col h-full">
                        <div class="flex-1 bg-gray-200 p-4 mb-2">
                            <canvas id="targetChart" width="400" height="400"></canvas>
                            <div class="task-link" style="text-align: center">
                                <a style="color: blue; text-align: center" href="">Xem chi tiết</a>
                            </div>
                        </div>
                        <div class="flex-1 bg-gray-200 p-4">
                            <!-- Bảng dữ liệu -->
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-300">
                                    <tr class="border-b border-gray-300" style="background-color: #D3D4CF">
                                        <th class="px-4 py-2 text-left">STT</th>
                                        <th class="px-4 py-2 text-left">Tên chỉ tiêu</th>
                                        <th class="px-4 py-2 text-left">Trạng thái báo cáo</th>
                                        <th class="px-4 py-2 text-left">Phê duyệt</th>
                                    </tr>
                                </thead>
                                {{-- <tbody>
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
                        </tbody> --}}
                            </table>
                            {{-- <div class="mt-4">
                        {{ $targetsTable->links() }} <!-- Render pagination links -->
                    </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="flex -mx-2">
                <div class="">
                    <div class=" bg-gray-200 p-4 mb-2">

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
                            {{-- <tbody>
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
                    </tbody> --}}
                        </table>
                        {{-- <div class="mt-4">
                    {{ $staffTable->links() }} <!-- Render pagination links -->
                </div> --}}
                    </div>
                </div>
            </div>
        @endif
    </div>
    <script>
        // Biểu đồ Task
        var ctxTask = document.getElementById('taskChart').getContext('2d');
        var taskChart = new Chart(ctxTask, {
            type: 'pie',
            data: {
                labels: ['Quá hạn', 'Sắp tới hạn', 'Đang thực hiện', 'Hoàn thành đúng hạn', 'Hoàn thành quá hạn'],
                datasets: [{
                    label: 'Trạng thái Task',
                    data: [
                        {{ $taskStatus['overdue'] }},
                        {{ $taskStatus['upcoming'] }},
                        {{ $taskStatus['inProgress'] }},
                        {{ $taskStatus['completedOnTime'] }},
                        {{ $taskStatus['completedLate'] }}
                    ],
                    backgroundColor: ['red', 'yellow', 'green', 'blue', 'purple'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Biểu đồ trạng thái nhiệm vụ'
                    }
                }
            }
        });

        // Biểu đồ Target
        var ctxTarget = document.getElementById('targetChart').getContext('2d');
        var targetChart = new Chart(ctxTarget, {
            type: 'pie',
            data: {
                labels: ['Quá hạn', 'Sắp tới hạn', 'Đang thực hiện', 'Hoàn thành đúng hạn', 'Hoàn thành quá hạn'],
                datasets: [{
                    label: 'Trạng thái Target',
                    data: [
                        {{ $targetStatus['overdue'] }},
                        {{ $targetStatus['upcoming'] }},
                        {{ $targetStatus['inProgress'] }},
                        {{ $targetStatus['completedOnTime'] }},
                        {{ $targetStatus['completedLate'] }}
                    ],
                    backgroundColor: ['red', 'yellow', 'green', 'blue', 'purple'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Biểu đồ trạng thái chỉ tiêu'
                    }
                }
            }
        });
    </script>


@endsection
