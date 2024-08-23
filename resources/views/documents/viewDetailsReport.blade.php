@extends('layouts.app')

@section('content')

    <style>
        .table-container {
            width: 2100px;
            border-collapse: collapse;
            overflow-x: auto;
            /* Cho phép cuộn ngang */
        }

        .table-container th,
        .table-container td {
            text-align: center;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
            /* Đảm bảo văn bản dài sẽ xuống dòng */
            white-space: normal;
            /* Cho phép văn bản wrap */
        }

        .table-container th {
            text-align: center;
            background-color: #f4f4f4;
            font-weight: bold;
        }
/* Cột cố định thứ ba */
        th:nth-child(3),
        td:nth-child(3) {
            position: -webkit-sticky;
            position: sticky;
            left: 0;
            padding: 5px;
            background-color: #f9f9f9;
            z-index: 10;
        }

        .col-110 {
            width: 110px;
        }

        .col-130 {
            width: 130px;
        }

        .col-320 {
            width: 320px;
        }

        .col-250 {
            width: 250px;
        }

        .col-100 {
            width: 100px;
        }

        .col-400 {
            width: 400px;
        }

        .col-600 {
            width: 600px;
        }

        .col-90 {
            width: 90px;
        }
        
        input[readonly], textarea[readonly] {
            background-color: #f0f0f0 !important;
            /* Màu nền nhạt hơn để hiển thị trạng thái readonly */
            cursor: not-allowed;
            /* Con trỏ chuột hiển thị "không được phép" khi di chuột qua trường nhập liệu */
        }

        .file-item {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .file-item img,
        .file-item i {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }

        .file-item .remove-button {
            position: absolute;
            top: 0;
            right: 0;
            background: rgba(255, 0, 0, 0.5);
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            text-align: center;
            line-height: 20px;
            cursor: pointer;
        }
    </style>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container mx-auto px-4 py-6">

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

        <div class="bg-white  overflow-hidden">
            <div class="p-6">
                <form action="{{ route('documents.task.update.cycle', $document->id) }}" method="POST" enctype="multipart/form-data"
                    class="bg-white p-6 ">
                    @csrf
                    @method('POST')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Cột trái -->
                        <div class="mb-4">
                            <label for="document_code" class="block text-gray-700 text-sm font-medium mb-2">Mã văn
                                bản:</label>
                            @if ($document->creator != auth()->user()->id)
                                <span class="rounded-lg">{{ $document->document_code }}</span>
                            @else
                                <input disabled readonly type="text" id="document_code" name="document_code"
                                    value="{{ $document->document_code }}"
                                    class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                                @error('document_code')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                        <div class="mb-4">
                            <label for="document_name" class="block text-gray-700 text-sm font-medium mb-2">Tên văn
                                bản:</label>
                            @if ($document->creator != auth()->user()->id)
                                <span class="rounded-lg">{{ $document->document_name }}</span>
                            @else
                                <input type="text" id="document_name" name="document_name"
                                    value="{{ $document->document_name }}"
                                    class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                            @endif

                        </div>
                        <div class="mb-4">
                            <label for="issuing_department" class="block text-gray-700 text-sm font-medium mb-2">Cơ quan, đơn vị phát
                                hành:</label>
                            @if ($document->creator != auth()->user()->id)
                                <span class="rounded-lg">{{ $document->issuingDepartment->name ?? '' }}</span>
                            @else
                                <select name="issuing_department" id="issuing_department" required
                                    @if ($document->creator != auth()->user()->id) disabled @endif
                                    class="form-input w-full border border-gray-300 rounded-lg p-2">
                                    @foreach ($organizations as $organization)
                                        <option value="{{ $organization->id }}"
                                            {{ $document->issuing_department == $organization->id ? 'selected' : '' }}>
                                            {{ $organization->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                        </div>
                        <div class="mb-4">
                            <label for="release_date" class="block text-gray-700 text-sm font-medium mb-2">Ngày phát
                                hành:</label>
                            @if ($document->creator != auth()->user()->id)
                                <span class="rounded-lg">{{ $document->getReleaseDateFormattedAttribute() }}</span>
                            @else
                                <input type="date" placeholder="dd-mm-yyyy"
                                min="1997-01-01" max="2100-12-31" id="release_date" name="release_date"
                                    @if ($document->creator != auth()->user()->id) readonly @endif
                                    value="{{ $document->getReleaseDateFormattedAttribute() }}"
                                    class="form-input w-full border border-gray-300 rounded-lg p-2">
                            @endif

                        </div>
                    </div>

                    <!-- Hàng upload file -->
                    <div class="mb-4" style="margin: 20px 0">
                        @if ($document->creator == auth()->user()->id)
                            <label for="files" class="block text-gray-700 text-sm font-medium mb-2">Tải lên tài liệu
                                (nhiều
                                tệp)</label>
                            <input type="file" id="files" name="files[]"
                                class="form-input w-full border border-gray-300 rounded-lg p-2" multiple>
                            <p class="text-gray-500 text-sm mt-1">Chọn nhiều tệp để tải lên.</p>
                        @endif
                        <!-- Khu vực để hiển thị danh sách tệp đã chọn -->
                        <div id="file-list-data" class="mt-2 file-list-data">
                            @foreach ($document->files as $file)
                                <div class="file-item flex items-center mb-2" data-file-id="{{ $file->id }}"
                                    data-file-type="{{ mime_content_type(storage_path('app/public/' . $file->file_path)) }}">
                                    <img class="file-icon w-12 h-12 mr-2" src="" alt="File icon">
                                    <span class="text-gray-700">{{ $file->file_name }}</span>

                                </div>
                            @endforeach
                        </div>
                        <div id="file-list" class="mt-2 file-list"></div>
                    </div>
                   
                    {{-- Tuần --}}
                    @if ($weekTask->isNotEmpty())
                        <div class="mt-6">
                            <h5 class="text-xl font-semibold mb-4">Danh sách nhiệm vụ/chỉ tiêu theo tuần:</h5>
                            <div class="overflow-x-auto">
                            
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table-container">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th rowspan="2"
                                                class="fixed-side col-110 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Mã 
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-130 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Cơ quan, tổ chức
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-320 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tên 
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-250 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tiến độ
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Đánh giá tiến độ
                                            </th>
                                            <th colspan="2"
                                                class="col-400 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tuần {{ $timeParamsWeek['current'] }}
                                            </th>
                                            <th colspan="2"
                                                class="col-400 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tuần {{ $timeParamsWeek['previous'] }}
                                            </th>
                                            <th colspan="2"
                                                class="col-400 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tuần {{ $timeParamsWeek['two_previous'] }}
                                            </th>


                                            <th rowspan="2"
                                                class="col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                               Hoàn thành
                                            </th>
                                            @if($hasCompletedWeekTask)
                                            <th rowspan="2"
                                                class="col-250 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nhận xét báo cáo
                                            </th>
                                            @endif
                                            <th rowspan="2"
                                                class="col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Thao tác
                                            </th>
                                        </tr>
                                        <tr>
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả</th>
                                          
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tài liệu</th>
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả</th>
                                          
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tài liệu</th>
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả</th>
                                         
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tài liệu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($weekTask as $task)
                                            @php
                                                $isDisabled = $task->status == 'Đã giao việc';
                                                $isStatus = $task->status == 'Đã hoàn thành chu kỳ';
                                                $hasOrganization = $task->hasOrganizationAppro();
                                                $taskApproval = $task->getTaskApprovalHistory();
                                            @endphp
                                            <tr>
                                                <td class="fixed-side col-110 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <input type="hidden" id="cycle_type" name="cycle_type[{{ $task->id }}]"
                                                        value="1">
                                                    <input type="hidden" id="number_cycle" name="number_cycle[{{ $task->id }}]"
                                                        value="{{ $timeParamsWeek['current'] }}">
                                                    <input type="hidden" id="document_id" name="document_id[{{ $task->id }}]"
                                                        value="{{ $document->id }}">
                                                    <input type="hidden" id="taskTargetId" name="task_target_id[{{ $task->id }}]"
                                                        value="{{ $task->id }}">
                                                    {{ $task->code }}
                                                </td>
                                                <td class="fixed-side col-130 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span
                                                        class="text-gray-900">{{ $task->organization->name ?? 'Chưa giao việc' }}</span>
                                                </td>
                                                <td class="fixed-side col-320 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->name }}</span>
                                                </td>
                                                <td class="fixed-side col-250 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->request_results }}</span>
                                                </td>
                                                <td class="fixed-side col-100 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->results }}</span>
                                                </td>
                                                <td class="fixed-side col-100 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->getStatus() }}</span>
                                                </td>
                                                <td
                                                    class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    <span >{{ $task->taskResultsByNumber($timeParamsWeek['current'])->result ?? '' }}</span>
                                                    
                                                   
                                                </td>
                                              
                                                <td
                                                    class="border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center !important">
                                                      @php
                                                        $file =
                                                            $task->getFilePathByType(
                                                                $timeParamsWeek['current']
                                                            ) ?? null;
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $task->cycle_type, 'numberType' => $timeParamsWeek['current']]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    <span>{{ $task->taskResultsByNumber($timeParamsWeek['previous'])->result ?? '' }}</span>
                                                </td>
                                            
                                                <td
                                                    class="border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    @php
                                                        $file =
                                                            $task->getFilePathByType(
                                                                $timeParamsWeek['previous']
                                                            ) ?? null;
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $task->cycle_type, 'numberType' => $timeParamsWeek['previous']]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>

                                                <td class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                   
                                                    <span>{{ $task->taskResultsByNumber($timeParamsWeek['two_previous'])->result ?? '' }}</span>
                                                </td>
                                            
                                                <td
                                                    class="border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    @php
                                                        $file =
                                                            $task->getFilePathByType(
                                                                $timeParamsWeek['two_previous']
                                                            ) ?? null;
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $task->cycle_type, 'numberType' => $timeParamsWeek['two_previous']]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>

                                                <td
                                                    class="col-100 border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center">
                                                    <label for="completed-{{ $task->id }}">
                                                        <input type="checkbox" name="task_completed[{{ $task->id }}]" disabled id="completed-{{ $task->id }}"
                                                               @if ($task->is_completed) checked readonly disabled  @endif
                                                               value="1" onchange="updateTaskInput('{{ $task->id }}', this.checked)">
                                           
                                                    </label>
                                                    <input type="hidden" id="task-result-input-{{ $task->id }}" name="task_result[{{ $task->id }}]"
                                                    value="{{ $task->taskResultsById($timeParamsWeek['current'])->id ?? ''}}">
                                                    <!-- Input để hiển thị trạng thái -->
                                                    <input type="hidden" id="task-input-{{ $task->id }}" name="task_status[{{ $task->id }}]"
                                                           value="{{ $task->is_completed ? '1' : '0' }}">
                                                </td>
                                                @if($hasCompletedWeekTask && $task->is_completed)
                                                    <td
                                                        class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center">

                                                        <textarea required
                                                        @if(!$hasOrganization) readonly @endif 
                                                        name="remarks[{{ $task->id }}]"    @if($taskApproval && $taskApproval->status === 'approved') readonly @endif 
                                                        id="remarks-{{$task->id}}"
                                                        placeholder="Nhập kết quả">{{ $taskApproval->remarks ?? '' }}</textarea>
                                 
                                                    </td>
                                                @endif
                                                <td
                                                    class="col-150 border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center">
                                                    @if($hasOrganization && $task->is_completed && ($taskApproval == null || ($taskApproval != null && $taskApproval->status === 'rejected')))
                                                        <button data-id="{{ $task->id }}" id="button-apprrover-{{$task->id}}"  style="margin:  10px 0" type="button" class="button-approved bg-green-500 text-white px-2 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300">
                                                            <i class="fas fa-check-circle"></i> Duyệt
                                                        </button>
                                                        
                                                        <!-- Nút Reject -->
                                                        <button data-id="{{ $task->id }}" id="button-reject-{{$task->id}}" style="margin:  10px 0" type="button" class="button-reject bg-red-500 text-white px-2 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300">
                                                            <i class="fas fa-times-circle"></i> Reject
                                                        </button>
                                                    @elseif($taskApproval != null)
                                                        <span>@if($taskApproval->status === 'approved') 
                                                            Đã duyệt 
                                                        @elseif ($taskApproval->status === 'rejected')
                                                            Đã từ chối
                                                        @endif
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="17"
                                                    class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                                                    Không có dữ liệu</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                
                            </div>
                            {{-- {{ $weekTask->withPath(url()->current())->links() }} --}}
                        </div>
                    @endif
                    {{-- End Tuần --}}

                    {{-- Tháng --}}
                    @if ($monthTask->isNotEmpty())
                        <div class="mt-6">
                            <h5 class="text-xl font-semibold mb-4">Danh sách nhiệm vụ/chỉ tiêu theo tháng:</h5>
                            <div class="overflow-x-auto">
                            
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table-container">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th rowspan="2"
                                                class="fixed-side col-110 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Mã 
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-130 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Cơ quan, tổ chức
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-320 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tên 
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-250 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tiến độ
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Đánh giá tiến độ
                                            </th>
                                            <th colspan="2"
                                                class="col-400 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tháng {{ $timeParamsMonth['current'] }}
                                            </th>
                                            <th colspan="2"
                                                class="col-400 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tháng {{ $timeParamsMonth['previous'] }}
                                            </th>
                                            <th colspan="2"
                                                class="col-400 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tháng {{ $timeParamsMonth['two_previous'] }}
                                            </th>


                                            <th rowspan="2"
                                                class="col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                               Hoàn thành
                                            </th>
                                            @if($hasCompletedMonthTask)
                                            <th rowspan="2"
                                                class="col-250 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nhận xét báo cáo
                                            </th>
                                            @endif
                                            <th rowspan="2"
                                                class="col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Thao tác
                                            </th>
                                        </tr>
                                        <tr>
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả</th>
                                            
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tài liệu</th>
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả</th>
                                          
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tài liệu</th>
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả</th>
                                         
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tài liệu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($weekTask as $task)
                                            @php
                                                $isDisabled = $task->status == 'Đã giao việc';
                                                $isStatus = $task->status == 'Đã hoàn thành chu kỳ';
                                                $hasOrganization = $task->hasOrganizationAppro();
                                                $taskApproval = $task->getTaskApprovalHistory();
                                            @endphp
                                            <tr>
                                                <td class="fixed-side col-110 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <input type="hidden" id="cycle_type" name="cycle_type[{{ $task->id }}]"
                                                        value="2">
                                                    <input type="hidden" id="number_cycle" name="number_cycle[{{ $task->id }}]"
                                                        value="{{ $timeParamsMonth['current'] }}">
                                                    <input type="hidden" id="document_id" name="document_id[{{ $task->id }}]"
                                                        value="{{ $document->id }}">
                                                    <input type="hidden" id="taskTargetId" name="task_target_id[{{ $task->id }}]"
                                                        value="{{ $task->id }}">
                                                    {{ $task->code }}
                                                </td>
                                                <td class="fixed-side col-130 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span
                                                        class="text-gray-900">{{ $task->organization->name ?? 'Chưa giao việc' }}</span>
                                                </td>
                                                <td class="fixed-side col-320 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->name }}</span>
                                                </td>
                                                <td class="fixed-side col-250 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->request_results }}</span>
                                                </td>
                                                <td class="fixed-side col-100 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->results }}</span>
                                                </td>
                                                <td class="fixed-side col-100 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->getStatus() }}</span>
                                                </td>
                                                <td
                                                    class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap text-center">

                                                    <span>{{ $task->taskResultsByNumber($timeParamsMonth['current'])->result ?? '' }}</span>

                                                   
                                                </td>
                                             
                                                <td
                                                    class="border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center !important">
                                                    @php
                                                        $file =
                                                            $task->getFilePathByType(
                                                                $timeParamsMonth['current']
                                                            ) ?? null;
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $task->cycle_type, 'numberType' => $timeParamsMonth['current']]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    <span>{{ $task->taskResultsByNumber($timeParamsMonth['previous'])->result ?? '' }}</span>
                                                </td>
                                             
                                                <td
                                                    class="border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    @php
                                                        $file =
                                                            $task->getFilePathByType(
                                                                $timeParamsMonth['previous']
                                                            ) ?? null;
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $task->cycle_type, 'numberType' => $timeParamsMonth['previous']]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>

                                                <td class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->taskResultsByNumber($timeParamsMonth['two_previous'])->result ?? '' }}</span>
                                                </td>
                                             
                                                <td
                                                    class="border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    @php
                                                        $file =
                                                            $task->getFilePathByType(
                                                                $timeParamsMonth['two_previous']
                                                            ) ?? null;
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $task->cycle_type, 'numberType' => $timeParamsMonth['two_previous']]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>

                                                <td
                                                    class="col-100 border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center">
                                                    <label for="completed-{{ $task->id }}">
                                                        <input type="checkbox" name="task_completed[{{ $task->id }}]" disabled id="completed-{{ $task->id }}"
                                                               @if ($task->is_completed) checked readonly disabled  @endif
                                                               value="1" onchange="updateTaskInput('{{ $task->id }}', this.checked)">
                                           
                                                    </label>
                                                    <input type="hidden" id="task-result-input-{{ $task->id }}" name="task_result[{{ $task->id }}]"
                                                    value="{{ $task->taskResultsById($timeParamsMonth['current'])->id ?? ''}}">
                                                    <!-- Input để hiển thị trạng thái -->
                                                    <input type="hidden" id="task-input-{{ $task->id }}" name="task_status[{{ $task->id }}]"
                                                           value="{{ $task->is_completed ? '1' : '0' }}">
                                                </td>
                                                @if($hasCompletedMonthTask && $task->is_completed)
                                                    <td
                                                        class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center">
                                                        <textarea required
                                                  
                                                        @if(!$hasOrganization) readonly @endif 
                                                        name="remarks[{{ $task->id }}]"    @if($taskApproval && $taskApproval->status === 'approved') readonly @endif 
                                                        id="remarks-{{$task->id}}"
                                                        placeholder="Nhập kết quả">{{ $taskApproval->remarks ?? '' }}</textarea>
                                                      
                                                    </td>
                                                @endif
                                                <td
                                                    class="col-100 border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center">
                                                    {{-- @php
                                                    $hasOrganization = $task->hasOrganizationAppro();
                                                    // dd($hasOrganization)
                                                    @endphp --}}
                                                    @if($hasOrganization && $task->is_completed && ($taskApproval == null || ($taskApproval != null && $taskApproval->status === 'rejected')))
                                                        <button data-id="{{ $task->id }}" id="button-apprrover-{{$task->id}}"  style="margin:  10px 0" type="button" class="button-approved bg-green-500 text-white px-2 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300">
                                                            <i class="fas fa-check-circle"></i> Duyệt
                                                        </button>
                                                        
                                                        <!-- Nút Reject -->
                                                        <button data-id="{{ $task->id }}" id="button-reject-{{$task->id}}" style="margin:  10px 0" type="button" class="button-reject bg-red-500 text-white px-2 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300">
                                                            <i class="fas fa-times-circle"></i> Reject
                                                        </button>
                                                    @elseif($taskApproval != null)
                                                        <span>@if($taskApproval->status === 'approved') 
                                                            Đã duyệt 
                                                        @elseif ($taskApproval->status === 'rejected')
                                                            Đã từ chối
                                                        @endif
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="17"
                                                    class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                                                    Không có dữ liệu</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                
                            </div>
                            {{-- {{ $weekTask->withPath(url()->current())->links() }} --}}
                        </div>
                    @endif
                    {{-- End tháng --}}

                    {{-- Quý --}}
                    @if ($quarterTask->isNotEmpty())
                        <div class="mt-6">
                            <h5 class="text-xl font-semibold mb-4">Danh sách nhiệm vụ/chỉ tiêu theo quý:</h5>
                            <div class="overflow-x-auto">
                            
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table-container">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th rowspan="2"
                                                class="fixed-side col-110 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Mã 
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-130 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Cơ quan, tổ chức
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-320 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tên 
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-250 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tiến độ
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Đánh giá tiến độ
                                            </th>
                                            <th colspan="2"
                                                class="col-400 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Quý {{ $timeParamsQuarter['current'] }}
                                            </th>
                                            <th colspan="2"
                                                class="col-400 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Quý {{ $timeParamsQuarter['previous'] }}
                                            </th>
                                            <th colspan="2"
                                                class="col-400 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Quý {{ $timeParamsQuarter['two_previous'] }}
                                            </th>


                                            <th rowspan="2"
                                                class="col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Hoàn thành
                                            </th>
                                            @if($hasCompletedQuarterTask)
                                            <th rowspan="2"
                                                class="col-250 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nhận xét báo cáo
                                            </th>
                                            @endif
                                            <th rowspan="2"
                                                class="col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Thao tác
                                            </th>
                                        </tr>
                                        <tr>
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả</th>
                                        
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tài liệu</th>
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả</th>
                                           
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tài liệu</th>
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả</th>
                                          
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tài liệu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($quarterTask as $task)
                                            @php
                                                $isDisabled = $task->status == 'Đã giao việc';
                                                $isStatus = $task->status == 'Đã hoàn thành chu kỳ';
                                                $hasOrganization = $task->hasOrganizationAppro();
                                                $taskApproval = $task->getTaskApprovalHistory();
                                            @endphp
                                            <tr>
                                                <td class="fixed-side col-110 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <input type="hidden" id="cycle_type" name="cycle_type[{{ $task->id }}]"
                                                        value="3">
                                                    <input type="hidden" id="number_cycle" name="number_cycle[{{ $task->id }}]"
                                                        value="{{ $timeParamsQuarter['current'] }}">
                                                    <input type="hidden" id="document_id" name="document_id[{{ $task->id }}]"
                                                        value="{{ $document->id }}">
                                                    <input type="hidden" id="taskTargetId" name="task_target_id[{{ $task->id }}]"
                                                        value="{{ $task->id }}">
                                                    {{ $task->code }}
                                                </td>
                                                <td class="fixed-side col-130 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span
                                                        class="text-gray-900">{{ $task->organization->name ?? 'Chưa giao việc' }}</span>
                                                </td>
                                                <td class="fixed-side col-320 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->name }}</span>
                                                </td>
                                                <td class="fixed-side col-250 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->request_results }}</span>
                                                </td>
                                                <td class="fixed-side col-100 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->results }}</span>
                                                </td>
                                                <td class="fixed-side col-100 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->getStatus() }}</span>
                                                </td>
                                                <td
                                                    class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap text-center">

                                                    <span>{{ $task->taskResultsByNumber($timeParamsQuarter['current'])->result ?? '' }}</span>

                                                   
                                                </td>
                                            
                                                <td
                                                    class="border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center !important">
                                                    @php
                                                    $file =
                                                        $task->getFilePathByType(
                                                            $timeParamsQuarter['current']
                                                        ) ?? null;
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $task->cycle_type, 'numberType' => $timeParamsQuarter['current']]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    <span>{{ $task->taskResultsByNumber($timeParamsQuarter['previous'])->result ?? '' }}</span>
                                                </td>
                                            
                                                <td
                                                    class="border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    @php
                                                        $file =
                                                            $task->getFilePathByType(
                                                                $timeParamsQuarter['previous']
                                                            ) ?? null;
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $task->cycle_type, 'numberType' => $timeParamsQuarter['previous']]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>

                                                <td class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->taskResultsByNumber($timeParamsQuarter['two_previous'])->result ?? '' }}</span>
                                                </td>
                                             
                                                <td
                                                    class="border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    @php
                                                        $file =
                                                            $task->getFilePathByType(
                                                                $timeParamsQuarter['two_previous']
                                                            ) ?? null;
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $task->cycle_type, 'numberType' => $timeParamsQuarter['two_previous']]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>

                                                <td
                                                    class="col-100 border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center">
                                                    <label for="completed-{{ $task->id }}">
                                                        <input type="checkbox" name="task_completed[{{ $task->id }}]" disabled id="completed-{{ $task->id }}"
                                                            @if ($task->is_completed) checked readonly disabled  @endif
                                                            value="1" onchange="updateTaskInput('{{ $task->id }}', this.checked)">
                                        
                                                    </label>
                                                    <input type="hidden" id="task-result-input-{{ $task->id }}" name="task_result[{{ $task->id }}]"
                                                    value="{{ $task->taskResultsById($timeParamsQuarter['current'])->id ?? ''}}">
                                                    <!-- Input để hiển thị trạng thái -->
                                                    <input type="hidden" id="task-input-{{ $task->id }}" name="task_status[{{ $task->id }}]"
                                                        value="{{ $task->is_completed ? '1' : '0' }}">
                                                </td>
                                                @if($hasCompletedQuarterTask && $task->is_completed)
                                                    <td
                                                        class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center">
                                             
                                                        <textarea required
                                                            @if(!$hasOrganization) readonly @endif 
                                                            name="remarks[{{ $task->id }}]"    @if($taskApproval && $taskApproval->status === 'approved') readonly @endif 
                                                            id="remarks-{{$task->id}}"
                                                            placeholder="Nhập kết quả">{{ $taskApproval->remarks ?? '' }}</textarea>
                                                    </td>
                                                @endif
                                                <td
                                                    class="col-100 border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center">
                                                    {{-- @php
                                                    $hasOrganization = $task->hasOrganizationAppro();
                                                    // dd($hasOrganization)
                                                    @endphp --}}
                                                    @if($hasOrganization && $task->is_completed && ($taskApproval == null || ($taskApproval != null && $taskApproval->status === 'rejected')))
                                                        <button data-id="{{ $task->id }}" id="button-apprrover-{{$task->id}}"  style="margin:  10px 0" type="button" class="button-approved bg-green-500 text-white px-2 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300">
                                                            <i class="fas fa-check-circle"></i> Duyệt
                                                        </button>
                                                        
                                                        <!-- Nút Reject -->
                                                        <button data-id="{{ $task->id }}" id="button-reject-{{$task->id}}" style="margin:  10px 0" type="button" class="button-reject bg-red-500 text-white px-2 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300">
                                                            <i class="fas fa-times-circle"></i> Reject
                                                        </button>
                                                    @elseif($taskApproval != null)
                                                        <span>@if($taskApproval->status === 'approved') 
                                                            Đã duyệt 
                                                        @elseif ($taskApproval->status === 'rejected')
                                                            Đã từ chối
                                                        @endif
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="17"
                                                    class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                                                    Không có dữ liệu</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                
                            </div>
                            {{-- {{ $weekTask->withPath(url()->current())->links() }} --}}
                        </div>
                    @endif
                    {{-- End Quý --}}

                    {{-- Năm --}}
                    @if ($yearTask->isNotEmpty())
                        <div class="mt-6">
                            <h5 class="text-xl font-semibold mb-4">Danh sách nhiệm vụ/chỉ tiêu theo năm:</h5>
                            <div class="overflow-x-auto">
                            
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table-container">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th rowspan="2"
                                                class="fixed-side col-110 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Mã 
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-130 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Cơ quan, tổ chức
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-320 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tên 
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-250 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tiến độ
                                            </th>
                                            <th rowspan="2"
                                                class="fixed-side col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Đánh giá tiến độ
                                            </th>
                                            <th colspan="2"
                                                class="col-400 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Năm {{ $timeParamsYear['current'] }}
                                            </th>
                                            <th colspan="2"
                                                class="col-400 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Năm {{ $timeParamsYear['previous'] }}
                                            </th>
                                            <th colspan="2"
                                                class="col-400 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Năm {{ $timeParamsYear['two_previous'] }}
                                            </th>


                                            <th rowspan="2"
                                                class="col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Hoàn thành
                                            </th>
                                            @if($hasCompletedYearTask)
                                            <th rowspan="2"
                                                class="col-250 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nhận xét báo cáo
                                            </th>
                                            @endif
                                            <th rowspan="2"
                                                class="col-100 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Thao tác
                                            </th>
                                        </tr>
                                        <tr>
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả</th>
                                          
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tài liệu</th>
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả</th>
                                         
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tài liệu</th>
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kết quả</th>
                                         
                                            <th
                                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tài liệu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($yearTask as $task)
                                            @php
                                                $isDisabled = $task->status == 'Đã giao việc';
                                                $isStatus = $task->status == 'Đã hoàn thành chu kỳ';
                                                $hasOrganization = $task->hasOrganizationAppro();
                                                $taskApproval = $task->getTaskApprovalHistory();
                                            @endphp
                                            <tr>
                                                <td class="fixed-side col-110 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <input type="hidden" id="cycle_type" name="cycle_type[{{ $task->id }}]"
                                                        value="4">
                                                    <input type="hidden" id="number_cycle" name="number_cycle[{{ $task->id }}]"
                                                        value="{{ $timeParamsYear['current'] }}">
                                                    <input type="hidden" id="document_id" name="document_id[{{ $task->id }}]"
                                                        value="{{ $document->id }}">
                                                    <input type="hidden" id="taskTargetId" name="task_target_id[{{ $task->id }}]"
                                                        value="{{ $task->id }}">
                                                    {{ $task->code }}
                                                </td>
                                                <td class="fixed-side col-130 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span
                                                        class="text-gray-900">{{ $task->organization->name ?? 'Chưa giao việc' }}</span>
                                                </td>
                                                <td class="fixed-side col-320 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->name }}</span>
                                                </td>
                                                <td class="fixed-side col-250 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->request_results }}</span>
                                                </td>
                                                <td class="fixed-side col-100 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->results }}</span>
                                                </td>
                                                <td class="fixed-side col-100 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->getStatus() }}</span>
                                                </td>
                                                <td
                                                    class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    <span>{{ $task->taskResultsByNumber($timeParamsYear['current'])->result ?? '' }}</span>
                                                   
                                                </td>
                                            
                                                <td
                                                    class=" border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center !important">
                                                    @php
                                                        $file =
                                                            $task->getFilePathByType(
                                                                $timeParamsYear['current']
                                                            ) ?? null;
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $task->cycle_type, 'numberType' => $timeParamsYear['current']]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif

                                                </td>
                                                <td
                                                    class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    <span>{{ $task->taskResultsByNumber($timeParamsYear['previous'])->result ?? '' }}</span>
                                                </td>
                                             
                                                <td
                                                    class="border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    @php
                                                        $file =
                                                            $task->getFilePathByType(
                                                                $timeParamsYear['previous']
                                                            ) ?? null;
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $task->cycle_type, 'numberType' => $timeParamsYear['previous']]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>

                                                <td class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap">
                                                    <span>{{ $task->taskResultsByNumber($timeParamsYear['two_previous'])->result ?? '' }}</span>
                                                </td>
                                             
                                                <td
                                                    class="border border-gray-300 px-4 py-2 whitespace-nowrap text-center">
                                                    @php
                                                        $file =
                                                            $task->getFilePathByType(
                                                                $timeParamsYear['two_previous']
                                                            ) ?? null;
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $task->cycle_type, 'numberType' => $timeParamsYear['two_previous']]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>

                                                <td
                                                    class="col-100 border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center">
                                                    <label for="completed-{{ $task->id }}">
                                                        <input type="checkbox" name="task_completed[{{ $task->id }}]" disabled id="completed-{{ $task->id }}"
                                                            @if ($task->is_completed) checked readonly disabled  @endif
                                                            value="1" onchange="updateTaskInput('{{ $task->id }}', this.checked)">
                                        
                                                    </label>
                                                    <input type="hidden" id="task-result-input-{{ $task->id }}" name="task_result[{{ $task->id }}]"
                                                    value="{{ $task->taskResultsById($timeParamsYear['current'])->id ?? ''}}">
                                                    <!-- Input để hiển thị trạng thái -->
                                                    <input type="hidden" id="task-input-{{ $task->id }}" name="task_status[{{ $task->id }}]"
                                                        value="{{ $task->is_completed ? '1' : '0' }}">
                                                </td>
                                                @if($hasCompletedYearTask && $task->is_completed)
                                                    <td
                                                        class="col-250 border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center">
                                                        <textarea required
                                                            @if(!$hasOrganization) readonly @endif 
                                                            name="remarks[{{ $task->id }}]"    @if($taskApproval && $taskApproval->status === 'approved') readonly @endif 
                                                            id="remarks-{{$task->id}}"
                                                            placeholder="Nhập kết quả">{{ $taskApproval->remarks ?? '' }}</textarea>
                                                    </td>
                                                @endif
                                                <td
                                                    class="col-100 border border-gray-300 px-4 py-2 whitespace-nowrap text-center" style="text-align: center">
                                                    {{-- @php
                                                    $hasOrganization = $task->hasOrganizationAppro();
                                                    // dd($hasOrganization)
                                                    @endphp --}}
                                                    @if($hasOrganization && $task->is_completed && ($taskApproval == null || ($taskApproval != null && $taskApproval->status === 'rejected')))
                                                        <button data-id="{{ $task->id }}" id="button-apprrover-{{$task->id}}"  style="margin:  10px 0" type="button" class="button-approved bg-green-500 text-white px-2 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300">
                                                            <i class="fas fa-check-circle"></i> Duyệt
                                                        </button>
                                                        
                                                        <!-- Nút Reject -->
                                                        <button data-id="{{ $task->id }}" id="button-reject-{{$task->id}}" style="margin:  10px 0" type="button" class="button-reject bg-red-500 text-white px-2 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300">
                                                            <i class="fas fa-times-circle"></i> Reject
                                                        </button>
                                                    @elseif($taskApproval != null)
                                                        <span>@if($taskApproval->status === 'approved') 
                                                            Đã duyệt 
                                                        @elseif ($taskApproval->status === 'rejected')
                                                            Đã từ chối
                                                        @endif
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="17"
                                                    class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                                                    Không có dữ liệu</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                
                            </div>
                            {{-- {{ $weekTask->withPath(url()->current())->links() }} --}}
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <script>
        function updateTaskInput(taskId, isChecked) {
            var input = document.getElementById('task-input-' + taskId);
            input.value = isChecked ? '1' : '0';
        }
        function downloadFile(filename, type) {
            window.location.href = '/download/' + filename + '/' + type;
        }

        function uploadFile(id, type) {
            console.log(type);
            let fileInput;
            let file;
            if (type == "1") {
                fileInput = document.getElementById('fileInput-' + id);
                file = fileInput.files[0];
            } else {
                fileInput = document.getElementById('criteria-fileInput-' + id);
                file = fileInput.files[0];
            }
            console.log(fileInput);
            console.log(file);
            if (file) {
                const formData = new FormData();
                formData.append('files', file);
                formData.append('file_id', id);

                formData.append('type', type);

                // Gửi file đến server
                fetch('/upload', { // Thay đổi URL theo API endpoint của bạn
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            //alert("Upload file thành công " + data.file_path);
                            var uploadStatus = document.getElementById('uploadStatus-' + data.id);
                            uploadStatus.classList.remove('hidden');
                            document.getElementById('button-file-task-'+ data.id).style.display = 'none';
                        } else {
                            document.getElementById('fileName').textContent = 'Upload failed: ' + data.message;
                        }
                    })
                    .catch(error => {
                        console.error('Error uploading file:', error);
                    });
            }
        }

        function toggleRow(taskId) {
            // Get all criteria rows for the given taskId
            const criteriaRows = document.querySelectorAll(`tr[id^="criteria-row-${taskId}"]`);

            // Loop through each row and toggle visibility
            criteriaRows.forEach(row => {
                row.classList.toggle('hidden');
            });

            // Find the button that was clicked
            const button = document.querySelector(`button[onclick="toggleRow('${taskId}')"]`);
            if (button) {
                // Find the SVG icon within that button
                const svgIcon = button.querySelector('svg');
                if (svgIcon) {
                    svgIcon.classList.toggle('rotate-90');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('files');
            const fileList = document.getElementById('file-list');
            const fileListData = document.querySelectorAll('.file-item');

            function getFileIcon(fileType) {
                const baseUrl = '/icons/';
                switch (fileType) {
                    case 'application/pdf':
                        return baseUrl + 'pdf.png';
                    case 'application/msword':
                    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                        return baseUrl + 'word.png';
                    case 'application/vnd.ms-excel':
                    case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                        return baseUrl + 'excel.png';
                    default:
                        return baseUrl + 'default-icon.png';
                }
            }
            fileListData.forEach(function(item) {
                const fileType = item.dataset.fileType;
                const iconUrl = getFileIcon(fileType);
                console.log(iconUrl);
                const iconElement = item.querySelector('.file-icon');
                if (iconElement) {
                    iconElement.src = iconUrl;
                }
            });

           
            // Xóa tệp từ danh sách đã chọn
            function removeFile(index) {
                const dt = new DataTransfer();
                const files = fileInput.files;

                Array.from(files).forEach((file, i) => {
                    if (i !== index) dt.items.add(file);
                });

                fileInput.files = dt.files;
                updateFileList();
            }


            // Xóa tệp cũ từ danh sách
            function removeOldFile(fileId) {
                fetch(`/delete-file/${fileId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`.file-item[data-file-id="${fileId}"]`).remove();
                        } else {
                            alert('Có lỗi xảy ra khi xóa tệp.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi xóa tệp.');
                    });
            }

            // fileInput.addEventListener('change', updateFileList);

            // Xử lý sự kiện xóa tệp cũ
            document.querySelectorAll('.remove-file-button').forEach(button => {
                button.addEventListener('click', () => {
                    const fileId = button.closest('.file-item').dataset.fileId;
                    removeOldFile(fileId);
                });
            });

            // Cập nhật danh sách tệp khi trang được tải
            //updateFileList();

            ///////////==================từ chối/duyệt================
            document.querySelectorAll('.button-approved').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const taskId = this.getAttribute('data-id');
                    const remarksValue = document.getElementById('remarks-' + taskId).value;
                    const taskResultId = document.getElementById('task-result-input-' + taskId).value;
                    
                    fetch('{{ route('tasks.updateRemarks') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                taskId: taskId,
                                remarks: remarksValue,
                                type : 'Approval',
                                taskResultId : taskResultId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log(data.message)
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Duyệt thành công!',
                                    text: data.message,
                                    confirmButtonText: 'OK'
                                });
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                Swal.fire({
                                icon: 'error',
                                title: 'Có lỗi xảy ra!',
                                text: 'Đã xảy ra lỗi trong quá trình thực hiện.',
                                confirmButtonText: 'Đóng'
                            });
                               // alert(data.message);
                                // Xử lý lỗi
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Xử lý lỗi
                        });
                });
            });
            document.querySelectorAll('.button-reject').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const taskId = this.getAttribute('data-id');
                    const remarksValue = document.getElementById('remarks-' + taskId).value;
                    const taskResultId = document.getElementById('task-result-input-' + taskId).value;
                    fetch('{{ route('tasks.updateRemarks') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                taskId: taskId,
                                remarks: remarksValue,
                                type : 'Reject',
                                taskResultId : taskResultId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log(data.message)
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Từ chối thành công!',
                                    text: data.message,
                                    confirmButtonText: 'OK'
                                });
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                               
                            } else {
                                Swal.fire({
                                icon: 'error',
                                title: 'Có lỗi xảy ra!',
                                text: 'Đã xảy ra lỗi trong quá trình thực hiện.',
                                confirmButtonText: 'Đóng'
                            });
                               // alert(data.message);
                                // Xử lý lỗi
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Xử lý lỗi
                        });
                });
            });
        });
    </script>
@endsection
