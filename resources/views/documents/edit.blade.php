@extends('layouts.app')

@section('content')
    <style>
/* .table-container {
    table-layout: fixed;
    width: 100%; 
} */
    .table-container th, .table-container td {
        width: 250px;
        text-align: center;
        word-wrap: break-word;
        white-space: normal;
    }

        #assigned-organizations-modal {
            z-index: 99999999;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th:first-child,
        td:first-child {
            position: -webkit-sticky;
            /* for Safari */
            position: sticky;
            left: 0;
            background-color: #f9f9f9;
            /* Background color to cover any gaps */
            z-index: 10;
            /* Ensure it is on top of other cells */
        }
/* 
        th:nth-child(2),
th:nth-child(3),
td:nth-child(2),
td:nth-child(3) {
    position: -webkit-sticky;
    position: sticky;
    left: 0;
    background-color: #f9f9f9;
    z-index: 100;
} */
        .table-container {
            overflow-x: auto;
        }

        input[readonly] {
            background-color: #f0f0f0;
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


     <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
        <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-lg shadow-lg">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cột trái -->
                <div class="mb-4">
                    <label for="document_code" class="block text-gray-700 text-sm font-medium mb-2">Mã văn bản:</label>
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
                    <label for="document_name" class="block text-gray-700 text-sm font-medium mb-2">Tên văn bản:</label>
                    @if ($document->creator != auth()->user()->id)
                        <span class="rounded-lg">{{ $document->document_name }}</span>
                    @else
                        <input type="text" id="document_name" name="document_name" value="{{ $document->document_name }}"
                        class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    @endif
                   
                </div>
                <div class="mb-4">
                    <label for="issuing_department" class="block text-gray-700 text-sm font-medium mb-2">Đơn vị phát
                        hành:</label>
                        @if ($document->creator != auth()->user()->id)
                            <span class="rounded-lg">{{ $document->issuingDepartment->name ?? "" }}</span>
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
                    <label for="release_date" class="block text-gray-700 text-sm font-medium mb-2">Ngày phát hành:</label>
                    @if ($document->creator != auth()->user()->id)
                    <span class="rounded-lg">{{ $document->getReleaseDateFormattedAttribute() }}</span>
                @else
                    <input type="date" id="release_date" name="release_date"
                    @if ($document->creator != auth()->user()->id) readonly @endif
                    value="{{ $document->getReleaseDateFormattedAttribute() }}"
                    class="form-input w-full border border-gray-300 rounded-lg p-2">
                @endif

                </div>
            </div>

            <!-- Hàng upload file -->
            <div class="mb-4" style="margin: 20px 0">
                @if ($document->creator == auth()->user()->id)
                    <label for="files" class="block text-gray-700 text-sm font-medium mb-2">Tải lên tài liệu (nhiều
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
                            <button type="button" @if ($document->creator != auth()->user()->id) disabled @endif
                                class="remove-button ml-2 bg-red-500 text-white px-2 py-1 rounded remove-file-button">×</button>
                        </div>
                    @endforeach
                </div>
                <div id="file-list" class="mt-2 file-list"></div>
            </div>
            {{-- Danh sách công việc --}}
            <div class="mt-6">
                <h5 class="text-xl font-semibold mb-4">Danh sách đầu công việc:</h5>
                <div class="overflow-x-auto">

                    {{-- Tuần --}}
                    @if ($weekTask->isNotEmpty())
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table-container">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mã đầu việc</th>
                                        
                                    <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tổ chức</th>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tên đầu việc</th>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kết quả</th>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tiến độ</th>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Đánh giá tiến độ</th>

                                    <th colspan="3"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tuần {{ $timeParamsWeek['two_previous'] }}</th>
                                    <th colspan="3"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tuần {{ $timeParamsWeek['previous'] }}</th>
                                    <th colspan="3"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tuần {{ $timeParamsWeek['current'] }}</th>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Thao tác</th>
                                </tr>
                                <tr>

                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kết quả
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mô tả
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tài liệu
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kết quả
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mô tả
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tài liệu
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kết quả
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mô tả
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tài liệu
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($weekTask as $task)
                                    @php
                                        $isDisabled = $task->status == 'Đã giao việc';
                                        $isStatus = $task->status == 'Đã hoàn thành chu kỳ';
                                    @endphp
                                    <tr>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <input required type="hidden" id="typeCurrent" name="typeCurrent[]"
                                                value="1">

                                            <input required type="hidden" id="numberCurrent" name="numberCurrent[]"
                                                value="{{ $timeParamsWeek['current'] }}">
                                            <input required type="hidden" id="document_id" name="document_id[]"
                                                value="{{ $document->id }}">
                                            <input required type="hidden" id="task_code" name="task_code[]"
                                                value="{{ $task->task_code }}">
                                            <input required type="hidden" id="taskId" name="task_id[]"
                                                value="{{ $task->id }}">
                                            <button type="button" class="flex items-center text-blue-500 hover:underline"
                                                onclick="toggleRow('{{ $task->id }}')">
                                                <svg class="w-5 h-5 mr-2 {{ $task->criteriasTask->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                                {{ $task->task_code }}
                                            </button>
                                        </td>
                                        
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">            
                                            <span class="text-gray-900 w-2/3">{{ $task->organization->name??'Chưa giao việc' }}</span>
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <input required type="hidden" id="task_code"
                                                
                                                value="{{ $task->task_code }}" class="task-input required"
                                                data-id="{{ $task->id }}">
                                                
                                            @if ($isDisabled)
                                                <span class="rounded-lg">{{ $task->task_name }}</span>
                                            @else
                                                <input required type="text" name="task_name[]"
                                                
                                                value="{{ $task->task_name }}" class="task-input required">
                                            @endif
                                           
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @if ($isDisabled)
                                                <span class="rounded-lg">{{ $task->required_result }}</span>
                                            @else
                                                <input required
                                                type="text" name="required_result[]" id="required_result"
                                                value="{{ $task->required_result }}"
                                                
                                                placeholder="Nhập đánh giá">
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @if ($isDisabled)
                                            <span class="rounded-lg">{{ $task->progress }}</span>
                                            @else
                                            <input required
                                            type="text" name="task_progress[]" id="task_progress"
                                            value="{{ $task->progress }}" readonly>
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @if ($isDisabled)
                                                <span class="rounded-lg">{{ $task->getStatus() }}</span>
                                            @else
                                                <input
                                                type="text" name="progress_evaluation[]" id="progress_evaluation"
                                                value="{{ $task->getStatus() }}" readonly>
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                            
                                        <span class="rounded-lg"> {{ $task->taskResultsByNumber($timeParamsWeek['two_previous'], $task->reporting_cycle)->result ?? '' }}</span>
                                           
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <span class="rounded-lg"> {{ $task->taskResultsByNumber($timeParamsWeek['two_previous'], $task->reporting_cycle)->description ?? '' }}</span>
                                           
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @php
                                                $file =
                                                    $task->getFilePathByType(
                                                        $timeParamsWeek['two_previous'],
                                                        $task->reporting_cycle,
                                                    ) ?? null;
                                            @endphp

                                            @if ($file && !empty($file->file_path))
                                                <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1]) }}"
                                                    class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                    download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @else
                                                <span class="text-red-500"></span>
                                            @endif
                                            {{-- <button type="button" onclick="downloadFile('{{ $file->file_name }}')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button> --}}
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->taskResultsByNumber($timeParamsWeek['previous'], $task->reporting_cycle)->result ?? '' }}
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->taskResultsByNumber($timeParamsWeek['previous'], $task->reporting_cycle)->description ?? '' }}
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @php
                                                $file =
                                                    $task->getFilePathByType(
                                                        $timeParamsWeek['previous'],
                                                        $task->reporting_cycle,
                                                    ) ?? null;
                                            @endphp

                                            @if ($file && !empty($file->file_path))
                                                <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1]) }}"
                                                    class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                    download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @else
                                                <span class="text-red-500"></span>
                                            @endif
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="task_current_result[]" id="current_result"   @if ($document->creator == auth()->user()->id) readonly @endif
                                                value="{{ $task->taskResultsById($task->id, $timeParamsWeek['current'], $task->reporting_cycle)->result ?? '' }}"
                                                placeholder="Nhập kết quả"></td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                @if ($document->creator == auth()->user()->id) readonly @endif
                                                type="text" name="task_current_note[]" id="current_note"
                                                value="{{ $task->taskResultsById($task->id, $timeParamsWeek['current'], $task->reporting_cycle)->description ?? '' }}"
                                                placeholder="Nhập mô tả"></td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <input type="file" id="fileInput-{{ $task->id }}"
                                                name="file_{{ $task->id }}" style="display: none;"
                                                onchange="uploadFile('{{ $task->id }}', 1)">
                                            @php
                                                $file = $task->getFilePath($task->id) ?? null;
                                                //dd($filePath->file_path);
                                            @endphp
                                            @if ($file && !empty($file->file_path))
                                                <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1]) }}"
                                                    @if ($document->creator == auth()->user()->id) readonly @endif
                                                    class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                    download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @else
                                                <button type="button" id="button-file-task-{{ $task->id }}"
                                                    @if ($document->creator == auth()->user()->id) readonly @endif
                                                    onclick="document.getElementById('fileInput-{{ $task->id }}').click()"
                                                    class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                    <i class="fas fa-upload"></i>
                                                </button>
                                            @endif


                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @if ($document->creator == auth()->user()->id)
                                                @if ($task->status == 'Đã giao việc')
                                                    <button data-document-id="{{ $document->id }}"
                                                        data-status = "{{ $task->status }}"
                                                        data-task-code="{{ $task->task_code }}"
                                                        data-task-id="{{ $task->id }}"
                                                        class="assign-task open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                        <i class="fa fa-tasks"></i>
                                                    </button>
                                                @else
                                                    <button data-document-id="{{ $document->id }}"
                                                        data-status = "{{ $task->status }}"
                                                        data-task-code="{{ $task->task_code }}"
                                                        data-task-id="{{ $task->id }}"
                                                        class="open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                        <i class="fa fa-tasks"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($task->criteriasTask->count() > 0)
                                        @forelse ($task->criteriasTask as $index => $criterion)
                                            <tr id="criteria-row-{{ $task->id }}" class="hidden">
                                                <td class=" px-6 py-4 whitespace-nowrap">
                                                    <input required type="hidden" id="typeCurrent"
                                                        name="criteriaTypeCurrent[]" value="1">

                                                    <input required type="hidden" id="numberCurrent"
                                                        name="criteriaNumberCurrent[]"
                                                        value="{{ $timeParamsWeek['current'] }}">
                                                    <input required type="hidden" id="document_id" name="document_id[]"
                                                        value="{{ $document->id }}">
                                                    <input required type="hidden" id="criteria_code"
                                                        name="criteria_code[]" value="{{ $criterion->CriteriaCode }}">
                                                    <input required type="hidden" id="criteriaId" name="criteria_id[]"
                                                        value="{{ $criterion->id }}">
                                                    <button type="button"
                                                        class="flex items-center text-blue-500 hover:underline">
                                                        <svg hidden
                                                            class="w-5 h-5 mr-2 {{ $task->criteriasTask->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                        {{ $index + 1 }}
                                                        {{-- {{ $criterion->CriteriaCode }} --}}
                                                    </button>
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                                                      
                                                            <span class="text-gray-900 w-2/3">{{ $task->organization->name??'Chưa giao việc' }}</span>
            
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    
                                                    <input required type="hidden" id="criteria_code"
                                                        
                                                        value="{{ $criterion->CriteriaCode }}"
                                                        class="criteria-input required" data-id="{{ $criterion->id }}">

                                                        @if ($isDisabled)
                                                         <span class="rounded-lg">{{ $criterion->CriteriaName }}</span>
                                                        @else
                                                        <input required type="text" name="criteria_name[]"
                                                        
                                                        value="{{ $criterion->CriteriaName }}"
                                                        class="criteria-input required">
                                                        @endif
                                                   
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    @if ($isDisabled)
                                                    <span class="rounded-lg">{{ $criterion->RequestResult }}</span>
                                                   @else
                                                        <input required type="text" name="criteria_required_result[]"
                                                        id="required_result" value="{{ $criterion->RequestResult }}"
                                                        
                                                        placeholder="Nhập đánh giá">
                                                   @endif
                                                   
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    @if ($isDisabled)
                                                    <span class="rounded-lg">{{ $criterion->progress }}</span>
                                                   @else
                                                   <input required type="text" name="criterion_progress[]"
                                                   id="criterion_progress" value="{{ $criterion->progress }}"
                                                   readonly>
                                                   @endif
                                                  
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    @if ($isDisabled)
                                                    <span class="rounded-lg">{{ $criterion->getStatus() }}</span>
                                                   @else
                                                    <input type="text" name="criteria_progress_evaluation[]"
                                                    id="progress_evaluation" value="{{ $criterion->getStatus() }}"
                                                    readonly>
                                                   @endif   
                                                    
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    {{ $criterion->taskResultsByNumber($timeParamsWeek['two_previous'], $task->reporting_cycle)->result ?? '' }}
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    {{ $criterion->taskResultsByNumber($timeParamsWeek['two_previous'], $task->reporting_cycle)->description ?? '' }}
                                                </td>
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $file =
                                                            $criterion->getFilePathByType(
                                                                $timeParamsWeek['two_previous'],
                                                                $task->reporting_cycle,
                                                            ) ?? null;
                                                    @endphp

                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    {{ $criterion->taskResultsByNumber($timeParamsWeek['previous'], $task->reporting_cycle)->result ?? '' }}
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    {{ $criterion->taskResultsByNumber($timeParamsWeek['previous'], $task->reporting_cycle)->description ?? '' }}
                                                </td>
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $file =
                                                            $criterion->getFilePathByType(
                                                                $timeParamsWeek['previous'],
                                                                $task->reporting_cycle,
                                                            ) ?? null;
                                                    @endphp

                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>

                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input
                                                    @if ($document->creator == auth()->user()->id) readonly @endif
                                                        required type="text" name="criteria_current_result[]"
                                                        id="current_result"
                                                        value="{{ $criterion->taskResultsById($criterion->id, $timeParamsWeek['current'], $task->reporting_cycle)->result ?? '' }}
"
                                                        placeholder="Nhập kết quả">
                                                </td>

                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input
                                                    @if ($document->creator == auth()->user()->id) readonly @endif
                                                        required type="text" name="criteria_current_note[]"
                                                        id="current_note"
                                                        value="{{ $criterion->taskResultsById($criterion->id, $timeParamsWeek['current'], $task->reporting_cycle)->description ?? '' }}
"
                                                        placeholder="Nhập mô tả">
                                                </td>

                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    <input type="file" id="criteria-fileInput-{{ $criterion->id }}"
                                                        name="criteria_file_{{ $criterion->id }}" style="display: none;"
                                                        onchange="uploadFile('{{ $criterion->id }}', 2)">

                                                    @php
                                                        $file = $criterion->getFilePath($criterion->id) ?? null;
                                                        //dd($filePath->file_path);
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                            @if ($document->creator == auth()->user()->id) readonly @endif
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <button type="button"
                                                            id="button-file-criteria-{{ $criterion->id }}"
                                                            @if ($document->creator == auth()->user()->id) readonly @endif
                                                            onclick="document.getElementById('criteria-fileInput-{{ $criterion->id }}').click()"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                            <i class="fas fa-upload"></i>
                                                    @endif
                                                    </button>
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">


                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="px-6 py-4 text-gray-500">Không
                                                    có
                                                    chỉ tiêu nào.
                                                </td>
                                            </tr>
                                        @endforelse
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-gray-500">Không có đầu công việc nào.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                    @endif
                    {{-- end tuần --}}

                    {{-- tháng --}}
                    @if ($monthTask->isNotEmpty())
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table-container" style="margin: 50px 0">
                        <thead class="bg-gray-100">
                            <tr>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mã đầu việc</th>
                                    <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tổ chức</th>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tên đầu việc</th>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kết quả</th>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tiến độ</th>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Đánh giá tiến độ</th>

                                <th colspan="3"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tháng {{ $timeParamsMonth['two_previous'] }}</th>
                                <th colspan="3"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tháng {{ $timeParamsMonth['previous'] }}</th>
                                <th colspan="3"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tháng {{ $timeParamsMonth['current'] }}</th>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thao tác</th>
                            </tr>
                            <tr>

                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kết quả
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mô tả
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tài liệu
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kết quả
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mô tả
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tài liệu
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kết quả
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mô tả
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tài liệu
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($monthTask as $task)
                                @php
                                    $isDisabled = $task->status == 'Đã giao việc';
                                    $isStatus = $task->status == 'Đã hoàn thành chu kỳ';
                                @endphp
                                <tr>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        <input required type="hidden" id="typeCurrent" name="typeCurrent[]"
                                            value="2">

                                        <input required type="hidden" id="numberCurrent" name="numberCurrent[]"
                                            value="{{ $timeParamsMonth['current'] }}">
                                        <input required type="hidden" id="document_id" name="document_id[]"
                                            value="{{ $document->id }}">
                                        <input required type="hidden" id="task_code" name="task_code[]"
                                            value="{{ $task->task_code }}">
                                        <input required type="hidden" id="taskId" name="task_id[]"
                                            value="{{ $task->id }}">
                                        <button type="button" class="flex items-center text-blue-500 hover:underline"
                                            onclick="toggleRow('{{ $task->id }}')">
                                            <svg class="w-5 h-5 mr-2 {{ $task->criteriasTask->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                            {{ $task->task_code }}
                                        </button>
                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"> 
                                        <span class="text-gray-900 w-2/3">{{ $task->organization->name??'Chưa giao việc' }}</span>
                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        <input required type="hidden" id="task_code"
                                            
                                            value="{{ $task->task_code }}" class="task-input required"
                                            data-id="{{ $task->id }}">
                                            @if ($isDisabled)
                                            <span class="rounded-lg">{{ $task->task_name }}</span>
                                           @else
                                           <input required type="text" name="task_name[]"
                                            
                                            value="{{ $task->task_name }}" class="task-input required">
                                           @endif   
                                       
                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                       
                                        @if ($isDisabled)
                                        <span class="rounded-lg">{{ $task->required_result }}</span>
                                       @else
                                            <input required
                                            type="text" name="required_result[]" id="required_result"
                                            value="{{ $task->required_result }}"
                                            
                                            placeholder="Nhập đánh giá">
                                       @endif  
                                       </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        @if ($isDisabled)
                                        <span class="rounded-lg">{{ $task->progress }}</span>
                                       @else
                                       <input required
                                       type="text" name="task_progress[]" id="task_progress"
                                       value="{{ $task->progress }}" readonly>
                                       @endif  
                                      
                                        </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        @if ($isDisabled)
                                        <span class="rounded-lg">{{ $task->getStatus() }}</span>
                                       @else
                                       <input
                                            type="text" name="progress_evaluation[]" id="progress_evaluation"
                                            value="{{ $task->getStatus() }}" readonly>
                                       @endif  
                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        {{ $task->taskResultsByNumber($timeParamsMonth['two_previous'], $task->reporting_cycle)->result ?? '' }}
                                    </td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        {{ $task->taskResultsByNumber($timeParamsMonth['two_previous'], $task->reporting_cycle)->description ?? '' }}
                                    </td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        @php
                                            $file =
                                                $task->getFilePathByType(
                                                    $timeParamsMonth['two_previous'],
                                                    $task->reporting_cycle,
                                                ) ?? null;
                                        @endphp

                                        @if ($file && !empty($file->file_path))
                                            <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                download>
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <span class="text-red-500"></span>
                                        @endif
                                        {{-- <button type="button" onclick="downloadFile('{{ $file->file_name }}')"
                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                            <i class="fas fa-download"></i>
                                        </button> --}}
                                    </td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        {{ $task->taskResultsByNumber($timeParamsMonth['previous'], $task->reporting_cycle)->result ?? '' }}
                                    </td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        {{ $task->taskResultsByNumber($timeParamsMonth['previous'], $task->reporting_cycle)->description ?? '' }}
                                    </td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        @php
                                            $file =
                                                $task->getFilePathByType(
                                                    $timeParamsMonth['previous'],
                                                    $task->reporting_cycle,
                                                ) ?? null;
                                        @endphp

                                        @if ($file && !empty($file->file_path))
                                            <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1]) }}"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                download>
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <span class="text-red-500"></span>
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                        @if ($document->creator == auth()->user()->id) readonly @endif
                                            type="text" name="task_current_result[]" id="current_result"
                                            value="{{ $task->taskResultsById($task->id, $timeParamsMonth['current'], $task->reporting_cycle)->result ?? '' }}"
                                            placeholder="Nhập kết quả"></td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                        @if ($document->creator == auth()->user()->id) readonly @endif
                                            type="text" name="task_current_note[]" id="current_note"
                                            value="{{ $task->taskResultsById($task->id, $timeParamsMonth['current'], $task->reporting_cycle)->description ?? '' }}"
                                            placeholder="Nhập mô tả"></td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        <input type="file" id="fileInput-{{ $task->id }}"
                                            name="file_{{ $task->id }}" style="display: none;"
                                            onchange="uploadFile('{{ $task->id }}', 1)">
                                        @php
                                            $file = $task->getFilePath($task->id) ?? null;
                                            //dd($filePath->file_path);
                                        @endphp
                                        @if ($file && !empty($file->file_path))
                                            <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1]) }}"
                                                @if ($document->creator == auth()->user()->id) readonly @endif
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                download>
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <button type="button" id="button-file-task-{{ $task->id }}"
                                                @if ($document->creator == auth()->user()->id) readonly @endif
                                                onclick="document.getElementById('fileInput-{{ $task->id }}').click()"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        @endif


                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        @if ($document->creator == auth()->user()->id)
                                            @if ($task->status == 'Đã giao việc')
                                                <button data-document-id="{{ $document->id }}"
                                                    data-status = "{{ $task->status }}"
                                                    data-task-code="{{ $task->task_code }}"
                                                    data-task-id="{{ $task->id }}"
                                                    @if ($document->creator == auth()->user()->id) readonly @endif
                                                    class="assign-task open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                    <i class="fa fa-tasks"></i>
                                                </button>
                                            @else
                                                <button data-document-id="{{ $document->id }}"
                                                    data-status = "{{ $task->status }}"
                                                    data-task-code="{{ $task->task_code }}"
                                                    data-task-id="{{ $task->id }}"
                                                    @if ($document->creator == auth()->user()->id) readonly @endif
                                                    class="open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                    <i class="fa fa-tasks"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @if ($task->criteriasTask->count() > 0)
                                    @forelse ($task->criteriasTask as $index => $criterion)
                                        <tr id="criteria-row-{{ $task->id }}" class="hidden">
                                            <td class=" px-6 py-4 whitespace-nowrap">
                                                <input required type="hidden" id="typeCurrent"
                                                    name="criteriaTypeCurrent[]" value="2">

                                                <input required type="hidden" id="numberCurrent"
                                                    name="criteriaNumberCurrent[]"
                                                    value="{{ $timeParamsMonth['current'] }}">
                                                <input required type="hidden" id="document_id" name="document_id[]"
                                                    value="{{ $document->id }}">
                                                <input required type="hidden" id="criteria_code"
                                                    name="criteria_code[]" value="{{ $criterion->CriteriaCode }}">
                                                <input required type="hidden" id="criteriaId" name="criteria_id[]"
                                                    value="{{ $criterion->id }}">
                                                <button type="button"
                                                    class="flex items-center text-blue-500 hover:underline">
                                                    <svg hidden
                                                        class="w-5 h-5 mr-2 {{ $task->criteriasTask->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                    {{ $index + 1 }}
                                                    {{-- {{ $criterion->CriteriaCode }} --}}
                                                </button>
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                <span class="text-gray-900 w-2/3">{{ $task->organization->name??'Chưa giao việc' }}</span>
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                <input required type="hidden" id="criteria_code"
                                                    
                                                    value="{{ $criterion->CriteriaCode }}"
                                                    class="criteria-input required" data-id="{{ $criterion->id }}">
                                                    @if ($isDisabled)
                                                        <span class="rounded-lg">{{ $criterion->CriteriaName }}</span>
                                                   @else
                                                        <input required type="text" name="criteria_name[]"
                                                        
                                                        value="{{ $criterion->CriteriaName }}"
                                                        class="criteria-input required">
                                                   @endif  
                        
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                @if ($isDisabled)
                                                <span class="rounded-lg">{{ $criterion->RequestResult }}</span>
                                               @else
                                               <input required type="text" name="criteria_required_result[]"
                                                    id="required_result" value="{{ $criterion->RequestResult }}"
                                                    
                                                    placeholder="Nhập đánh giá">
                                               @endif  
                                              
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                @if ($isDisabled)
                                                <span class="rounded-lg">{{ $criterion->progress }}</span>
                                               @else
                                               <input required type="text" name="criterion_progress[]"
                                               id="criterion_progress" value="{{ $criterion->progress }}"
                                               readonly>
                                               @endif  
                                              
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                @if ($isDisabled)
                                                <span class="rounded-lg">{{ $criterion->getStatus() }}</span>
                                               @else
                                               <input type="text" name="criteria_progress_evaluation[]"
                                               id="progress_evaluation" value="{{ $criterion->getStatus() }}"
                                               readonly>
                                               @endif 
                                   
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                {{ $criterion->taskResultsByNumber($timeParamsMonth['two_previous'], $task->reporting_cycle)->result ?? '' }}
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                {{ $criterion->taskResultsByNumber($timeParamsMonth['two_previous'], $task->reporting_cycle)->description ?? '' }}
                                            </td>
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $file =
                                                        $criterion->getFilePathByType(
                                                            $timeParamsMonth['two_previous'],
                                                            $task->reporting_cycle,
                                                        ) ?? null;
                                                @endphp

                                                @if ($file && !empty($file->file_path))
                                                    <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                        class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                        download>
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <span class="text-red-500"></span>
                                                @endif
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                {{ $criterion->taskResultsByNumber($timeParamsMonth['previous'], $task->reporting_cycle)->result ?? '' }}
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                {{ $criterion->taskResultsByNumber($timeParamsMonth['previous'], $task->reporting_cycle)->description ?? '' }}
                                            </td>
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $file =
                                                        $criterion->getFilePathByType(
                                                            $timeParamsMonth['previous'],
                                                            $task->reporting_cycle,
                                                        ) ?? null;
                                                @endphp

                                                @if ($file && !empty($file->file_path))
                                                    <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                        class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                        download>
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <span class="text-red-500"></span>
                                                @endif
                                            </td>

                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input
                                                @if ($document->creator == auth()->user()->id) readonly @endif
                                                    required type="text" name="criteria_current_result[]"
                                                    id="current_result"
                                                    value="{{ $criterion->taskResultsById($criterion->id, $timeParamsMonth['current'], $task->reporting_cycle)->result ?? '' }}
"
                                                    placeholder="Nhập kết quả">
                                            </td>

                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input
                                                @if ($document->creator == auth()->user()->id) readonly @endif
                                                    required type="text" name="criteria_current_note[]"
                                                    id="current_note"
                                                    value="{{ $criterion->taskResultsById($criterion->id, $timeParamsMonth['current'], $task->reporting_cycle)->description ?? '' }}
"
                                                    placeholder="Nhập mô tả">
                                            </td>

                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                <input type="file" id="criteria-fileInput-{{ $criterion->id }}"
                                                    name="criteria_file_{{ $criterion->id }}" style="display: none;"
                                                    onchange="uploadFile('{{ $criterion->id }}', 2)">

                                                @php
                                                    $file = $criterion->getFilePath($criterion->id) ?? null;
                                                    //dd($filePath->file_path);
                                                @endphp
                                                @if ($file && !empty($file->file_path))
                                                    <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                        @if ($document->creator == auth()->user()->id) readonly @endif
                                                        class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                        download>
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <button type="button"
                                                        id="button-file-criteria-{{ $criterion->id }}"
                                                        onclick="document.getElementById('criteria-fileInput-{{ $criterion->id }}').click()"
                                                        @if ($document->creator == auth()->user()->id) readonly @endif
                                                        class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                        <i class="fas fa-upload"></i>
                                                @endif
                                                </button>
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">


                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-6 py-4 text-gray-500">Không
                                                có
                                                chỉ tiêu nào.
                                            </td>
                                        </tr>
                                    @endforelse
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-gray-500">Không có đầu công việc nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
                    {{-- end tháng --}}

                    {{-- quý --}}
                    @if ($quarterTask->isNotEmpty())
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table-container" style="margin: 0 0 50px 0 ">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mã đầu việc</th>
                                        
                                    <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tổ chức</th>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tên đầu việc</th>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kết quả</th>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tiến độ</th>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Đánh giá tiến độ</th>

                                    <th colspan="3"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quý {{ $timeParamsQuarter['two_previous'] }}</th>
                                    <th colspan="3"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quý {{ $timeParamsQuarter['previous'] }}</th>
                                    <th colspan="3"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quý {{ $timeParamsQuarter['current'] }}</th>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Thao tác</th>
                                </tr>
                                <tr>

                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kết quả
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mô tả
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tài liệu
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kết quả
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mô tả
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tài liệu
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kết quả
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mô tả
                                    </th>
                                    <th
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tài liệu
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($quarterTask as $task)
                                    @php
                                        $isDisabled = $task->status == 'Đã giao việc';
                                        $isStatus = $task->status == 'Đã hoàn thành chu kỳ';
                                    @endphp
                                    <tr>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <input required type="hidden" id="typeCurrent" name="typeCurrent[]"
                                                value="3">

                                            <input required type="hidden" id="numberCurrent" name="numberCurrent[]"
                                                value="{{ $timeParamsQuarter['current'] }}">
                                            <input required type="hidden" id="document_id" name="document_id[]"
                                                value="{{ $document->id }}">
                                            <input required type="hidden" id="task_code" name="task_code[]"
                                                value="{{ $task->task_code }}">
                                            <input required type="hidden" id="taskId" name="task_id[]"
                                                value="{{ $task->id }}">
                                            <button type="button" class="flex items-center text-blue-500 hover:underline"
                                                onclick="toggleRow('{{ $task->id }}')">
                                                <svg class="w-5 h-5 mr-2 {{ $task->criteriasTask->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                                {{ $task->task_code }}
                                            </button>
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        
                        
                                            <span class="text-gray-900 w-2/3">{{ $task->organization->name??'Chưa giao việc' }}</span>

                              </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <input required type="hidden" id="task_code"
                                                
                                                value="{{ $task->task_code }}" class="task-input required"
                                                data-id="{{ $task->id }}">
                                            <input required type="text" name="task_name[]"
                                                
                                                value="{{ $task->task_name }}" class="task-input required">
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="required_result[]" id="required_result"
                                                value="{{ $task->required_result }}"
                                                
                                                placeholder="Nhập đánh giá"></td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="task_progress[]" id="task_progress"
                                                value="{{ $task->progress }}" readonly></td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input
                                                type="text" name="progress_evaluation[]" id="progress_evaluation"
                                                value="{{ $task->getStatus() }}" readonly>
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->taskResultsByNumber($timeParamsQuarter['two_previous'], $task->reporting_cycle)->result ?? '' }}
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->taskResultsByNumber($timeParamsQuarter['two_previous'], $task->reporting_cycle)->description ?? '' }}
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @php
                                                $file =
                                                    $task->getFilePathByType(
                                                        $timeParamsQuarter['two_previous'],
                                                        $task->reporting_cycle,
                                                    ) ?? null;
                                            @endphp

                                            @if ($file && !empty($file->file_path))
                                                <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1]) }}"
                                                    class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                    download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @else
                                                <span class="text-red-500"></span>
                                            @endif
                                            {{-- <button type="button" onclick="downloadFile('{{ $file->file_name }}')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button> --}}
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->taskResultsByNumber($timeParamsQuarter['previous'], $task->reporting_cycle)->result ?? '' }}
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->taskResultsByNumber($timeParamsQuarter['previous'], $task->reporting_cycle)->description ?? '' }}
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @php
                                                $file =
                                                    $task->getFilePathByType(
                                                        $timeParamsQuarter['previous'],
                                                        $task->reporting_cycle,
                                                    ) ?? null;
                                            @endphp

                                            @if ($file && !empty($file->file_path))
                                                <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1]) }}"
                                                    class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                    download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @else
                                                <span class="text-red-500"></span>
                                            @endif
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                            @if ($document->creator == auth()->user()->id) readonly @endif
                                                type="text" name="task_current_result[]" id="current_result"
                                                value="{{ $task->taskResultsById($task->id, $timeParamsQuarter['current'], $task->reporting_cycle)->result ?? '' }}"
                                                placeholder="Nhập kết quả"></td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                            @if ($document->creator == auth()->user()->id) readonly @endif
                                                type="text" name="task_current_note[]" id="current_note"
                                                value="{{ $task->taskResultsById($task->id, $timeParamsQuarter['current'], $task->reporting_cycle)->description ?? '' }}"
                                                placeholder="Nhập mô tả"></td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <input type="file" id="fileInput-{{ $task->id }}"
                                                name="file_{{ $task->id }}" style="display: none;"
                                                onchange="uploadFile('{{ $task->id }}', 1)">
                                            @php
                                                $file = $task->getFilePath($task->id) ?? null;
                                                //dd($filePath->file_path);
                                            @endphp
                                            @if ($file && !empty($file->file_path))
                                                <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1]) }}"
                                                    @if ($document->creator == auth()->user()->id) readonly @endif
                                                    class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                    download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @else
                                                <button type="button" id="button-file-task-{{ $task->id }}"
                                                    onclick="document.getElementById('fileInput-{{ $task->id }}').click()"
                                                    @if ($document->creator == auth()->user()->id) readonly @endif
                                                    class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                    <i class="fas fa-upload"></i>
                                                </button>
                                            @endif


                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @if ($document->creator == auth()->user()->id)
                                                @if ($task->status == 'Đã giao việc')
                                                    <button data-document-id="{{ $document->id }}"
                                                        data-status = "{{ $task->status }}"
                                                        data-task-code="{{ $task->task_code }}"
                                                        data-task-id="{{ $task->id }}"
                                                        class="assign-task open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                        <i class="fa fa-tasks"></i>
                                                    </button>
                                                @else
                                                    <button data-document-id="{{ $document->id }}"
                                                        data-status = "{{ $task->status }}"
                                                        data-task-code="{{ $task->task_code }}"
                                                        data-task-id="{{ $task->id }}"
                                                        class="open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                        <i class="fa fa-tasks"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($task->criteriasTask->count() > 0)
                                        @forelse ($task->criteriasTask as $index => $criterion)
                                            <tr id="criteria-row-{{ $task->id }}" class="hidden">
                                                <td class=" px-6 py-4 whitespace-nowrap">
                                                    <input required type="hidden" id="typeCurrent"
                                                        name="criteriaTypeCurrent[]" value="3">

                                                    <input required type="hidden" id="numberCurrent"
                                                        name="criteriaNumberCurrent[]"
                                                        value="{{ $timeParamsQuarter['current'] }}">
                                                    <input required type="hidden" id="document_id" name="document_id[]"
                                                        value="{{ $document->id }}">
                                                    <input required type="hidden" id="criteria_code"
                                                        name="criteria_code[]" value="{{ $criterion->CriteriaCode }}">
                                                    <input required type="hidden" id="criteriaId" name="criteria_id[]"
                                                        value="{{ $criterion->id }}">
                                                    <button type="button"
                                                        class="flex items-center text-blue-500 hover:underline">
                                                        <svg hidden
                                                            class="w-5 h-5 mr-2 {{ $task->criteriasTask->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                        {{ $index + 1 }}
                                                        {{-- {{ $criterion->CriteriaCode }} --}}
                                                    </button>
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        
                        
                                                    <span class="text-gray-900 w-2/3">{{ $task->organization->name??'Chưa giao việc' }}</span>
  
                                      </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    <input required type="hidden" id="criteria_code"
                                                        
                                                        value="{{ $criterion->CriteriaCode }}"
                                                        class="criteria-input required" data-id="{{ $criterion->id }}">
                                                        
                                                        value="{{ $criterion->CriteriaCode }}"
                                                        class="criteria-input required" data-id="{{ $criterion->id }}">
                                                        @if ($isDisabled)
                                                            <span class="rounded-lg">{{ $criterion->CriteriaName }}</span>
                                                       @else
                                                            <input required type="text" name="criteria_name[]"
                                                            
                                                            value="{{ $criterion->CriteriaName }}"
                                                            class="criteria-input required">
                                                       @endif  
                                            
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    @if ($isDisabled)
                                                        <span class="rounded-lg">{{ $criterion->RequestResult }}</span>
                                                    @else
                                                            <input required type="text" name="criteria_required_result[]"
                                                            id="required_result" value="{{ $criterion->RequestResult }}"
                                                            
                                                            placeholder="Nhập đánh giá">
                                                    @endif  
                                                    
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    @if ($isDisabled)
                                                        <span class="rounded-lg">{{ $criterion->progress }}</span>
                                                    @else
                                                        <input required type="text" name="criterion_progress[]"
                                                        id="criterion_progress" value="{{ $criterion->progress }}"
                                                        readonly>
                                                    @endif  
                                                   
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    @if ($isDisabled)
                                                        <span class="rounded-lg">{{ $criterion->getStatus() }}</span>
                                                    @else
                                                        <input type="text" name="criteria_progress_evaluation[]"
                                                        id="progress_evaluation" value="{{ $criterion->getStatus() }}"
                                                        readonly>
                                                    @endif  
                                                    
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    {{ $criterion->taskResultsByNumber($timeParamsQuarter['two_previous'], $task->reporting_cycle)->result ?? '' }}
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    {{ $criterion->taskResultsByNumber($timeParamsQuarter['two_previous'], $task->reporting_cycle)->description ?? '' }}
                                                </td>
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $file =
                                                            $criterion->getFilePathByType(
                                                                $timeParamsQuarter['two_previous'],
                                                                $task->reporting_cycle,
                                                            ) ?? null;
                                                    @endphp

                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    {{ $criterion->taskResultsByNumber($timeParamsQuarter['previous'], $task->reporting_cycle)->result ?? '' }}
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    {{ $criterion->taskResultsByNumber($timeParamsQuarter['previous'], $task->reporting_cycle)->description ?? '' }}
                                                </td>
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $file =
                                                            $criterion->getFilePathByType(
                                                                $timeParamsQuarter['previous'],
                                                                $task->reporting_cycle,
                                                            ) ?? null;
                                                    @endphp

                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-red-500"></span>
                                                    @endif
                                                </td>

                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input
                                                    @if ($document->creator == auth()->user()->id) readonly @endif
                                                        required type="text" name="criteria_current_result[]"
                                                        id="current_result"
                                                        value="{{ $criterion->taskResultsById($criterion->id, $timeParamsQuarter['current'], $task->reporting_cycle)->result ?? '' }}
"
                                                        placeholder="Nhập kết quả">
                                                </td>

                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input
                                                    @if ($document->creator == auth()->user()->id) readonly @endif
                                                        required type="text" name="criteria_current_note[]"
                                                        id="current_note"
                                                        value="{{ $criterion->taskResultsById($criterion->id, $timeParamsQuarter['current'], $task->reporting_cycle)->description ?? '' }}
"
                                                        placeholder="Nhập mô tả">
                                                </td>

                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                    <input type="file" id="criteria-fileInput-{{ $criterion->id }}"
                                                        name="criteria_file_{{ $criterion->id }}" style="display: none;"
                                                        onchange="uploadFile('{{ $criterion->id }}', 2)">

                                                    @php
                                                        $file = $criterion->getFilePath($criterion->id) ?? null;
                                                        //dd($filePath->file_path);
                                                    @endphp
                                                    @if ($file && !empty($file->file_path))
                                                        <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                            @if ($document->creator == auth()->user()->id) readonly @endif
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @else
                                                        <button type="button"
                                                            id="button-file-criteria-{{ $criterion->id }}"
                                                            onclick="document.getElementById('criteria-fileInput-{{ $criterion->id }}').click()"
                                                            @if ($document->creator == auth()->user()->id) readonly @endif
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                            <i class="fas fa-upload"></i>
                                                    @endif
                                                    </button>
                                                </td>
                                                <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">


                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="px-6 py-4 text-gray-500">Không
                                                    có
                                                    chỉ tiêu nào.
                                                </td>
                                            </tr>
                                        @endforelse
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-gray-500">Không có đầu công việc nào.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                    {{-- end quý --}}

                    {{-- năm  --}}
                    @if ($yearTask->isNotEmpty())
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table-container">
                        <thead class="bg-gray-100">
                            <tr>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mã đầu việc</th>
                                    
 <th rowspan="2"
 class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
 Tổ chức</th>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tên đầu việc</th>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kết quả</th>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tiến độ</th>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Đánh giá tiến độ</th>

                                <th colspan="3"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Năm {{ $timeParamsYear['two_previous'] }}</th>
                                <th colspan="3"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Năm {{ $timeParamsYear['previous'] }}</th>
                                <th colspan="3"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Năm {{ $timeParamsYear['current'] }}</th>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thao tác</th>
                            </tr>
                            <tr>

                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kết quả
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mô tả
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tài liệu
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kết quả
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mô tả
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tài liệu
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kết quả
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mô tả
                                </th>
                                <th
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tài liệu
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($yearTask as $task)
                                @php
                                    $isDisabled = $task->status == 'Đã giao việc';
                                    $isStatus = $task->status == 'Đã hoàn thành chu kỳ';
                                @endphp
                                <tr>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        <input required type="hidden" id="typeCurrent" name="typeCurrent[]"
                                            value="4">

                                        <input required type="hidden" id="numberCurrent" name="numberCurrent[]"
                                            value="{{ $timeParamsYear['current'] }}">
                                        <input required type="hidden" id="document_id" name="document_id[]"
                                            value="{{ $document->id }}">
                                        <input required type="hidden" id="task_code" name="task_code[]"
                                            value="{{ $task->task_code }}">
                                        <input required type="hidden" id="taskId" name="task_id[]"
                                            value="{{ $task->id }}">
                                        <button type="button" class="flex items-center text-blue-500 hover:underline"
                                            onclick="toggleRow('{{ $task->id }}')">
                                            <svg class="w-5 h-5 mr-2 {{ $task->criteriasTask->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                            {{ $task->task_code }}
                                        </button>
                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        <span class="text-gray-900 w-2/3">{{ $task->organization->name??'Chưa giao việc' }}</span>
                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        <input required type="hidden" id="task_code"
                                            
                                            value="{{ $task->task_code }}" class="task-input required"
                                            data-id="{{ $task->id }}">
                                        @if ($isDisabled)
                                            <span class="rounded-lg">{{ $task->task_name }}</span>
                                        @else
                                        <input required type="text" name="task_name[]"
                                        
                                        value="{{ $task->task_name }}" class="task-input required">
                                        @endif  
                                       
                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        @if ($isDisabled)
                                        <span class="rounded-lg">{{ $task->required_result }}</span>
                                        @else
                                            <input required
                                                type="text" name="required_result[]" id="required_result"
                                                value="{{ $task->required_result }}"
                                                
                                                placeholder="Nhập đánh giá">
                                        @endif  
                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        @if ($isDisabled)
                                        <span class="rounded-lg">{{ $task->progress }}</span>
                                        @else
                                        <input required
                                        type="text" name="task_progress[]" id="task_progress"
                                        value="{{ $task->progress }}" readonly>
                                        @endif  
                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        @if ($isDisabled)
                                        <span class="rounded-lg">{{ $task->getStatus() }}</span>
                                        @else
                                            <input
                                            type="text" name="progress_evaluation[]" id="progress_evaluation"
                                            value="{{ $task->getStatus() }}" readonly>
                                        @endif  
                                       
                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        {{ $task->taskResultsByNumber($timeParamsYear['two_previous'], $task->reporting_cycle)->result ?? '' }}
                                    </td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        {{ $task->taskResultsByNumber($timeParamsYear['two_previous'], $task->reporting_cycle)->description ?? '' }}
                                    </td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        @php
                                            $file =
                                                $task->getFilePathByType(
                                                    $timeParamsYear['two_previous'],
                                                    $task->reporting_cycle,
                                                ) ?? null;
                                        @endphp

                                        @if ($file && !empty($file->file_path))
                                            <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1]) }}"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                download>
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <span class="text-red-500"></span>
                                        @endif
                                        {{-- <button type="button" onclick="downloadFile('{{ $file->file_name }}')"
                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                            <i class="fas fa-download"></i>
                                        </button> --}}
                                    </td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        {{ $task->taskResultsByNumber($timeParamsYear['previous'], $task->reporting_cycle)->result ?? '' }}
                                    </td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        {{ $task->taskResultsByNumber($timeParamsYear['previous'], $task->reporting_cycle)->description ?? '' }}
                                    </td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        @php
                                            $file =
                                                $task->getFilePathByType(
                                                    $timeParamsYear['previous'],
                                                    $task->reporting_cycle,
                                                ) ?? null;
                                        @endphp

                                        @if ($file && !empty($file->file_path))
                                            <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1]) }}"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                download>
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <span class="text-red-500"></span>
                                        @endif
                                    </td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                        @if ($document->creator == auth()->user()->id) readonly @endif
                                            type="text" name="task_current_result[]" id="current_result"
                                            value="{{ $task->taskResultsById($task->id, $timeParamsYear['current'], $task->reporting_cycle)->result ?? '' }}"
                                            placeholder="Nhập kết quả"></td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                        @if ($document->creator == auth()->user()->id) readonly @endif
                                            type="text" name="task_current_note[]" id="current_note"
                                            value="{{ $task->taskResultsById($task->id, $timeParamsYear['current'], $task->reporting_cycle)->description ?? '' }}"
                                            placeholder="Nhập mô tả"></td>

                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        <input type="file" id="fileInput-{{ $task->id }}"
                                            name="file_{{ $task->id }}" style="display: none;"
                                            onchange="uploadFile('{{ $task->id }}', 1)">
                                        @php
                                            $file = $task->getFilePath($task->id) ?? null;
                                            //dd($filePath->file_path);
                                        @endphp
                                        @if ($file && !empty($file->file_path))
                                            <a href="{{ route('file.download', ['id' => $file->id, 'type' => 1]) }}"
                                                @if ($document->creator == auth()->user()->id) readonly @endif
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                download>
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <button type="button" id="button-file-task-{{ $task->id }}"
                                                onclick="document.getElementById('fileInput-{{ $task->id }}').click()"
                                                @if ($document->creator == auth()->user()->id) readonly @endif
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        @endif


                                    </td>
                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                        @if ($document->creator == auth()->user()->id)
                                            @if ($task->status == 'Đã giao việc')
                                                <button data-document-id="{{ $document->id }}"
                                                    data-status = "{{ $task->status }}"
                                                    data-task-code="{{ $task->task_code }}"
                                                    data-task-id="{{ $task->id }}"
                                                    class="assign-task open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                    <i class="fa fa-tasks"></i>
                                                </button>
                                            @else
                                                <button data-document-id="{{ $document->id }}"
                                                    data-status = "{{ $task->status }}"
                                                    data-task-code="{{ $task->task_code }}"
                                                    data-task-id="{{ $task->id }}"
                                                    class="open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                    <i class="fa fa-tasks"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @if ($task->criteriasTask->count() > 0)
                                    @forelse ($task->criteriasTask as $index => $criterion)
                                        <tr id="criteria-row-{{ $task->id }}" class="hidden">
                                            <td class=" px-6 py-4 whitespace-nowrap">
                                                <input required type="hidden" id="typeCurrent"
                                                    name="criteriaTypeCurrent[]" value="4">

                                                <input required type="hidden" id="numberCurrent"
                                                    name="criteriaNumberCurrent[]"
                                                    value="{{ $timeParamsYear['current'] }}">
                                                <input required type="hidden" id="document_id" name="document_id[]"
                                                    value="{{ $document->id }}">
                                                <input required type="hidden" id="criteria_code"
                                                    name="criteria_code[]" value="{{ $criterion->CriteriaCode }}">
                                                <input required type="hidden" id="criteriaId" name="criteria_id[]"
                                                    value="{{ $criterion->id }}">
                                                <button type="button"
                                                    class="flex items-center text-blue-500 hover:underline">
                                                    <svg hidden
                                                        class="w-5 h-5 mr-2 {{ $task->criteriasTask->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                    {{ $index + 1 }}
                                                    {{-- {{ $criterion->CriteriaCode }} --}}
                                                </button>
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                <span class="text-gray-900 w-2/3">{{ $task->organization->name??'Chưa giao việc' }}</span>
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                <input required type="hidden" id="criteria_code"
                                                    
                                                    value="{{ $criterion->CriteriaCode }}"
                                                    class="criteria-input required" data-id="{{ $criterion->id }}">

                                                    @if ($isDisabled)
                                                    <span class="rounded-lg">{{ $criterion->CriteriaName }}</span>
                                                    @else
                                                    <input required type="text" name="criteria_name[]"
                                                    
                                                    value="{{ $criterion->CriteriaName }}"
                                                    class="criteria-input required">
                                                    @endif  
                                               
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                @if ($isDisabled)
                                                <span class="rounded-lg">{{ $criterion->RequestResult }}</span>
                                                @else
                                                <input required type="text" name="criteria_required_result[]"
                                                id="required_result" value="{{ $criterion->RequestResult }}"
                                                
                                                placeholder="Nhập đánh giá">
                                                @endif  
                                                
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                @if ($isDisabled)
                                                <span class="rounded-lg">{{ $criterion->progress }}</span>
                                                @else
                                                <input required type="text" name="criterion_progress[]"
                                                    id="criterion_progress" value="{{ $criterion->progress }}"
                                                    readonly>
                                                @endif  
                                              
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                @if ($isDisabled)
                                                    <span class="rounded-lg">{{ $criterion->getStatus() }}</span>
                                                @else
                                                    <input type="text" name="criteria_progress_evaluation[]"
                                                    id="progress_evaluation" value="{{ $criterion->getStatus() }}"
                                                    readonly>
                                                @endif 
                                              
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                {{ $criterion->taskResultsByNumber($timeParamsYear['two_previous'], $task->reporting_cycle)->result ?? '' }}
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                {{ $criterion->taskResultsByNumber($timeParamsYear['two_previous'], $task->reporting_cycle)->description ?? '' }}
                                            </td>
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $file =
                                                        $criterion->getFilePathByType(
                                                            $timeParamsYear['two_previous'],
                                                            $task->reporting_cycle,
                                                        ) ?? null;
                                                @endphp

                                                @if ($file && !empty($file->file_path))
                                                    <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                        class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                        download>
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <span class="text-red-500"></span>
                                                @endif
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                {{ $criterion->taskResultsByNumber($timeParamsYear['previous'], $task->reporting_cycle)->result ?? '' }}
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                {{ $criterion->taskResultsByNumber($timeParamsYear['previous'], $task->reporting_cycle)->description ?? '' }}
                                            </td>
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $file =
                                                        $criterion->getFilePathByType(
                                                            $timeParamsYear['previous'],
                                                            $task->reporting_cycle,
                                                        ) ?? null;
                                                @endphp

                                                @if ($file && !empty($file->file_path))
                                                    <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                        class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                        download>
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <span class="text-red-500"></span>
                                                @endif
                                            </td>

                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input
                                                @if ($document->creator == auth()->user()->id) readonly @endif
                                                    required type="text" name="criteria_current_result[]"
                                                    id="current_result"
                                                    value="{{ $criterion->taskResultsById($criterion->id, $timeParamsYear['current'], $task->reporting_cycle)->result ?? '' }}
"
                                                    placeholder="Nhập kết quả">
                                            </td>

                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input
                                                @if ($document->creator == auth()->user()->id) readonly @endif
                                                    required type="text" name="criteria_current_note[]"
                                                    id="current_note"
                                                    value="{{ $criterion->taskResultsById($criterion->id, $timeParamsYear['current'], $task->reporting_cycle)->description ?? '' }}
"
                                                    placeholder="Nhập mô tả">
                                            </td>

                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                <input type="file" id="criteria-fileInput-{{ $criterion->id }}"
                                                    name="criteria_file_{{ $criterion->id }}" style="display: none;"
                                                    onchange="uploadFile('{{ $criterion->id }}', 2)">

                                                @php
                                                    $file = $criterion->getFilePath($criterion->id) ?? null;
                                                    //dd($filePath->file_path);
                                                @endphp
                                                @if ($file && !empty($file->file_path))
                                                    <a href="{{ route('file.download', ['id' => $file->id, 'type' => 2]) }}"
                                                        class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                        download>
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <button type="button"
                                                        id="button-file-criteria-{{ $criterion->id }}"
                                                        onclick="document.getElementById('criteria-fileInput-{{ $criterion->id }}').click()"
                                                        class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                        <i class="fas fa-upload"></i>
                                                @endif
                                                </button>
                                            </td>
                                            <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">


                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-6 py-4 text-gray-500">Không
                                                có
                                                chỉ tiêu nào.
                                            </td>
                                        </tr>
                                    @endforelse
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-gray-500">Không có đầu công việc nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
                    {{-- end năm --}}


                </div>
            </div>
            <div class="mb-4">
                <button type="submit" id="save-button"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 mt-4">Lưu
                </button>
            </div>
        </form>
        </div>
     </div>
    </div>
     {{-- Giao việc  --}}
 <div id="assign-organizations-modal" style="z-index: 9999;" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-3/4">
        <h2 class="text-xl font-bold mb-4">Danh sách chỉ tiêu</h2>

        <!-- Phần chọn bộ lọc -->
        <div class="mb-4">
            <div class="flex space-x-4">
                <div class="flex items-center">
                    <input type="radio" id="unit-filter" name="filter" class="filter-radio" value="unit-filter"> 
                    <label for="unit-filter" class="text-gray-700 text-sm font-medium ml-2">Các đơn vị trực thuộc</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="all-provinces-filter" name="filter" class="filter-radio" value="all-provinces-filter">
                    <label for="all-provinces-filter" class="text-gray-700 text-sm font-medium ml-2">Tất cả các tỉnh</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="all-units-filter" name="filter" class="filter-radio" value="all-units-filter">
                    <label for="all-units-filter" class="text-gray-700 text-sm font-medium ml-2">Tất cả các bộ</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="other-filter" name="filter" class="filter-radio" value="other-filter">
                    <label for="other-filter" class="text-gray-700 text-sm font-medium ml-2">Khác</label>
                </div>
            </div>
        </div>
        

        <!-- Phần tìm kiếm chỉ tiêu -->
        <div class="mb-4">
            <input type="text" id="search-organizations" class="form-input w-full border border-gray-300 rounded-lg p-2" placeholder="Tìm kiếm cơ quan/tổ chức">
        </div>

        <!-- Bảng danh sách chỉ tiêu -->
        <div class="mb-4 overflow-x-auto" style="
            max-height: 400px;
            overflow-y: auto;
            overflow-x: auto;
            text-align: center;">
            <table id="existing-organizations-table" class="w-full border border-gray-300 rounded-lg">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b checkbox-column">
                            <input type="checkbox" id="check-all-organizations">
                            <label for="check-all-organization" class="text-gray-700 text-sm font-medium"></label>
                        </th>
                        <th class="py-2 px-4 border-b">Mã Đầu việc</th>
                        <th class="py-2 px-4 border-b">Mã Cơ quan/tổ chức</th>
                        <th class="py-2 px-4 border-b">Tên Cơ quan/Tổ chức</th>
                        <th class="py-2 px-4 border-b">Email</th>
                        <th class="py-2 px-4 border-b">Số điện thoại</th>
                    </tr>
                </thead>
                <tbody id="existing-organizations" style="text-align: center">
                    <!-- Danh sách chỉ tiêu sẽ được chèn vào đây bằng JavaScript -->
                </tbody>
            </table>
        </div>

        <button type="button" id="assign-organizations-save" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Gán</button>
        <button type="button" id="cancel-organizations-criteria" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">Hủy</button>
    </div>
    <div id="assigned-organizations-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-3/4">
            <h2 class="text-xl font-bold mb-4">Danh sách Cơ Quan/Tổ Chức đã giao việc</h2>
            <div class="mb-4 overflow-x-auto">
                <table id="assigned-organizations-table" class="w-full border border-gray-300 rounded-lg">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Mã Cơ Quan/Tổ Chức</th>
                            <th class="py-2 px-4 border-b">Tên Cơ Quan/Tổ Chức</th>
                            <th class="py-2 px-4 border-b">Email</th>
                            <th class="py-2 px-4 border-b">Số điện thoại</th>
                        </tr>
                    </thead>
                    <tbody id="assigned-organizations-body" style="text-align: center">
                        <!-- Danh sách sẽ được chèn vào đây bằng JavaScript -->
                    </tbody>
                </table>
            </div>
            <button type="button" id="close-assigned-organizations-modal"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">
                Đóng
            </button>
        </div>
    </div>
    <script>
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
                            alert("Upload file thành công " + data.file_path);

                            // if(data.type == "1"){
                            //     var downloadLink = document.getElementById('downloadLink-' + id);
                            //     // Cập nhật liên kết tải xuống
                            //     var fileUrl = '{{ asset('storage') }}/' + data.file_path;
                            //     downloadLink.href = fileUrl;
                            //     downloadLink.style.display = 'inline'; // Hiển thị nút tải xuống
                            //     document.getElementById('button-file-task-'+ id).style.display = 'none';
                            // }else{
                            //     var downloadLink = document.getElementById('downloadLink-criteria-' + id);
                            //     // Cập nhật liên kết tải xuống
                            //     var fileUrl = '{{ asset('storage') }}/' + data.file_path;
                            //     downloadLink.href = fileUrl;
                            //     downloadLink.style.display = 'inline'; // Hiển thị nút tải xuống
                            //     document.getElementById('button-file-criteria-'+ id).style.display = 'none';
                            // }


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


            // document.getElementById('save-button').addEventListener('click', function(event) {
            //     event.preventDefault(); // Ngăn chặn hành vi mặc định của nút
            //     // Thực hiện các hành động tùy ý (ví dụ: gửi AJAX request)
            //     alert('Dữ liệu đã được lưu thành công!');
            // });

            // Định nghĩa hàm để lấy URL icon dựa vào loại tệp
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

            // Cập nhật danh sách tệp hiển thị
            function updateFileList() {
                fileList.innerHTML = '';
                const files = fileInput.files;

                Array.from(files).forEach((file, index) => {
                    // Kiểm tra kích thước file
                    if (file.size > 2 * 1024 * 1024) {
                        alert(`${file.name} vượt quá kích thước 2MB và sẽ không được thêm vào danh sách.`);
                        return;
                    }

                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item flex items-center mb-2';

                    const fileIcon = document.createElement('img');
                    fileIcon.src = getFileIcon(file.type);
                    fileIcon.className = 'file-icon w-12 h-12 mr-2';
                    fileItem.appendChild(fileIcon);

                    const fileName = document.createElement('span');
                    fileName.className = 'text-gray-700';
                    fileName.textContent = file.name;
                    fileItem.appendChild(fileName);

                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.className = 'remove-button ml-2 bg-red-500 text-white px-2 py-1 rounded';
                    removeButton.textContent = '×';
                    removeButton.addEventListener('click', () => removeFile(index));
                    fileItem.appendChild(removeButton);

                    fileList.appendChild(fileItem);
                });
            }

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

            fileInput.addEventListener('change', updateFileList);

            // Xử lý sự kiện xóa tệp cũ
            document.querySelectorAll('.remove-file-button').forEach(button => {
                button.addEventListener('click', () => {
                    const fileId = button.closest('.file-item').dataset.fileId;
                    removeOldFile(fileId);
                });
            });

            // Cập nhật danh sách tệp khi trang được tải
            updateFileList();


            //================================================CÔng việc đã gán==============================
            let documentID;
            let taskCode;
            let taskId;
            document.querySelectorAll('.open-popup-button').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const documentIdRow = this.getAttribute('data-document-id');
                    const taskCodeRow = this.getAttribute('data-task-code');
                    const taskIdRow = this.getAttribute('data-task-id');
                    const status = this.getAttribute('data-status');
                    documentID = documentIdRow;
                    taskCode = taskCodeRow;
                    taskId = taskIdRow;
                    if (status == 'Đã giao việc') {
                        // Gửi yêu cầu AJAX để lấy dữ liệu từ server
                        fetch(
                                `/get-assigned-organizations?documentId=${documentIdRow}&taskCode=${taskCodeRow}&taskId=${taskIdRow}`
                            )
                            .then(response => response.json())
                            .then(data => {
                                const assignedOrganizations = data.organizations;
                                // Chèn dữ liệu vào bảng trong popup
                                const tbody = document.getElementById(
                                    'assigned-organizations-body');
                                tbody.innerHTML = ''; // Xóa dữ liệu cũ
                                assignedOrganizations.forEach(org => {
                                    const row = document.createElement('tr');
                                    row.innerHTML = `
                                            <td class="py-2 px-4 border-b">${org.code}</td>
                                            <td class="py-2 px-4 border-b">${org.name}</td>
                                            <td class="py-2 px-4 border-b">${org.email}</td>
                                            <td class="py-2 px-4 border-b">${org.phone}</td>
                                        `;
                                    tbody.appendChild(row);
                                });

                                // Hiển thị popup
                                document.getElementById('assigned-organizations-modal')
                                    .classList
                                    .remove('hidden');

                            });

                    } else {
                         // Lấy tất cả các input radio với tên 'filter'
                         const radios = document.querySelectorAll('input[name="filter"]');
                        radios.forEach(radio => {
                        radio.checked = false; // Đặt thuộc tính checked thành false
                        });
                        document.getElementById('check-all-organizations').checked = false;
                        document.querySelectorAll('#existing-organizations input.organization-checkbox').forEach(
                        checkbox => {
                            checkbox.checked = false;
                        });
                        const tasksTableBody = document.getElementById('existing-organizations');
                        tasksTableBody.innerHTML = ''; 
                        document.getElementById('assign-organizations-modal').classList.remove('hidden');
                    }

                });
            });

            // Đóng popup
            document.getElementById('close-assigned-organizations-modal').addEventListener('click', function(
                event) {
                event.preventDefault();
                document.getElementById('assigned-organizations-modal').classList.add('hidden');
            });

             /////=============================Giao việc====================================/////


             const organizationsMap = new Map();
                let taskCodeRow;
                let taskIdRow;
                // Hàm để thêm hoặc cập nhật tiêu chí cho một mã công việc
                function addOrganizations(taskCode, criteriaCode) {
                    if (!organizationsMap.has(taskCode)) {
                        organizationsMap.set(taskCode, new Set());
                    }
                    organizationsMap.get(taskCode).add(criteriaCode);
                }

                // Hàm để xóa tiêu chí cho một mã công việc
                function removeOrganizations(taskCode, criteriaCode) {
                    if (organizationsMap.has(taskCode)) {
                        organizationsMap.get(taskCode).delete(criteriaCode);
                        if (organizationsMap.get(taskCode).size === 0) {
                            organizationsMap.delete(taskCode);
                        }
                    }
                }

                // Hàm để lấy danh sách tiêu chí cho một mã công việc
                function getOrganizationsForTask(taskCode) {
                    return organizationsMap.has(taskCode) ? Array.from(organizationsMap.get(taskCode)) : [];
                }

                function hasOrganizations(taskCode, criteriaCode) {
                    console.log("organizationsMap");
                    console.log(organizationsMap);
                    if (!organizationsMap.has(taskCode)) {
                        return false; // Mã công việc không tồn tại trong criteriaMap
                    }

                    const criteriaSet = organizationsMap.get(taskCode);
                    return organizationsMap.has(criteriaCode); // Kiểm tra xem tiêu chí có trong Set không
                }
                const otherFilterCheckbox = document.getElementById('other-filter');
                const searchOtherSection = document.getElementById('search-organizations');
                searchOtherSection.classList.add('hidden');
                // Hiển thị popup chọn đầu việc có sẵn
                // Khi checkbox "Chọn tất cả" thay đổi trạng thái
                document.getElementById('check-all-organizations').addEventListener('change', function(event) {
                    const checked = event.target.checked;
                    document.querySelectorAll('#existing-organizations input.organization-checkbox').forEach(
                        checkbox => {
                            checkbox.checked = checked;
                        });
                });

                const filterRadios = document.querySelectorAll('.filter-radio');
    
                // Thêm sự kiện change cho tất cả các radio button
                filterRadios.forEach(radio => {
                    radio.addEventListener('change', function () {
                        console.log(this.value);
                        handleFilterChange(this.value);
                    });
                });
                
                // Hàm xử lý sự kiện thay đổi radio button
                function handleFilterChange(selectedFilter) {
                   
                    switch (selectedFilter) {
                        case 'unit-filter':
                            searchOtherSection.classList.add('hidden');
                            fetchOrganizationsByParentId();
                            console.log('Chọn: Các đơn vị trực thuộc');
                            // Thực hiện các hành động liên quan
                            break;
                        case 'all-provinces-filter':
                            searchOtherSection.classList.add('hidden');
                            fetchOrganizationsByType('tỉnh');
                            console.log('Chọn: Tất cả các tỉnh');
                            break;
                        case 'all-units-filter':
                            searchOtherSection.classList.add('hidden');
                            fetchOrganizationsByType('bộ');
                            console.log('Chọn: Tất cả các bộ');
                            // Thực hiện các hành động liên quan
                            break;
                        case 'other-filter':
                            // Xử lý khi chọn "Khác"
                            console.log('Chọn: Khác');
                            // Thực hiện các hành động liên quan
                            // Hiển thị ô tìm kiếm
                            searchOtherSection.classList.remove('hidden');
                            break;
                        default:
                            break;
                    }
                }
                searchOtherSection.addEventListener('input', function(event) {
                    const query = event.target.value;
                    fetchOrSearchName(query);
                   // fetchOrganizationsByParentId(query);
                });
                function fetchOrganizationsByType(query = '') {
                    fetch(`{{ route('organization.search.type') }}?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            fillData(data);
                        })
                        .catch(error => console.error('Error fetching tasks:', error));
                }
                function fetchOrganizationsByNameOrCode(query = '') {
                    fetch(`{{ route('organization.search.name') }}?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            fillData(data);
                        })
                        .catch(error => console.error('Error fetching tasks:', error));
                }
                function fetchOrganizationsByParentId(query = '') {
                    fetch(`{{ route('organization.search.parent') }}?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            fillData(data);
                        })
                        .catch(error => console.error('Error fetching tasks:', error));
                }

                function fillData(data) {
                    const tasksTableBody = document.getElementById('existing-organizations');
                            tasksTableBody.innerHTML = ''; // Xóa nội dung hiện tại

                            // Kiểm tra xem có dữ liệu không
                            if (!data.organizations || data.organizations.length === 0) {
                                const row = document.createElement('tr');
                                const cell = document.createElement('td');
                                cell.colSpan = 4; // Chiếm toàn bộ số cột
                                cell.textContent = 'Không có đầu việc nào';
                                row.appendChild(cell);
                                tasksTableBody.appendChild(row);
                                return;
                            }

                            data.organizations.forEach(task => {
                                const code = task.code;
                                var hasCri = hasOrganizations(taskCode, code);
                                console.log("hasOrganizations");
                                console.log(hasCri);
                                if (hasCri) {
                                    return;
                                }
                                const row = document.createElement('tr');

                                // Checkbox
                                const checkboxCell = document.createElement('td');
                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.id = `organization-${task.code}`;
                                checkbox.value = task.code;
                                checkbox.classList.add('organization-checkbox');
                                checkboxCell.appendChild(checkbox);
                                
                                // Tên công việc
                                const taskCodeAssignCell = document.createElement('td');
                                taskCodeAssignCell.textContent = taskCode;
                                taskCodeAssignCell.classList.add('task-code');


                                const taskIdAssignCell = document.createElement('td');
                                taskIdAssignCell.textContent = taskId;
                                taskIdAssignCell.classList.add('task-id');
                                taskIdAssignCell.style.display = 'none';
                                // Mã công việc
                                const codeCell = document.createElement('td');
                                codeCell.textContent = task.code;
                                codeCell.classList.add('organization-code');

                                // Tên công việc
                                const nameCell = document.createElement('td');
                                nameCell.textContent = task.name;
                                nameCell.classList.add('organization-name');
                                // Tên công việc
                                const emailCell = document.createElement('td');
                                emailCell.textContent = task.email;
                                emailCell.classList.add('organization-email');

                                // Tên công việc
                                const phoneCell = document.createElement('td');
                                phoneCell.textContent = task.phone;
                                phoneCell.classList.add('organization-phone');

                                row.appendChild(checkboxCell);
                                row.appendChild(taskCodeAssignCell);
                                row.appendChild(taskIdAssignCell);
                                row.appendChild(codeCell);
                                row.appendChild(nameCell);
                                row.appendChild(emailCell);
                                row.appendChild(phoneCell);


                                tasksTableBody.appendChild(row);
                            });
                }

                function fetchOrSearchName(query = '') {
                    fetch(`{{ route('organization.search.name') }}?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            const tasksTableBody = document.getElementById('existing-organizations');
                            tasksTableBody.innerHTML = ''; // Xóa nội dung hiện tại

                            // Kiểm tra xem có dữ liệu không
                            if (!data.organizations || data.organizations.length === 0) {
                                const row = document.createElement('tr');
                                const cell = document.createElement('td');
                                cell.colSpan = 4; // Chiếm toàn bộ số cột
                                cell.textContent = 'Không có đầu việc nào';
                                row.appendChild(cell);
                                tasksTableBody.appendChild(row);
                                return;
                            }

                            data.organizations.forEach(task => {
                                const code = task.code;
                                var hasCri = hasOrganizations(taskCode, code);
                                console.log("hasOrganizations");
                                console.log(hasCri);
                                if (hasCri) {
                                    return;
                                }
                                const row = document.createElement('tr');

                                // Checkbox
                                const checkboxCell = document.createElement('td');
                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.id = `organization-${task.code}`;
                                checkbox.value = task.code;
                                checkbox.classList.add('organization-checkbox');
                                checkboxCell.appendChild(checkbox);

                                // Tên công việc
                                const taskCodeAssignCell = document.createElement('td');
                                taskCodeAssignCell.textContent = taskCode;
                                taskCodeAssignCell.classList.add('task-code');

                                const taskIdAssignCell = document.createElement('td');
                                taskIdAssignCell.textContent = taskId;
                                taskIdAssignCell.classList.add('task-id');
                                taskIdAssignCell.style.display = 'none';

                                // Mã công việc
                                const codeCell = document.createElement('td');
                                codeCell.textContent = task.code;
                                codeCell.classList.add('organization-code');

                                // Tên công việc
                                const nameCell = document.createElement('td');
                                nameCell.textContent = task.name;
                                nameCell.classList.add('organization-name');

                                // Tên công việc
                                const emailCell = document.createElement('td');
                                emailCell.textContent = task.email;
                                emailCell.classList.add('organization-email');

                                // Tên công việc
                                const phoneCell = document.createElement('td');
                                phoneCell.textContent = task.phone;
                                phoneCell.classList.add('organization-phone');

                                row.appendChild(checkboxCell);
                                row.appendChild(taskCodeAssignCell);
                                row.appendChild(taskIdAssignCell);
                                row.appendChild(codeCell);
                                row.appendChild(nameCell);
                                row.appendChild(emailCell);
                                row.appendChild(phoneCell);
                                // row.appendChild(dataResultCell);


                                tasksTableBody.appendChild(row);
                            });
                        })
                        .catch(error => console.error('Error fetching tasks:', error));
                }


                const organizationsCheckMap = new Map();

                // Hàm để thêm hoặc cập nhật tiêu chí cho một mã công việc
                function addCheckOrganizations(taskCode, criteriaCode) {
                    if (!organizationsCheckMap.has(taskCode)) {
                        organizationsCheckMap.set(taskCode, new Set());
                    }
                    organizationsCheckMap.get(taskCode).add(criteriaCode);
                }

                // Hàm để xóa tiêu chí cho một mã công việc
                function removeCheckOrganizations(taskCode, criteriaCode) {
                    if (organizationsCheckMap.has(taskCode)) {
                        organizationsCheckMap.get(taskCode).delete(criteriaCode);
                        if (organizationsCheckMap.get(taskCode).size === 0) {
                            organizationsCheckMap.delete(taskCode);
                        }
                    }
                }

                // Hàm để lấy danh sách tiêu chí cho một mã công việc
                function getCheckOrganizationsForTask(taskCode) {
                    return organizationsCheckMap.has(taskCode) ? Array.from(organizationsCheckMap.get(taskCode)) : [];
                }

                function hasCheckOrganizations(taskCode, criteriaCode) {
                    console.log("organizationsCheckMap");
                    console.log(organizationsCheckMap);
                    if (!organizationsCheckMap.has(taskCode)) {
                        return false; // Mã công việc không tồn tại trong criteriaMap
                    }

                    const criteriaSet = organizationsCheckMap.get(taskCode);
                    return organizationsCheckMap.has(criteriaCode); // Kiểm tra xem tiêu chí có trong Set không
                }
                document.getElementById('assign-organizations-save').addEventListener('click', function() {
    const organizations = [];
    const selectedCheckboxes = document.querySelectorAll('#existing-organizations input[type="checkbox"]:checked');

    selectedCheckboxes.forEach(checkbox => {
        const row = checkbox.closest('tr'); // Lấy hàng chứa checkbox
        const organizationCode = row.querySelector('.organization-code').textContent;
        const organizationName = row.querySelector('.organization-name').textContent;
        const taskId = row.querySelector('.task-id').textContent;
        const organizationEmail = row.querySelector('.organization-email').textContent;
        const organizationPhone = row.querySelector('.organization-phone').textContent;
        const taskCode = row.querySelector('.task-code').textContent;

        organizations.push({
            code: organizationCode,
            name: organizationName,
            email: organizationEmail,
            phone: organizationPhone,
            task_code: taskCode,
            task_id: taskId
        });
    });

                // Gọi API một lần sau khi thu thập tất cả dữ liệu
                fetch('/save-assign-organizations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ organizations: organizations })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Tổ chức đã được giao thành công!');
                        location.reload(); // Reload trang sau khi thành công
                    } else {
                        alert('Đã xảy ra lỗi!');
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    alert('Đã xảy ra lỗi!');
                });

                // Đóng modal
                document.getElementById('assign-organizations-modal').classList.add('hidden');
            });


                const cancelOrganizationsBtn = document.getElementById('cancel-organizations-criteria');
                const assignOrganizationsModal = document.getElementById('assign-organizations-modal');

                function hideModalOr() {
                    assignOrganizationsModal.classList.add('hidden');
                }

                cancelOrganizationsBtn.addEventListener('click', hideModalOr);


            //============================== Lich su ==========================
            document.querySelectorAll('.history-task').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    alert("sadasdad");
                    const documentIdRow = this.getAttribute('data-document-id');
                    const taskCodeRow = this.getAttribute('data-task-code');
                    const taskIdRow = this.getAttribute('data-task-id');
                    const status = this.getAttribute('data-status');
                    documentID = documentIdRow;
                    taskCode = taskCodeRow;
                    taskId = taskIdRow;
                    console.log(status);
                    if (status == 'Đã giao việc') {
                        // Gửi yêu cầu AJAX để lấy dữ liệu từ server
                        fetch(
                                `/get-assigned-organizations?documentId=${documentIdRow}&taskCode=${taskCodeRow}&taskId=${taskIdRow}`
                            )
                            .then(response => response.json())
                            .then(data => {
                                const assignedOrganizations = data.organizations;
                                // Chèn dữ liệu vào bảng trong popup
                                const tbody = document.getElementById(
                                    'assigned-organizations-body');
                                tbody.innerHTML = ''; // Xóa dữ liệu cũ
                                assignedOrganizations.forEach(org => {
                                    const row = document.createElement('tr');
                                    row.innerHTML = `
                                            <td class="py-2 px-4 border-b">${org.code}</td>
                                            <td class="py-2 px-4 border-b">${org.name}</td>
                                            <td class="py-2 px-4 border-b">${org.email}</td>
                                            <td class="py-2 px-4 border-b">${org.phone}</td>
                                        `;
                                    tbody.appendChild(row);
                                });

                                // Hiển thị popup
                                document.getElementById('assigned-organizations-modal')
                                    .classList
                                    .remove('hidden');

                            });

                    }
                });
            });
        });
    </script>
@endsection
