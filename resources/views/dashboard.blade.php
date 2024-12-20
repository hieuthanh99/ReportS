@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
        @if ($errors->any())
            <div class="error-message bg-yellow-300 text-white p-4 rounded-lg mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="error-message bg-yellow-300 text-white p-4 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="success-message bg-green-500 text-white p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        {{-- @if (Auth::user()->role === 'admin' || Auth::user()->role === 'supper_admin') --}}
            <div class="flex -mx-2">

                <div class="w-1/2 px-2">
                    <!-- Nội dung của cột 2 chia làm 2 row -->
                    <div class="flex flex-col">
                        <div class="flex-1 bg-gray-200 p-4 mb-2">
                            <canvas id="taskChart" style=" max-width: 600px; max-height: 600px; margin: 0 auto; width: 400px; height: 400px;"></canvas>
                            <div class="flex justify-between items-center text-sm mt-2">
                                <div>
                                    <a class="font-bold" style="margin-left: 5rem;">Tổng số nhiệm vụ: {{ $taskStatus['overdue'] + $taskStatus['upcoming'] + $taskStatus['inProgress'] + $taskStatus['completedOnTime'] + $taskStatus['completedLate']}}</a>
                                </div>
                                <div class="task-link" style="margin-right: 5rem;">
                                    @if (Auth::user()->role === 'admin' || Auth::user()->role === 'supper_admin')
                                    <a class="text-blue-500" href="{{route('tasks.byType.approved', 'task')}}">Xem chi tiết</a>
                                    @elseif(Auth::user()->role === 'sub_admin' || Auth::user()->role === 'staff')
                                    <a class="text-blue-500" href="{{route('documents.report')}}">Xem chi tiết</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 bg-gray-200 p-4">
                            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'supper_admin')
                                <a class="font-bold">Nhiệm vụ có báo cáo cần xem: {{ $tableTaskCount->count() }}</a>
                            @elseif(Auth::user()->role === 'sub_admin')
                                <a class="font-bold">Nhiệm vụ cần phê duyệt: {{ $tableTaskCount->count() }}</a>
                            @elseif(Auth::user()->role === 'staff')
                                <a class="font-bold">Nhiệm vụ cần báo cáo: {{ $tableTaskCount->count() }}</a>
                            @endif    
                            
                            <!-- Bảng dữ liệu -->
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-300">
                                    <tr class="border-b border-gray-300" style="background-color: #D3D4CF">
                                        <th style="width: 50px;" class="px-4 py-2 text-left">STT</th>
                                        <th class="px-4 py-2 text-left" style="width: 300px;">Tên nhiệm vụ</th>
                                        <th class="px-4 py-2 text-left whitespace-nowrap">Tiến độ</th>
                                        @if (Auth::user()->role === 'sub_admin' || Auth::user()->role === 'staff')
                                            <th class="px-4 py-2 text-left whitespace-nowrap">Trạng thái báo cáo</th>
                                        @endif
                                        @if (Auth::user()->role === 'sub_admin' ||  Auth::user()->role === 'admin' || Auth::user()->role === 'supper_admin')
                                        <th style="width: 120px;" class="px-4 py-2 text-left">Phê duyệt</th>
                                        @elseif(Auth::user()->role === 'staff')
                                        <th style="width: 120px;" class="px-4 py-2 text-left whitespace-nowrap">Báo cáo</th>
                                        @endif

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tableTask as $index => $task)
                     
                                        <tr class="border-b border-gray-200">
                                            <td style="width: 50px;" class="px-4 py-2">{{ $index + 1 }}</td>
                                            <td class="px-4 py-2" style="width: 300px;">{{ $task->name }}</td>
                                            <td class="px-4 py-2">{{ $task->getStatusLabel() }}</td>
                                            @if (Auth::user()->role === 'sub_admin' || Auth::user()->role === 'staff')
                                                <td class="px-4 py-2">{{ $task->taskResultsRelation->first()->getStatusLabelAttributeTaskTarget() ?? null }}</td>
                                            @endif
                                            <td>
                                                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'supper_admin')
                                                <button class="whitespace-nowrap bg-blue-400 text-white px-4 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300 ml-2"
                                                onclick="window.location.href='{{ route('tasks.edit.approved',['id' => $task->id, 'type' => $task->type]) }}'">
                                                Phê duyệt
                                                </button>
                                                @elseif(Auth::user()->role === 'staff')
                                                <button class="whitespace-nowrap bg-blue-400 text-white px-4 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300 ml-2"
                                                onclick="window.location.href='{{ route('documents.report.update.role', ['id' => $task->id, 'type' => $task->type]) }}'">
                                                Báo cáo
                                                </button>
                                                @elseif(Auth::user()->role === 'sub_admin')
                                                <button class="whitespace-nowrap bg-blue-400 text-white px-4 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300 ml-2"
                                                onclick="window.location.href='{{ route('documents.report.update.role', ['id' => $task->id, 'type' => $task->type]) }}'">
                                                Phê duyệt
                                                </button>
                                                @endif
                                       
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $tableTask->appends(['pageName' => 'target_pagging'])->links() }}
                                {{-- {{ $tableTask->links('pagination::bootstrap-4', ['pageName' => 'task_pagging']) }} <!-- Render pagination links --> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-1/2 px-2">
                    <!-- Nội dung của cột 3 chia làm 2 row -->
                    <div class="flex flex-col">
                        <div class="flex-1 bg-gray-200 p-4 mb-2">
                            <canvas id="targetChart" style=" max-width: 600px; max-height: 600px; margin: 0 auto; width: 400px; height: 400px;"></canvas>
                            <div class="flex justify-between items-center text-sm mt-2">
                                <div>
                                    <a class="font-bold" style="margin-left: 5rem;">Tổng số chỉ tiêu: {{ $targetStatus['overdue'] + $targetStatus['upcoming'] + $targetStatus['inProgress'] + $targetStatus['completedOnTime'] + $targetStatus['completedLate']}}</a>
                                </div>
                                <div class="task-link" style="margin-right: 5rem;">
                                    @if (Auth::user()->role === 'admin' || Auth::user()->role === 'supper_admin')
                                    <a class="text-blue-500" href="{{route('tasks.byType.approved', 'target')}}">Xem chi tiết</a>
                                    @elseif(Auth::user()->role === 'sub_admin' || Auth::user()->role === 'staff')
                                    <a class="text-blue-500" href="{{route('documents.report.target')}}">Xem chi tiết</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 bg-gray-200 p-4">
                                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'supper_admin')
                                    <a class="font-bold">Chỉ tiêu có báo cáo cần xem: {{ $tableTargetCount->count() }}</a>
                                @elseif(Auth::user()->role === 'sub_admin')
                                    <a class="font-bold">Chỉ tiêu cần phê duyệt: {{ $tableTargetCount->count() }}</a>
                                @elseif(Auth::user()->role === 'staff')
                                    <a class="font-bold">Chỉ tiêu cần báo cáo: {{ $tableTargetCount->count() }}</a>
                                @endif
                            <!-- Bảng dữ liệu -->
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-300">
                                    <tr class="border-b border-gray-300" style="background-color: #D3D4CF">
                                        <th style="width: 50px;" class="px-4 py-2 text-left">STT</th>
                                        <th class="px-4 py-2 text-left" style="width: 300px;">Tên chỉ tiêu</th>
                                        <th class="px-4 py-2 text-left whitespace-nowrap">Tiến độ</th>
                                        @if (Auth::user()->role === 'sub_admin' || Auth::user()->role === 'staff')
                                            <th class="px-4 py-2 text-left whitespace-nowrap">Trạng thái báo cáo</th>
                                        @endif
                                        @if (Auth::user()->role === 'sub_admin' ||  Auth::user()->role === 'admin' || Auth::user()->role === 'supper_admin')
                                        <th style="width: 120px;" class="px-4 py-2 text-left">Phê duyệt</th>
                                        @elseif(Auth::user()->role === 'staff')
                                        <th style="width: 120px;" class="px-4 py-2 text-left">Báo cáo</th>
                                      
                                        @endif

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tableTarget as $index => $target)
                     
                                        <tr class="border-b border-gray-200">
                                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                                            <td class="px-4 py-2" style="width: 300px;">{{ $target->name }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">{{ $target->getStatusLabel() }}</td>
                                            @if (Auth::user()->role === 'sub_admin' || Auth::user()->role === 'staff')
                                                <td class="px-4 py-2">{{ $target->taskResultsRelation->first()->getStatusLabelAttributeTaskTarget() ?? null }}</td>
                                            @endif
                                            <td>
                                                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'supper_admin')
                                                <button class="whitespace-nowrap bg-blue-400 text-white px-4 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300 ml-2"
                                                onclick="window.location.href='{{ route('tasks.edit.approved',['id' => $target->id, 'type' => $target->type]) }}'">
                                                Phê duyệt
                                                </button>
                                                @elseif(Auth::user()->role === 'staff')
                                                <button class="whitespace-nowrap bg-blue-400 text-white px-4 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300 ml-2"
                                                onclick="window.location.href='{{ route('documents.report.update.role', ['id' => $target->id, 'type' => $target->type]) }}'">
                                                Báo cáo
                                                </button>
                                                @elseif(Auth::user()->role === 'sub_admin')
                                                <button class="whitespace-nowrap bg-blue-400 text-white px-4 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300 ml-2"
                                                onclick="window.location.href='{{ route('documents.report.update.role', ['id' => $target->id, 'type' => $target->type]) }}'">
                                                Phê duyệt
                                                </button>
                                                @endif
                                       
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $tableTarget->appends(['pageName' => 'target_pagging'])->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <script>
        // Biểu đồ Task
        var ctxTask = document.getElementById('taskChart').getContext('2d');
        var taskChart = new Chart(ctxTask, {
            type: 'pie',
            data: {
                labels: ['Quá hạn', 'Sắp tới hạn', 'Đang thực hiện', 'Hoàn thành đúng hạn', 'Hoàn thành quá hạn'],
                datasets: [{
                    label: 'Trạng thái nhiệm vụ',
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
                events: [],
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Biểu đồ tiến độ nhiệm vụ'
                    },
                    datalabels: {
                        display: true,
                        color: 'black',
                        font: {
                            weight: 'bold',
                            size: 16
                        },
                        formatter: (value, ctx) => {
                            return value > 0 ? value : null;
                        }
                    },
                },
                hover: {
                    mode: null // Tắt hiệu ứng hover
                }
            },
            plugins: [ChartDataLabels]
        });

        // Biểu đồ Target
        var ctxTarget = document.getElementById('targetChart').getContext('2d');
        var targetChart = new Chart(ctxTarget, {
            type: 'pie',
            data: {
                labels: ['Quá hạn', 'Sắp tới hạn', 'Đang thực hiện', 'Hoàn thành đúng hạn', 'Hoàn thành quá hạn'],
                datasets: [{
                    label: 'Trạng thái chỉ tiêu',
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
                events: [],
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Biểu đồ tiến độ chỉ tiêu'
                    },
                    datalabels: {
                        display: true,
                        color: 'black',
                        font: {
                            weight: 'bold',
                            size: 16
                        },
                        formatter: (value, ctx) => {
                            return value > 0 ? value : null;
                        }
                    },
                },
                hover: {
                    mode: null // Tắt hiệu ứng hover
                }
            },
            plugins: [ChartDataLabels]
        });

    </script>


@endsection
