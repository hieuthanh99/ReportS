@extends('layouts.app')

@section('content')
    <style>
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
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Cập nhật văn bản</h1>
        <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-lg shadow-lg">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cột trái -->
                <div class="mb-4">
                    <label for="document_code" class="block text-gray-700 text-sm font-medium mb-2">Mã văn bản</label>
                    <input disabled readonly type="text" id="document_code" name="document_code"
                        value="{{ $document->document_code }}"
                        class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    @error('document_code')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="document_name" class="block text-gray-700 text-sm font-medium mb-2">Tên văn bản</label>
                    <input type="text" id="document_name" name="document_name" value="{{ $document->document_name }}"
                        class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                </div>
                <div class="mb-4">
                    <label for="issuing_department" class="block text-gray-700 text-sm font-medium mb-2">Đơn vị phát
                        hành</label>
                    <select name="issuing_department" id="issuing_department" required
                        class="form-input w-full border border-gray-300 rounded-lg p-2">
                        @foreach ($organizations as $organization)
                            <option value="{{ $organization->id }}"
                                {{ $document->issuing_department == $organization->id ? 'selected' : '' }}>
                                {{ $organization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="release_date" class="block text-gray-700 text-sm font-medium mb-2">Ngày phát hành</label>
                    <input type="date" id="release_date" name="release_date"
                        value="{{ $document->getReleaseDateFormattedAttribute() }}"
                        class="form-input w-full border border-gray-300 rounded-lg p-2">
                </div>
            </div>

            <!-- Hàng upload file -->
            <div class="mb-4" style="margin: 20px 0">
                <label for="files" class="block text-gray-700 text-sm font-medium mb-2">Tải lên tài liệu (nhiều
                    tệp)</label>
                <input type="file" id="files" name="files[]"
                    class="form-input w-full border border-gray-300 rounded-lg p-2" multiple>
                <p class="text-gray-500 text-sm mt-1">Chọn nhiều tệp để tải lên.</p>
                <!-- Khu vực để hiển thị danh sách tệp đã chọn -->
                <div id="file-list-data" class="mt-2 file-list-data">
                    @foreach ($document->files as $file)
                        <div class="file-item flex items-center mb-2" data-file-id="{{ $file->id }}"
                            data-file-type="{{ mime_content_type(storage_path('app/public/' . $file->file_path)) }}">
                            <img class="file-icon w-12 h-12 mr-2" src="" alt="File icon">
                            <span class="text-gray-700">{{ $file->file_name }}</span>
                            <button type="button"
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
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mã đầu việc</th>
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
                                                value="{{ $timeParamsWeek['current'] }}">

                                            <input required type="hidden" id="numberCurrent" name="numberCurrent[]"
                                                value="1">
                                            <input required type="hidden" id="document_id" name="document_id[]"
                                                value="{{ $document->id }}">
                                            <input required type="hidden" id="task_code" name="task_code[]"
                                                value="{{ $task->task_code }}">
                                            <input required type="hidden" id="taskId" name="task_id[]"
                                                value="{{ $task->id }}">
                                            <button type="button" class="flex items-center text-blue-500 hover:underline"
                                                onclick="toggleRow('{{ $task->id }}')">
                                                <svg class="w-5 h-5 mr-2 {{ $criterias[$task->task_code]->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                                {{ $task->task_code }}
                                            </button>
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <input required type="hidden" id="task_code"
                                                @if ($isDisabled) readonly @endif
                                                value="{{ $task->task_code }}" class="task-input required"
                                                data-id="{{ $task->id }}">
                                            <input required type="text" name="task_name[]"
                                                @if ($isDisabled) readonly @endif
                                                value="{{ $task->task_name }}" class="task-input required">
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="required_result[]" id="required_result"
                                                value="{{ $task->required_result }}"
                                                @if ($isDisabled) readonly @endif
                                                placeholder="Nhập đánh giá"></td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="task_progress[]" id="task_progress"
                                                value="{{ $task->progress }}" placeholder="Nhập tiến độ"></td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="progress_evaluation[]" id="progress_evaluation"
                                                value="{{ $task->progress_evaluation }}" placeholder="Nhập đánh giá">
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <button onclick="downloadFile('document1.pdf')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <button onclick="downloadFile('document1.pdf')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="current_result[]" id="current_result"
                                                value="{{ $task->progress_evaluation }}" placeholder="Nhập kết quả"></td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="current_note[]" id="current_note"
                                                value="{{ $task->progress_evaluation }}" placeholder="Nhập mô tả"></td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <button onclick="downloadFile('document1.pdf')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @if ($task->status == 'Đã giao việc')
                                                <button data-document-id="{{ $document->id }}"
                                                    data-status = "{{ $task->status }}"
                                                    data-task-code="{{ $task->task_code }}"
                                                    data-task-id="{{ $task->id }}"
                                                    class="assign-task open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                    Giao việc
                                                </button>
                                            @else
                                                <button data-document-id="{{ $document->id }}"
                                                    data-status = "{{ $task->status }}"
                                                    data-task-code="{{ $task->task_code }}"
                                                    data-task-id="{{ $task->id }}"
                                                    class="open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                    Giao việc
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                    @if ($criterias[$task->task_code]->count() > 0)
                                        <tr id="criteria-row-{{ $task->id }}" class="hidden">
                                            @forelse ($criterias[$task->task_code] as $criterion)
                                                <tr>
                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                        <input required type="hidden" id="typeCurrent" name="typeCurrent[]"
                                                            value="{{ $timeParamsWeek['current'] }}">

                                                        <input required type="hidden" id="numberCurrent" name="numberCurrent[]"
                                                            value="1">
                                                        <input required type="hidden" id="document_id" name="document_id[]"
                                                            value="{{ $document->id }}">
                                                        <input required type="hidden" id="criteria_code" name="criteria_code[]"
                                                            value="{{ $criterion->CriteriaCode }}">
                                                        <input required type="hidden" id="criteriaId" name="criteria_id[]"
                                                            value="{{ $criterion->id }}">
                                                        <button type="button"
                                                            class="flex items-center text-blue-500 hover:underline">
                                                            <svg class="w-5 h-5 mr-2 {{ $criterias[$task->task_code]->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                            {{ $criterion->CriteriaCode }}
                                                        </button>
                                                    </td>
                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                        <input required type="hidden" id="criteria_code"
                                                            @if ($isDisabled) readonly @endif
                                                            value="{{ $criterion->CriteriaCode }}"
                                                            class="criteria-input required" data-id="{{ $criterion->id }}">
                                                        <input required type="text" name="criteria_name[]"
                                                            @if ($isDisabled) readonly @endif
                                                            value="{{ $criterion->CriteriaName }}"
                                                            class="criteria-input required">
                                                    </td>
                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                            type="text" name="required_result[]" id="required_result"
                                                            value="{{ $criterion->RequestResult }}"
                                                            @if ($isDisabled) readonly @endif
                                                            placeholder="Nhập đánh giá"></td>
                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                            type="text" name="criterion_progress[]" id="criterion_progress"
                                                            value="{{ $criterion->progress }}" placeholder="Nhập tiến độ"></td>
                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                            type="text" name="progress_evaluation[]" id="progress_evaluation"
                                                            value="{{ $criterion->progress_evaluation }}"
                                                            placeholder="Nhập đánh giá">
                                                    </td>
                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                        {{ $criterion->RequestResult }}</td>

                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                        {{ $criterion->RequestResult }}</td>

                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                        <button onclick="downloadFile('document1.pdf')"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    </td>

                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                        {{ $criterion->RequestResult }}</td>

                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                        {{ $criterion->RequestResult }}</td>

                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                        <button onclick="downloadFile('document1.pdf')"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    </td>

                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                            type="text" name="current_result[]" id="current_result"
                                                            value="{{ $criterion->progress_evaluation }}"
                                                            placeholder="Nhập kết quả"></td>

                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                            type="text" name="current_note[]" id="current_note"
                                                            value="{{ $criterion->progress_evaluation }}"
                                                            placeholder="Nhập mô tả"></td>

                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                                        <button onclick="downloadFile('document1.pdf')"
                                                            class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    </td>
                                                    <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">

                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="px-6 py-4 text-gray-500">Không
                                                        có
                                                        chỉ tiêu nào.</td>
                                                </tr>
                                            @endforelse
                                    </tr>
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
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300"
                            style="margin-top: 50px;>
                                      <thead class="bg-gray-100">
                            <tr>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mã đầu việc</th>
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
                                                value="{{ $timeParamsMonth['current'] }}">

                                            <input required type="hidden" id="numberCurrent" name="numberCurrent[]"
                                                value="2">
                                            <input required type="hidden" id="document_id" name="document_id[]"
                                                value="{{ $document->id }}">
                                            <input required type="hidden" id="task_code" name="task_code[]"
                                                value="{{ $task->task_code }}">
                                            <input required type="hidden" id="taskId" name="task_id[]"
                                                value="{{ $task->id }}">
                                            <button type="button" class="flex items-center text-blue-500 hover:underline"
                                                onclick="toggleRow('{{ $task->id }}')">
                                                <svg class="w-5 h-5 mr-2 {{ $criterias[$task->task_code]->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                                {{ $task->task_code }}
                                            </button>
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <input required type="hidden" id="task_code"
                                                @if ($isDisabled) disabled readonly @endif
                                                value="{{ $task->task_code }}" class="task-input required"
                                                data-id="{{ $task->id }}">
                                            <input required type="text" name="task_name[]"
                                                @if ($isDisabled) disabled readonly @endif
                                                value="{{ $task->task_name }}" class="task-input required">
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="required_result[]" id="required_result"
                                                value="{{ $task->required_result }}"
                                                @if ($isDisabled) disabled readonly @endif
                                                placeholder="Nhập đánh giá"></td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="task_progress[]" id="task_progress"
                                                value="{{ $task->progress }}" placeholder="Nhập tiến độ"></td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="progress_evaluation[]" id="progress_evaluation"
                                                value="{{ $task->progress_evaluation }}" placeholder="Nhập đánh giá">
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <button onclick="downloadFile('document1.pdf')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <button onclick="downloadFile('document1.pdf')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="current_result[]" id="current_result"
                                                value="{{ $task->progress_evaluation }}" placeholder="Nhập kết quả"></td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="current_note[]" id="current_note"
                                                value="{{ $task->progress_evaluation }}" placeholder="Nhập mô tả"></td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <button onclick="downloadFile('document1.pdf')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @if ($task->status == 'Đã giao việc')
                                                <button data-document-id="{{ $document->id }}"
                                                    data-status = "{{ $task->status }}"
                                                    data-task-code="{{ $task->task_code }}"
                                                    data-task-id="{{ $task->id }}"
                                                    class="assign-task open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                    Giao việc
                                                </button>
                                            @else
                                                <button data-document-id="{{ $document->id }}"
                                                    data-status = "{{ $task->status }}"
                                                    data-task-code="{{ $task->task_code }}"
                                                    data-task-id="{{ $task->id }}"
                                                    class="open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                    Giao việc
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                    @if ($criterias[$task->task_code]->count() > 0)
                                        <tr id="criteria-row-{{ $task->id }}" class="hidden">
                                            <td colspan="12">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            </th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            </th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Mã chỉ tiêu
                                                            </th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Tên chỉ tiêu</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Kết quả yêu cầu</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Hành động</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($criterias[$task->task_code] as $criterion)
                                                            <tr>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <input required type="hidden" id="criterion_id"
                                                                        value="{{ $criterion->id }}"
                                                                        class="task-input required">
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <input required type="text" id="criteria_code"
                                                                        value="{{ $criterion->CriteriaCode }}"
                                                                        class="task-input required">
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <input required type="text" id="criteria_name"
                                                                        value="{{ $criterion->CriteriaName }}"
                                                                        class="task-input required">
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    {{ $criterion->RequestResult }}</td>
                                                                <td> <button data-task-id="{{ $task->id }}"
                                                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 save-task-button">
                                                                        Lưu
                                                                    </button></td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="2" class="px-6 py-4 text-gray-500">Không
                                                                    có
                                                                    chỉ tiêu nào.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
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
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300"
                            style="margin-top: 50px;>
                                      <thead class="bg-gray-100">
                            <tr>
                                <th rowspan="2"
                                    class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mã đầu việc</th>
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
                                                value="{{ $timeParamsQuarter['current'] }}">

                                            <input required type="hidden" id="numberCurrent" name="numberCurrent[]"
                                                value="3">

                                            <input required type="hidden" id="document_id" name="document_id[]"
                                                value="{{ $document->id }}">
                                            <input required type="hidden" id="task_code" name="task_code[]"
                                                value="{{ $task->task_code }}">
                                            <input required type="hidden" id="taskId" name="task_id[]"
                                                value="{{ $task->id }}">
                                            <button type="button" class="flex items-center text-blue-500 hover:underline"
                                                onclick="toggleRow('{{ $task->id }}')">
                                                <svg class="w-5 h-5 mr-2 {{ $criterias[$task->task_code]->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                                {{ $task->task_code }}
                                            </button>
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <input required type="hidden" id="task_code"
                                                @if ($isDisabled) disabled readonly @endif
                                                value="{{ $task->task_code }}" class="task-input required"
                                                data-id="{{ $task->id }}">
                                            <input required type="text" name="task_name[]"
                                                @if ($isDisabled) disabled readonly @endif
                                                value="{{ $task->task_name }}" class="task-input required">
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="required_result[]" id="required_result"
                                                value="{{ $task->required_result }}"
                                                @if ($isDisabled) disabled readonly @endif
                                                placeholder="Nhập đánh giá"></td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="task_progress[]" id="task_progress"
                                                value="{{ $task->progress }}" placeholder="Nhập tiến độ"></td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="progress_evaluation[]" id="progress_evaluation"
                                                value="{{ $task->progress_evaluation }}" placeholder="Nhập đánh giá">
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <button onclick="downloadFile('document1.pdf')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <button onclick="downloadFile('document1.pdf')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="current_result[]" id="current_result"
                                                value="{{ $task->progress_evaluation }}" placeholder="Nhập kết quả">
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="current_note[]" id="current_note"
                                                value="{{ $task->progress_evaluation }}" placeholder="Nhập mô tả"></td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <button onclick="downloadFile('document1.pdf')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @if ($task->status == 'Đã giao việc')
                                                <button data-document-id="{{ $document->id }}"
                                                    data-status = "{{ $task->status }}"
                                                    data-task-code="{{ $task->task_code }}"
                                                    data-task-id="{{ $task->id }}"
                                                    class="assign-task open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                    Giao việc
                                                </button>
                                            @else
                                                <button data-document-id="{{ $document->id }}"
                                                    data-status = "{{ $task->status }}"
                                                    data-task-code="{{ $task->task_code }}"
                                                    data-task-id="{{ $task->id }}"
                                                    class="open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                    Giao việc
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                    @if ($criterias[$task->task_code]->count() > 0)
                                        <tr id="criteria-row-{{ $task->id }}" class="hidden">
                                            <td colspan="12">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            </th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            </th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Mã chỉ tiêu
                                                            </th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Tên chỉ tiêu</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Kết quả yêu cầu</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Hành động</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($criterias[$task->task_code] as $criterion)
                                                            <tr>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <input required type="hidden" id="criterion_id"
                                                                        value="{{ $criterion->id }}"
                                                                        class="task-input required">
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <input required type="text" id="criteria_code"
                                                                        value="{{ $criterion->CriteriaCode }}"
                                                                        class="task-input required">
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <input required type="text" id="criteria_name"
                                                                        value="{{ $criterion->CriteriaName }}"
                                                                        class="task-input required">
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    {{ $criterion->RequestResult }}</td>
                                                                <td> <button data-task-id="{{ $task->id }}"
                                                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 save-task-button">
                                                                        Lưu
                                                                    </button></td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="2" class="px-6 py-4 text-gray-500">Không
                                                                    có
                                                                    chỉ tiêu nào.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
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
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300"
                            style="margin-top: 50px;">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th rowspan="2"
                                        class="border border-gray-300 text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mã đầu việc</th>
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
                                                value="{{ $timeParamsYear['current'] }}">

                                            <input required type="hidden" id="numberCurrent" name="numberCurrent[]"
                                                value="4">
                                            <input required type="hidden" id="document_id" name="document_id[]"
                                                value="{{ $document->id }}">
                                            <input required type="hidden" id="task_code" name="task_code[]"
                                                value="{{ $task->task_code }}">
                                            <input required type="hidden" id="taskId" name="task_id[]"
                                                value="{{ $task->id }}">
                                            <button type="button" class="flex items-center text-blue-500 hover:underline"
                                                onclick="toggleRow('{{ $task->id }}')">
                                                <svg class="w-5 h-5 mr-2 {{ $criterias[$task->task_code]->count() > 0 ? 'rotate-90 transform' : '' }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                                {{ $task->task_code }}
                                            </button>
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <input required type="hidden" id="task_code"
                                                @if ($isDisabled) disabled readonly @endif
                                                value="{{ $task->task_code }}" class="task-input required"
                                                data-id="{{ $task->id }}">
                                            <input required type="text" name="task_name[]"
                                                @if ($isDisabled) disabled readonly @endif
                                                value="{{ $task->task_name }}" class="task-input required">
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="required_result[]" id="required_result"
                                                value="{{ $task->required_result }}"
                                                @if ($isDisabled) disabled readonly @endif
                                                placeholder="Nhập đánh giá"></td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="task_progress[]" id="task_progress"
                                                value="{{ $task->progress }}" placeholder="Nhập tiến độ"></td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="progress_evaluation[]" id="progress_evaluation"
                                                value="{{ $task->progress_evaluation }}" placeholder="Nhập đánh giá">
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <button onclick="downloadFile('document1.pdf')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            {{ $task->required_result }}</td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <button onclick="downloadFile('document1.pdf')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="current_result[]" id="current_result"
                                                value="{{ $task->progress_evaluation }}" placeholder="Nhập kết quả">
                                        </td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap"><input required
                                                type="text" name="current_note[]" id="current_note"
                                                value="{{ $task->progress_evaluation }}" placeholder="Nhập mô tả"></td>

                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            <button onclick="downloadFile('document1.pdf')"
                                                class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>
                                        <td class="border border-gray-300 px-6 py-4 whitespace-nowrap">
                                            @if ($task->status == 'Đã giao việc')
                                                <button data-document-id="{{ $document->id }}"
                                                    data-status = "{{ $task->status }}"
                                                    data-task-code="{{ $task->task_code }}"
                                                    data-task-id="{{ $task->id }}"
                                                    class="assign-task open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                    Giao việc
                                                </button>
                                            @else
                                                <button data-document-id="{{ $document->id }}"
                                                    data-status = "{{ $task->status }}"
                                                    data-task-code="{{ $task->task_code }}"
                                                    data-task-id="{{ $task->id }}"
                                                    class="open-popup-button bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                    Giao việc
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                    @if ($criterias[$task->task_code]->count() > 0)
                                        <tr id="criteria-row-{{ $task->id }}" class="hidden">
                                            <td colspan="12">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            </th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            </th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Mã chỉ tiêu
                                                            </th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Tên chỉ tiêu</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Kết quả yêu cầu</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Hành động</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($criterias[$task->task_code] as $criterion)
                                                            <tr>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <input required type="hidden" id="criterion_id"
                                                                        value="{{ $criterion->id }}"
                                                                        class="task-input required">
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <input required type="text" id="criteria_code"
                                                                        value="{{ $criterion->CriteriaCode }}"
                                                                        class="task-input required">
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <input required type="text" id="criteria_name"
                                                                        value="{{ $criterion->CriteriaName }}"
                                                                        class="task-input required">
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    {{ $criterion->RequestResult }}</td>
                                                                <td> <button data-task-id="{{ $task->id }}"
                                                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 save-task-button">
                                                                        Lưu
                                                                    </button></td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="2" class="px-6 py-4 text-gray-500">Không
                                                                    có
                                                                    chỉ tiêu nào.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
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
                            <th class="py-2 px-4 border-b">Người tạo</th>
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
        function toggleRow(taskId) {
            const criteriaRow = document.getElementById(`criteria-row-${taskId}`);
            const icon = criteriaRow.previousElementSibling.querySelector('svg');
            if (criteriaRow) {
                criteriaRow.classList.toggle('hidden');
                icon.classList.toggle('rotate-90');
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
                                            <td class="py-2 px-4 border-b">${org.creator}</td>
                                        `;
                                    tbody.appendChild(row);
                                });

                                // Hiển thị popup
                                document.getElementById('assigned-organizations-modal')
                                    .classList
                                    .remove('hidden');

                            });

                    } else {
                        document.getElementById('assign-organizations-modal').classList.remove(
                            'hidden');
                    }

                });
            });

            // Đóng popup
            document.getElementById('close-assigned-organizations-modal').addEventListener('click', function(
                event) {
                event.preventDefault();
                document.getElementById('assigned-organizations-modal').classList.add('hidden');
            });
        });
    </script>
@endsection
