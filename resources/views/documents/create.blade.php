@extends('layouts.app')

@section('content')
    <style>
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
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Thêm văn bản mới</h1>
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-lg shadow-lg">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cột trái -->
                <div class="mb-4">
                    <label for="document_code" class="block text-gray-700 text-sm font-medium mb-2">Mã văn bản</label>
                    <input type="text" id="document_code" name="document_code"
                        class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    @error('document_code')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="document_name" class="block text-gray-700 text-sm font-medium mb-2">Tên văn bản</label>
                    <input type="text" id="document_name" name="document_name"
                        class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                </div>
                <div class="mb-4">
                    <label for="issuing_department" class="block text-gray-700 text-sm font-medium mb-2">Đơn vị phát
                        hành</label>
                    <select name="issuing_department" id="issuing_department" required
                        class="form-input w-full border border-gray-300 rounded-lg p-2">
                        @foreach ($organizations as $organization)
                            <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="release_date" class="block text-gray-700 text-sm font-medium mb-2">Ngày phát hành</label>
                    <input type="date" id="release_date" name="release_date"
                        class="form-input w-full border border-gray-300 rounded-lg p-2">
                </div>

                <!-- Cột phải -->
                {{-- <div class="mb-4">
                    <label for="start_date" class="block text-gray-700 text-sm font-medium mb-2">Thời gian bắt đầu</label>
                    <input type="date" id="start_date" name="start_date"
                        class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                </div>
                <div class="mb-4">
                    <label for="end_date" class="block text-gray-700 text-sm font-medium mb-2">Thời gian kết thúc</label>
                    <input type="date" id="end_date" name="end_date"
                        class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                </div> --}}
            </div>

            <!-- Hàng upload file -->
            <div class="mb-4" style="margin: 20px 0">
                <label for="files" class="block text-gray-700 text-sm font-medium mb-2">Tải lên tài liệu (nhiều
                    tệp)</label>
                <input type="file" id="files" name="files[]"
                    class="form-input w-full border border-gray-300 rounded-lg p-2" multiple>
                <p class="text-gray-500 text-sm mt-1">Chọn nhiều tệp để tải lên.</p>
                <!-- Khu vực để hiển thị danh sách tệp đã chọn -->
                <div id="file-list" class="mt-2 file-list"></div>
            </div>

            <!-- Hàng danh sách các đầu việc -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Danh sách các đầu việc</label>
                <div class="overflow-x-auto">
                    <table id="task-table" class="min-w-full bg-white border border-gray-300 rounded-lg shadow">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="py-2 px-4 text-left text-gray-600">Mã đầu việc</th>
                                <th class="py-2 px-4 text-left text-gray-600">Tên đầu việc</th>
                                <th class="py-2 px-4 text-left text-gray-600">Chu kỳ báo cáo</th>
                                <th class="py-2 px-4 text-left text-gray-600">Kết quả yêu cầu</th>
                                <th class="py-2 px-4 text-left text-gray-600">Ngày bắt đầu</th>
                                <th class="py-2 px-4 text-left text-gray-600">Ngày kết thúc</th>
                                <th class="py-2 px-4 text-left text-gray-600">Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="task-list">
                            <!-- Danh sách đầu việc sẽ được chèn vào đây -->
                        </tbody>
                    </table>
                </div>
                <button type="button" id="create-task"
                    class="bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300 mt-2">Tạo
                    đầu việc mới</button>
                <button type="button" id="assign-task"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mt-2">Gán
                    đầu việc có sẵn</button>
            </div>

            <!-- Danh sách các tiêu chí -->
            <div class="mb-4 mt-8">
                <label class="block text-gray-700 text-sm font-medium mb-2">Danh sách các tiêu chí</label>
                <div class="overflow-x-auto">
                    <table id="criteria-table" class="min-w-full bg-white border border-gray-300 rounded-lg shadow">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="py-2 px-4 text-left text-gray-600">Mã công việc</th>
                                <th class="py-2 px-4 text-left text-gray-600">Mã tiêu chí</th>
                                <th class="py-2 px-4 text-left text-gray-600">Tên tiêu chí</th>
                                <th class="py-2 px-4 text-left text-gray-600">Kết quả yêu cầu</th>
                                <th class="py-2 px-4 text-left text-gray-600">Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="criteria-list">
                            <!-- Danh sách tiêu chí sẽ được chèn vào đây -->
                        </tbody>
                    </table>
                </div>
                <div id="criteria-actions" class="mt-2" style="display: none;">
                    <button type="button" id="create-criteria"
                        class="bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300">Tạo
                        tiêu chí mới</button>
                    <button type="button" id="assign-criteria"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Gán
                        tiêu chí có sẵn</button>
                </div>
            </div>

            <div id="existing-organizations-document" style="text-align: center;">

                <!-- Danh sách đầu việc sẽ được chèn vào đây -->
            </div>
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 mt-4">Lưu</button>
        </form>

        <!-- Popup tạo đầu việc mới -->
        <div id="create-task-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h2 class="text-xl font-bold mb-4">Tạo đầu việc mới</h2>
                <form id="create-task-form">
                    <div class="mb-4">
                        <label for="new-task-code" class="block text-gray-700 text-sm font-medium mb-2">Mã đầu việc</label>
                        <input type="text" id="new-task-code" name="task_code"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="new-task-name" class="block text-gray-700 text-sm font-medium mb-2">Tên đầu
                            việc</label>
                        <input type="text" id="new-task-name" name="task_name"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="new-task-reporting-cycle" class="block text-gray-700 text-sm font-medium mb-2">Chu kỳ
                            báo cáo</label>
                        <select id="new-task-reporting-cycle" name="reporting_cycle"
                            class="form-select w-full border border-gray-300 rounded-lg p-2">
                            <option value="1">Tuần</option>
                            <option value="2">Tháng</option>
                            <option value="3">Quý</option>
                            <option value="4">Năm</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="new-task-category" class="block text-gray-700 text-sm font-medium mb-2">Danh
                            mục</label>
                        <select id="new-task-category" name="category"
                            class="form-select w-full border border-gray-300 rounded-lg p-2">
                            <!-- Options sẽ được thêm bằng JavaScript -->
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="required-result" class="block text-gray-700 text-sm font-medium mb-2">Kết Quả Yêu
                            Cầu</label>
                        <input type="text" id="required-result" name="required_result"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="start-date" class="block text-gray-700 text-sm font-medium mb-2">Ngày Bắt Đầu</label>
                        <input type="date" id="start-date" name="start_date"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="end-date" class="block text-gray-700 text-sm font-medium mb-2">Ngày Kết Thúc</label>
                        <input type="date" id="end-date" name="end_date"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    </div>
                    <button type="button" id="save-new-task"
                        class="bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300">Lưu</button>
                    <button type="button" id="cancel-create-task"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">Hủy</button>
                </form>
            </div>
        </div>
        <!-- Popup sửa đầu việc -->
        <div id="edit-task-modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
                <h2 class="text-xl font-semibold mb-4">Chỉnh Sửa Đầu Việc</h2>
                <form id="edit-task-form">
                    <div class="mb-4">
                        <label for="edit-task-code" class="block text-gray-700 text-sm font-medium mb-2">Mã Đầu
                            Việc</label>
                        <input type="text" id="edit-task-code" name="edit_task_code"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="edit-task-name" class="block text-gray-700 text-sm font-medium mb-2">Tên Đầu
                            Việc</label>
                        <input type="text" id="edit-task-name" name="edit_task_name"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit-task-reporting-cycle" class="block text-gray-700 text-sm font-medium mb-2">Chu Kỳ
                            Báo Cáo</label>
                        <select id="edit-task-reporting-cycle" name="edit_task_reporting_cycle"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                            <!-- Options here -->
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="edit-task-category" class="block text-gray-700 text-sm font-medium mb-2">Danh
                            Mục</label>
                        <select id="edit-task-category" name="edit_task_category"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                            <!-- Options here -->
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="edit-required-result" class="block text-gray-700 text-sm font-medium mb-2">Kết Quả Yêu
                            Cầu</label>
                        <input type="text" id="edit-required-result" name="edit_required_result"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit-start-date" class="block text-gray-700 text-sm font-medium mb-2">Ngày Bắt
                            Đầu</label>
                        <input type="date" id="edit-start-date" name="edit_start_date"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit-end-date" class="block text-gray-700 text-sm font-medium mb-2">Ngày Kết
                            Thúc</label>
                        <input type="date" id="edit-end-date" name="edit_end_date"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="save-edit-task"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Lưu</button>
                        <button type="button" id="cancel-edit-task"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300 ml-2">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Popup chọn đầu việc có sẵn -->
        <div id="assign-task-modal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-3/4">
                <h2 class="text-xl font-bold mb-4">Gán đầu việc có sẵn</h2>
                <div class="mb-4">
                    <label for="search-tasks" class="block text-gray-700 text-sm font-medium mb-2">Tìm kiếm đầu
                        việc</label>
                    <input type="text" id="search-tasks"
                        class="form-input w-full border border-gray-300 rounded-lg p-2"
                        placeholder="Tìm kiếm theo mã hoặc tên">
                </div>
                <div class="mb-4 overflow-x-auto">
                    <table id="existing-tasks-table" class="w-full border border-gray-300 rounded-lg">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b checkbox-column">
                                    <input type="checkbox" id="check-all-tasks">
                                    <label for="check-all-tasks" class="text-gray-700 text-sm font-medium"></label>
                                </th>
                                <th class="py-2 px-4 border-b">Mã công việc</th>
                                <th class="py-2 px-4 border-b">Tên công việc</th>
                                <th class="py-2 px-4 border-b">Kế hoạch</th>
                            </tr>
                        </thead>
                        <tbody id="existing-tasks" style="text-align: center">
                            <!-- Danh sách đầu việc sẽ được chèn vào đây -->
                        </tbody>
                    </table>
                </div>
                <button type="button" id="assign-tasks"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Gán
                </button>
                <button type="button" id="cancel-assign-tasks"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">Hủy
                </button>
            </div>
        </div>

        <!-- Modal for adding new criteria -->
        <div id="create-criteria-modal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h2 class="text-xl font-bold mb-4">Tạo chỉ tiêu</h2>
                <form id="create-criteria-form">
                    <div class="mb-4">
                        <label for="task-select" class="block text-gray-700 text-sm font-medium mb-2">Chọn đầu công
                            việc</label>
                        <select id="task-select" name="task_id"
                            class="form-select w-full border border-gray-300 rounded-lg p-2">
                            <!-- Options sẽ được thêm bằng JavaScript -->
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="new-criteria-code" class="block text-gray-700 text-sm font-medium mb-2">Mã chỉ
                            tiêu</label>
                        <input type="text" id="new-criteria-code" name="criteria_code"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="new-criteria-name" class="block text-gray-700 text-sm font-medium mb-2">Tên chỉ
                            tiêu</label>
                        <input type="text" id="new-criteria-name" name="criteria_name"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                    </div>
                    <button type="button" id="save-new-criteria"
                        class="bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300">Lưu</button>
                    <button type="button" id="cancel-create-criteria"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">Hủy</button>
                </form>
            </div>
        </div>
        <!-- Popup chọn chỉ tiêu có sẵn -->
        <div id="assign-criteria-modal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-3/4">
                <h2 class="text-xl font-bold mb-4">Danh sách chỉ tiêu</h2>
                <div class="mb-4">
                    <label for="task-assign-select" class="block text-gray-700 text-sm font-medium mb-2">Chọn đầu công
                        việc</label>
                    <select id="task-assign-select" name="task_assign_id"
                        class="form-select w-full border border-gray-300 rounded-lg p-2">
                        <!-- Options sẽ được thêm bằng JavaScript -->
                    </select>
                </div>
                <div class="mb-4">
                    <label for="search-criteria" class="block text-gray-700 text-sm font-medium mb-2">Tìm kiếm đầu
                        việc</label>
                    <input type="text" id="search-criteria"
                        class="form-input w-full border border-gray-300 rounded-lg p-2"
                        placeholder="Tìm kiếm theo mã hoặc tên">
                </div>
                <div class="mb-4 overflow-x-auto">
                    <table id="existing-criteria-table" class="w-full border border-gray-300 rounded-lg">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b checkbox-column">
                                    <input type="checkbox" id="check-all-criteria">
                                    <label for="check-all-criteria" class="text-gray-700 text-sm font-medium"></label>
                                </th>
                                <th class="py-2 px-4 border-b">Mã chỉ tiêu</th>
                                <th class="py-2 px-4 border-b">Tên chỉ tiêu</th>
                            </tr>
                        </thead>
                        <tbody id="existing-criteria" style="text-align: center">
                            <!-- Danh sách đầu việc sẽ được chèn vào đây -->
                        </tbody>
                    </table>
                </div>
                <button type="button" id="assign-criteria-save"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Gán
                </button>
                <button type="button" id="cancel-assign-criteria"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">Hủy
                </button>
            </div>
        </div>


        {{-- Giao việc  --}}
        <div id="assign-organizations-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
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
                <div class="mb-4 overflow-x-auto">
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
        </div>
        
        <div id="loading-spinner" class="hidden">
            <!-- Ví dụ về spinner -->
            <div class="spinner"></div>
        </div>

        <script>
            async function checkTaskCodeInDB(taskCode) {
                try {
                    document.getElementById('loading-spinner').classList.remove('hidden'); // Hiển thị spinner
                    const response = await fetch(`/api/check-task-code/${taskCode}`);
                    const data = await response.json();
                    return data.exists;
                } catch (error) {
                    console.error('Error checking task code:', error);
                    return false;
                } finally {
                    document.getElementById('loading-spinner').classList.add('hidden'); // Ẩn spinner
                }
            }

            async function checkDocumentCodeInDB(documentCode) {
                try {
                    document.getElementById('loading-spinner').classList.remove('hidden'); // Hiển thị spinner
                    const response = await fetch(`/api/check-document-code/${documentCode}`);
                    const data = await response.json();
                    return data.exists;
                } catch (error) {
                    console.error('Error checking task code:', error);
                    return false;
                } finally {
                    document.getElementById('loading-spinner').classList.add('hidden'); // Ẩn spinner
                }
            }

            function fetchCategories() {
                fetch('{{ route('categories.list.document') }}')
                    .then(response => response.json())
                    .then(data => {
                        const categorySelect = document.getElementById('new-task-category');
                        categorySelect.innerHTML = ''; // Xóa các tùy chọn hiện tại
                        console.log(data.categories);
                        data.categories.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.CategoryID; // Giá trị của tùy chọn
                            option.textContent = category.CategoryName; // Văn bản hiển thị
                            categorySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching categories:', error));
            }
            let existingTaskCodes = [];

            document.addEventListener('DOMContentLoaded', function() {

                //=====================================FILE=============================================
                const fileInput = document.getElementById('files');
                const fileList = document.getElementById('file-list');

                function getFileIcon(fileType) {
                    // URL tương đối từ thư mục public
                    const baseUrl = '/icons/';
                    
                    switch (fileType) {
                        case 'application/pdf':
                            return baseUrl + 'pdf.png'; // Đặt đường dẫn đến biểu tượng PDF
                        case 'application/msword':
                        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                            return baseUrl + 'word.png'; // Đặt đường dẫn đến biểu tượng Word
                        case 'application/vnd.ms-excel':
                        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                            return baseUrl + 'excel.png'; // Đặt đường dẫn đến biểu tượng Excel
                        default:
                            return baseUrl + 'default-icon.png'; // Đặt đường dẫn đến biểu tượng mặc định
                    }
                }

                function updateFileList() {
                    fileList.innerHTML = '';
                    const files = fileInput.files;

                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        // Kiểm tra kích thước file (2MB = 2 * 1024 * 1024 bytes)
                        if (file.size > 2 * 1024 * 1024) {
                        alert(`${file.name} vượt quá kích thước 2MB và sẽ không được thêm vào danh sách.`);
                        continue;
                        }


                        const fileItem = document.createElement('div');
                        fileItem.className = 'file-item';

                        const fileIcon = document.createElement('img');
                        fileIcon.src = getFileIcon(file.type);
                        fileItem.appendChild(fileIcon);

                        const fileName = document.createElement('span');
                        fileName.className = 'text-gray-700';
                        fileName.textContent = file.name;
                        fileItem.appendChild(fileName);

                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.className = 'remove-button';
                        removeButton.textContent = '×';
                        removeButton.addEventListener('click', () => {
                            removeFile(i);
                        });
                        fileItem.appendChild(removeButton);

                        fileList.appendChild(fileItem);
                    }
                }

                function removeFile(index) {
                    const dt = new DataTransfer();
                    const {
                        files
                    } = fileInput;

                    for (let i = 0; i < files.length; i++) {
                        if (i !== index) {
                            dt.items.add(files[i]);
                        }
                    }

                    fileInput.files = dt.files;
                    updateFileList();
                }

                fileInput.addEventListener('change', updateFileList);

                // Cập nhật danh sách tệp khi trang được tải
                updateFileList();
                //=============================================== End file ==========================================
                function checkTasksAndToggleButtons() {
                    // Lấy tất cả các input có name là "tasks[]"
                    const taskInputs = document.querySelectorAll('input[name="tasks[]"]');
                    const criteriaActions = document.getElementById('criteria-actions');

                    // Kiểm tra nếu có ít nhất một input task có giá trị
                    if (taskInputs.length > 0) {
                        criteriaActions.style.display = 'block'; // Hiển thị nút
                    } else {
                        criteriaActions.style.display = 'none'; // Ẩn nút
                    }
                }

                // Gọi hàm để kiểm tra ngay khi trang được tải
                checkTasksAndToggleButtons();

                const reportingCycleMap = {
                    1: 'Hàng Ngày',
                    2: 'Hàng Tuần',
                    3: 'Hàng Tháng',
                    3: 'Hàng Năm',
                    // Thêm các giá trị khác nếu cần
                };
                // Hiển thị popup tạo đầu việc mới
                document.getElementById('create-task').addEventListener('click', function() {
                    fetchCategories();
                    document.getElementById('create-task-modal').classList.remove('hidden');
                });
                document.getElementById('new-task-code').addEventListener('blur', async function() {
                    const taskCode = this.value;
                    const exists = await checkTaskCodeInDB(taskCode);

                    if (exists) {
                        alert('Mã code đã tồn tại trong CSDL. Vui lòng nhập mã code khác.');
                        this.value = ''; // Xóa giá trị nhập
                    }
                    if (existingTaskCodes.includes(taskCode)) {
                        alert('Mã code đã tồn tại. Vui lòng nhập mã code khác.');
                        return;
                    }
                });
                document.getElementById('document_code').addEventListener('blur', async function() {
                    const taskCode = this.value;
                    const exists = await checkDocumentCodeInDB(taskCode);

                    if (exists) {
                        alert('Mã code đã tồn tại trong CSDL. Vui lòng nhập mã code khác.');
                        this.value = ''; // Xóa giá trị nhập
                    }
                });
                
                document.getElementById('cancel-create-task').addEventListener('click', function() {
                    document.getElementById('create-task-modal').classList.add('hidden');
                });
                // Lưu đầu việc mới
                document.getElementById('save-new-task').addEventListener('click', function() {
                    const taskCode = document.getElementById('new-task-code').value;
                    const taskName = document.getElementById('new-task-name').value;
                    const reportingCycle = document.getElementById('new-task-reporting-cycle').value;
                    const category = document.getElementById('new-task-category').value;
                    const requiredResult = document.getElementById('required-result').value;
                    const startDate = document.getElementById('start-date').value;
                    const endDate = document.getElementById('end-date').value;

                    // Validate fields
                    if (!taskCode || !taskName || !reportingCycle || !category || !requiredResult || !
                        startDate || !endDate) {
                        alert('Vui lòng điền tất cả các trường bắt buộc.');
                        return;
                    }

                    existingTaskCodes.push(taskCode);
                    const reportingCycleName = reportingCycleMap[reportingCycle] || reportingCycle;
                    const taskHTML = `
                    <tr>
                        <td class="py-2 px-4 border-b">${taskCode}</td>
                        <td class="py-2 px-4 border-b">${taskName}</td>
                        <td class="py-2 px-4 border-b">${reportingCycleName}</td>
                        <td class="py-2 px-4 border-b" style="display: none">${reportingCycle}</td>
                        <td class="py-2 px-4 border-b">${requiredResult}</td>
                        <td class="py-2 px-4 border-b" style="display: none">${category}</td>
                        <td class="py-2 px-4 border-b">${startDate}</td>
                        <td class="py-2 px-4 border-b">${endDate}</td>
                        <td class="py-2 px-4 border-b">
                            <button type="button" class="bg-blue-500 text-white px-2 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300  assign-task-organizations">Giao việc</button>
                            <button type="button" class="bg-blue-500 text-white px-2 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300  remove-task">Xóa</button>
                            <input type="hidden" name="tasks[]" value="${taskCode}|${taskName}|${reportingCycle}|${category}|${requiredResult}|${startDate}|${endDate}">
                        </td>
                    </tr>
                `;

                    document.getElementById('task-list').insertAdjacentHTML('beforeend', taskHTML);
                    document.getElementById('create-task-modal').classList.add('hidden');
                    document.getElementById('create-task-form').reset();
                    checkTasksAndToggleButtons();
                });

                //=====================================Xóa==================================///
                // Xóa đầu việc khỏi danh sách
                document.getElementById('task-list').addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-task')) {
                        const row = e.target.closest('tr');
                        const taskCode = row.cells[0].innerText.trim();

                        // Xóa mã công việc khỏi mảng
                        existingTaskCodes = existingTaskCodes.filter(code => code !== taskCode);

                        // Xóa hàng công việc khỏi bảng
                        row.remove();
                    }
                });

                //=====================================EDIT Task==================================///
                document.getElementById('task-list').addEventListener('click', function(event) {
                    if (event.target.classList.contains('edit-task')) {
                        const row = event.target.closest('tr');
                        const taskCode = row.cells[0].innerText.trim();
                        const taskName = row.cells[1].innerText.trim();
                        const reportingCycle = row.cells[3].innerText.trim();
                        const category = row.cells[5].innerText.trim();
                        const requiredResult = row.cells[4].innerText.trim();
                        const startDate = row.cells[6].innerText.trim();
                        const endDate = row.cells[7].innerText.trim();
                        console.log(row);

                        // Hiển thị thông tin công việc trong form chỉnh sửa
                        document.getElementById('edit-task-code').value = taskCode;
                        document.getElementById('edit-task-name').value = taskName;
                        document.getElementById('edit-task-reporting-cycle').value = reportingCycle;
                        // document.getElementById('edit-task-category').value = category;
                        document.getElementById('edit-required-result').value = requiredResult;
                        document.getElementById('edit-start-date').value = startDate;
                        document.getElementById('edit-end-date').value = endDate;
                        // Cập nhật dropdown danh mục trong popup chỉnh sửa
                        fetch('{{ route('categories.list.document') }}')
                            .then(response => response.json())
                            .then(data => {
                                const categorySelect = document.getElementById('edit-task-category');
                                categorySelect.innerHTML = ''; // Xóa các tùy chọn hiện tại
                                console.log(data.categories);

                                data.categories.forEach(cat => {
                                    const option = document.createElement('option');
                                    option.value = cat.CategoryID; // Giá trị của tùy chọn
                                    option.textContent = cat.CategoryName; // Văn bản hiển thị

                                    // Nếu giá trị của tùy chọn trùng với giá trị category hiện tại, đánh dấu là selected
                                    if (cat.CategoryID === category) {
                                        option.selected = true;
                                    }

                                    categorySelect.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error fetching categories:', error));
                        // Hiển thị popup chỉnh sửa
                        document.getElementById('edit-task-modal').classList.remove('hidden');
                    }
                });

                // Xử lý lưu chỉnh sửa
                document.getElementById('save-edit-task').addEventListener('click', function() {
                    const taskCode = document.getElementById('edit-task-code').value.trim();
                    const taskName = document.getElementById('edit-task-name').value.trim();
                    const reportingCycle = document.getElementById('edit-task-reporting-cycle').value.trim();
                    const category = document.getElementById('edit-task-category').value.trim();
                    const requiredResult = document.getElementById('edit-required-result').value.trim();
                    const startDate = document.getElementById('edit-start-date').value.trim();
                    const endDate = document.getElementById('edit-end-date').value.trim();

                    // Validate fields
                    if (!taskName || !reportingCycle || !category || !requiredResult || !startDate || !
                        endDate) {
                        alert('Vui lòng điền tất cả các trường bắt buộc.');
                        return;
                    }

                    // Cập nhật thông tin công việc trong bảng
                    const row = document.querySelector(`#task-list tr[data-task-code="${taskCode}"]`);
                    if (row) {
                        console.log(row);
                        row.cells[1].innerText = taskName;
                        row.cells[2].innerText = reportingCycle;
                        row.cells[3].innerText = category;
                        row.cells[4].innerText = requiredResult;
                        row.cells[5].innerText = startDate;
                        row.cells[6].innerText = endDate;
                    }

                    // Đóng popup và reset form
                    document.getElementById('edit-task-modal').classList.add('hidden');
                    document.getElementById('edit-task-form').reset();
                });

                // Xử lý hủy chỉnh sửa
                document.getElementById('cancel-edit-task').addEventListener('click', function() {
                    document.getElementById('edit-task-modal').classList.add('hidden');
                });
                ///============================Gán việc cho document================================//

                // Hàm để lấy và hiển thị danh sách đầu việc với checkbox
                function fetchTasks(query = '') {
                    fetch(`{{ route('tasks.search') }}?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            const tasksTableBody = document.getElementById('existing-tasks');
                            tasksTableBody.innerHTML = ''; // Xóa nội dung hiện tại

                            // Kiểm tra xem có dữ liệu không
                            if (!data.tasks || data.tasks.length === 0) {
                                const row = document.createElement('tr');
                                const cell = document.createElement('td');
                                cell.colSpan = 4; // Chiếm toàn bộ số cột
                                cell.textContent = 'Không có đầu việc nào';
                                row.appendChild(cell);
                                tasksTableBody.appendChild(row);
                                return;
                            }

                            data.tasks.forEach(task => {
                                const taskCode = task.task_code;

                                // Kiểm tra xem taskCode đã tồn tại trong existingTaskCodes chưa
                                if (existingTaskCodes.includes(taskCode)) {
                                    return; // Bỏ qua nếu taskCode đã tồn tại
                                }

                                const row = document.createElement('tr');

                                // Checkbox
                                const checkboxCell = document.createElement('td');
                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.id = `task-${task.task_code}`;
                                checkbox.value = task.task_code;
                                checkbox.classList.add('task-checkbox');
                                checkboxCell.appendChild(checkbox);

                                // Mã công việc
                                const codeCell = document.createElement('td');
                                codeCell.textContent = task.task_code;
                                codeCell.classList.add('task-code-assign');

                                // Tên công việc
                                const nameCell = document.createElement('td');
                                nameCell.textContent = task.task_name;
                                nameCell.classList.add('task-name-assign');

                                // Kế hoạch
                                const planCell = document.createElement('td');
                                planCell.textContent = task.required_result ||
                                    ''; // Hoặc bất kỳ thuộc tính nào đại diện cho kế hoạch
                                planCell.classList.add('required-result-assign');

                                // Kế hoạch
                                const categoryCell = document.createElement('td');
                                categoryCell.textContent = task.category;
                                categoryCell.classList.add('category-assign');
                                categoryCell.style.display = 'none';

                                const reportingCell = document.createElement('td');
                                reportingCell.textContent = task.reporting_cycle;
                                reportingCell.classList.add('reporting-cycle-assign');
                                reportingCell.style.display = 'none';

                                const start_dateCell = document.createElement('td');
                                start_dateCell.textContent = task.start_date;
                                start_dateCell.classList.add('start-date-assign');
                                start_dateCell.style.display = 'none';

                                const end_dateCell = document.createElement('td');
                                end_dateCell.textContent = task.end_date;
                                end_dateCell.classList.add('end-date-assign');
                                end_dateCell.style.display = 'none';


                                row.appendChild(checkboxCell);
                                row.appendChild(codeCell);
                                row.appendChild(nameCell);
                                row.appendChild(planCell);
                                row.appendChild(categoryCell);
                                row.appendChild(reportingCell);
                                row.appendChild(start_dateCell);
                                row.appendChild(end_dateCell);

                                tasksTableBody.appendChild(row);
                            });
                        })
                        .catch(error => console.error('Error fetching tasks:', error));
                }

                // Khi nhấn nút Gán, xử lý các đầu việc được chọn
                document.getElementById('assign-tasks').addEventListener('click', function() {
                    const selectedCheckboxes = document.querySelectorAll(
                        '#existing-tasks input[type="checkbox"]:checked');

                    selectedCheckboxes.forEach(checkbox => {
                        const row = checkbox.closest('tr'); // Lấy hàng chứa checkbox
                        console.log("row");

                        console.log(row);
                        console.log(row.querySelector('.task-code-assign'));
                        const taskCode = row.querySelector('.task-code-assign').textContent;
                        const taskName = row.querySelector('.task-name-assign').textContent;
                        const requiredResult = row.querySelector('.required-result-assign').textContent;
                        const category = row.querySelector('.category-assign').textContent;
                        const reportingCycle = row.querySelector('.reporting-cycle-assign').textContent;
                        const startDate = row.querySelector('.start-date-assign').textContent;
                        const endDate = row.querySelector('.end-date-assign').textContent;
                        // Validate if the task code already exists in the list
                        if (!existingTaskCodes.includes(taskCode)) {
                            existingTaskCodes.push(taskCode);
                            const reportingCycleName = reportingCycleMap[reportingCycle] ||
                                reportingCycle;
                            const taskHTML = `
                                <tr>
                                    <td class="py-2 px-4 border-b">${taskCode}</td>
                                    <td class="py-2 px-4 border-b">${taskName}</td>
                                    <td class="py-2 px-4 border-b">${reportingCycleName}</td>
                                    <td class="py-2 px-4 border-b" style="display: none">${reportingCycle}</td>
                                    <td class="py-2 px-4 border-b">${requiredResult}</td>
                                    <td class="py-2 px-4 border-b" style="display: none">${category}</td>
                                    <td class="py-2 px-4 border-b">${startDate}</td>
                                    <td class="py-2 px-4 border-b">${endDate}</td>
                                    <td class="py-2 px-4 border-b">
                                        <button type="button" class="bg-blue-500 text-white px-2 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300  assign-task-organizations">Giao việc</button>
                                        <button type="button" class="bg-blue-500 text-white px-2 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300  remove-task">Xóa</button>
                                        <input type="hidden" name="tasks[]" value="${taskCode}|${taskName}|${reportingCycle}|${category}|${requiredResult}|${startDate}|${endDate}">
                                    </td>
                                </tr>
                            `;

                            document.getElementById('task-list').insertAdjacentHTML('beforeend',
                                taskHTML);
                        }
                    });
                    checkTasksAndToggleButtons();
                    // Đóng modal
                    document.getElementById('assign-task-modal').classList.add('hidden');
                });

                // Khi người dùng gõ vào ô tìm kiếm
                document.getElementById('search-tasks').addEventListener('input', function(event) {
                    const query = event.target.value;
                    fetchTasks(query);
                });

                // Khi checkbox "Chọn tất cả" thay đổi trạng thái
                document.getElementById('check-all-tasks').addEventListener('change', function(event) {
                    const checked = event.target.checked;
                    document.querySelectorAll('#existing-tasks input.task-checkbox').forEach(checkbox => {
                        checkbox.checked = checked;
                    });
                });

                document.getElementById('cancel-assign-tasks').addEventListener('click', function() {
                    document.getElementById('assign-task-modal').classList.add('hidden');
                });

                // Hiển thị popup chọn đầu việc có sẵn
                document.getElementById('assign-task').addEventListener('click', function() {
                    document.getElementById('check-all-tasks').checked = false;
                    fetchTasks();
                    document.getElementById('assign-task-modal').classList.remove('hidden');
                });


                //=======================tiêu chí=====================================
                // Tạo một Map để lưu trữ tiêu chí theo mã công việc
                const criteriaMap = new Map();

                // Hàm để thêm hoặc cập nhật tiêu chí cho một mã công việc
                function addCriteria(taskCode, criteriaCode) {
                    if (!criteriaMap.has(taskCode)) {
                        criteriaMap.set(taskCode, new Set());
                    }
                    criteriaMap.get(taskCode).add(criteriaCode);
                }

                // Hàm để xóa tiêu chí cho một mã công việc
                function removeCriteria(taskCode, criteriaCode) {
                    if (criteriaMap.has(taskCode)) {
                        criteriaMap.get(taskCode).delete(criteriaCode);
                        if (criteriaMap.get(taskCode).size === 0) {
                            criteriaMap.delete(taskCode);
                        }
                    }
                }

                // Hàm để lấy danh sách tiêu chí cho một mã công việc
                function getCriteriaForTask(taskCode) {
                    return criteriaMap.has(taskCode) ? Array.from(criteriaMap.get(taskCode)) : [];
                }

                function hasCriteria(taskCode, criteriaCode) {
                    console.log("criteriaMap");
                    console.log(criteriaMap);
                    if (!criteriaMap.has(taskCode)) {
                        return false; // Mã công việc không tồn tại trong criteriaMap
                    }

                    const criteriaSet = criteriaMap.get(taskCode);
                    return criteriaSet.has(criteriaCode); // Kiểm tra xem tiêu chí có trong Set không
                }

                let existingCriteriaCodes = [];
                let selectedCriteriaByTaskCode = {};
                const createCriteriaModal = document.getElementById('create-criteria-modal');
                const assignCriteriaModal = document.getElementById('assign-criteria-modal');
                const assignOrganizationsModal = document.getElementById('assign-organizations-modal');
                const taskSelect = document.getElementById('task-select');
                const taskAssignSelect = document.getElementById('task-assign-select');
                const createCriteriaBtn = document.getElementById('create-criteria'); // Nút mở modal
                const saveNewCriteriaBtn = document.getElementById('save-new-criteria');
                const cancelCreateCriteriaBtn = document.getElementById('cancel-create-criteria');
                const cancelAssignCriteriaBtn = document.getElementById('cancel-assign-criteria');
                const cancelOrganizationsBtn = document.getElementById('cancel-organizations-criteria');

                async function checkTaskCodeInDBCriteria(criteriaCode) {
                    try {
                        document.getElementById('loading-spinner').classList.remove('hidden');
                        const response = await fetch(`/api/check-criteria-code/${criteriaCode}`);
                        const data = await response.json();
                        return data.exists;
                    } catch (error) {
                        console.error('Error checking task code:', error);
                        return false;
                    } finally {
                        document.getElementById('loading-spinner').classList.add('hidden'); // Ẩn spinner
                    }
                }

                function hideModal() {
                    createCriteriaModal.classList.add('hidden');
                }

                function hideModalAssign() {
                    assignCriteriaModal.classList.add('hidden');
                }
                function hideModalOr() {
                    assignOrganizationsModal.classList.add('hidden');
                }

                cancelCreateCriteriaBtn.addEventListener('click', hideModal);
                cancelAssignCriteriaBtn.addEventListener('click', hideModalAssign);
                cancelOrganizationsBtn.addEventListener('click', hideModalOr);
                document.getElementById('create-criteria').addEventListener('click', function() {
                    const tasksInputs = document.querySelectorAll('input[name="tasks[]"]');
                    const tasksArray = Array.from(tasksInputs).map(input => input.value);

                    taskSelect.innerHTML = ''; // Xóa nội dung cũ
                    tasksArray.forEach(task => {
                        const [taskCode, taskName, category, organization, taskResult] = task.split(
                            '|'); // Tách từng task thành các thuộc tính
                        const option = document.createElement('option');
                        option.value = taskCode;
                        option.setAttribute('data-result', taskResult);

                        option.textContent = taskName;
                        taskSelect.appendChild(option);
                    });
                    document.getElementById('create-criteria-modal').classList.remove('hidden');
                });

                document.getElementById('new-criteria-code').addEventListener('blur', async function() {
                    const taskCode = this.value;
                    const exists = await checkTaskCodeInDBCriteria(taskCode);

                    if (exists) {
                        alert('Mã code đã tồn tại trong CSDL. Vui lòng nhập mã code khác.');
                        this.value = ''; // Xóa giá trị nhập
                    }
                    if (existingCriteriaCodes.includes(taskCode)) {
                        alert('Mã code đã tồn tại. Vui lòng nhập mã code khác.');
                        return;
                    }
                });


                document.getElementById('save-new-criteria').addEventListener('click', function() {
                    const taskCode = document.getElementById('task-select');
                    const criteriaCode = document.getElementById('new-criteria-code').value;
                    const criteriaName = document.getElementById('new-criteria-name').value;
                    const selectedOption = taskCode.options[taskCode.selectedIndex];
                    const selectedDataResult = selectedOption.getAttribute('data-result');
                    console.log("selectedDataResult");
                    console.log(selectedOption);
                    // Validate fields
                    if (!criteriaCode || !criteriaName) {
                        alert('Vui lòng điền tất cả các trường bắt buộc.');
                        return;
                    }

                    existingCriteriaCodes.push(criteriaCode);
                    const criteriaHTML = `
                        <tr>
                            <td class="py-2 px-4 border-b">${taskCode.value}</td>
                            <td class="py-2 px-4 border-b">${criteriaCode}</td>
                            <td class="py-2 px-4 border-b">${criteriaName}</td>
                            <td class="py-2 px-4 border-b">${selectedDataResult}</td>
                            <td class="py-2 px-4 border-b">
                                <button type="button" class="bg-blue-500 text-white px-2 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300  remove-criteria">Xóa</button>
                                <input type="hidden" name="criterias[]" value="${taskCode.value}|${criteriaCode}|${criteriaName}|${selectedDataResult}">
                            </td>
                        </tr>
                    `;

                    document.getElementById('criteria-list').insertAdjacentHTML('beforeend', criteriaHTML);
                    document.getElementById('create-criteria-modal').classList.add('hidden');
                    document.getElementById('create-criteria-form').reset();
                    checkTasksAndToggleButtons();
                });
                document.getElementById('criteria-list').addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-criteria')) {
                        const row = e.target.closest('tr');
                        const criteriaCode = row.cells[1].innerText.trim();
                        const taskCode = row.cells[0].innerText.trim();
                        removeCriteria(taskCode, criteriaCode);
                        // Xóa mã công việc khỏi mảng
                        existingCriteriaCodes = existingCriteriaCodes.filter(code => code !== taskCode);

                        // Xóa hàng công việc khỏi bảng
                        row.remove();
                    }
                });

                // Hiển thị popup chọn đầu việc có sẵn
                document.getElementById('assign-criteria').addEventListener('click', function() {
                    const tasksInputs = document.querySelectorAll('input[name="tasks[]"]');
                    const tasksArray = Array.from(tasksInputs).map(input => input.value);

                    taskAssignSelect.innerHTML = ''; // Xóa nội dung cũ
                    tasksArray.forEach(task => {
                        const [taskCode, taskName, category, organization, taskResult] = task.split(
                            '|'); // Tách từng task thành các thuộc tính
                        const option = document.createElement('option');
                        option.value = taskCode;
                        option.setAttribute('data-result', taskResult);
                        option.textContent = taskName;
                        taskAssignSelect.appendChild(option);
                    });
                    document.getElementById('check-all-criteria').checked = false;
                    // fetchTasks();
                    fetchCriteria();
                    document.getElementById('assign-criteria-modal').classList.remove('hidden');
                });
                // Khi checkbox "Chọn tất cả" thay đổi trạng thái
                document.getElementById('check-all-criteria').addEventListener('change', function(event) {
                    const checked = event.target.checked;
                    document.querySelectorAll('#existing-criteria input.criteria-checkbox').forEach(
                        checkbox => {
                            checkbox.checked = checked;
                        });
                });

                // Khi người dùng gõ vào ô tìm kiếm
                document.getElementById('search-criteria').addEventListener('input', function(event) {
                    const query = event.target.value;
                    console.log(query);
                    fetchCriteria(query);
                });

                function fetchCriteria(query = '') {
                    fetch(`{{ route('criteria.search') }}?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            const tasksTableBody = document.getElementById('existing-criteria');
                            tasksTableBody.innerHTML = ''; // Xóa nội dung hiện tại
                            const taskCodeAssign = document.getElementById('task-assign-select');

                            const selectedOption = taskAssignSelect.options[taskAssignSelect.selectedIndex];
                            const selectedDataResult = selectedOption.getAttribute('data-result');
                            console.log('Selected option data-result:', selectedDataResult);

                            // Kiểm tra xem có dữ liệu không
                            if (!data.criteria || data.criteria.length === 0) {
                                const row = document.createElement('tr');
                                const cell = document.createElement('td');
                                cell.colSpan = 4; // Chiếm toàn bộ số cột
                                cell.textContent = 'Không có đầu việc nào';
                                row.appendChild(cell);
                                tasksTableBody.appendChild(row);
                                return;
                            }

                            data.criteria.forEach(task => {
                                const code = task.code;
                                console.log("existingCriteriaCodes");
                                console.log(existingCriteriaCodes);

                                var hasCri = hasCriteria(taskCodeAssign.value, code);
                                console.log("hasCriteriaCodes");
                                console.log(hasCri);
                                if (hasCri) {
                                    return;
                                }
                                const row = document.createElement('tr');

                                // Checkbox
                                const checkboxCell = document.createElement('td');
                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.id = `criteria-${task.code}`;
                                checkbox.value = task.code;
                                checkbox.classList.add('criteria-checkbox');
                                checkboxCell.appendChild(checkbox);

                                // Tên công việc
                                const taskCodeAssignCell = document.createElement('td');
                                taskCodeAssignCell.textContent = taskCodeAssign.value;
                                taskCodeAssignCell.classList.add('criteria-task-code-assign');
                                // Mã công việc
                                const codeCell = document.createElement('td');
                                codeCell.textContent = task.code;
                                codeCell.classList.add('criteria-code-assign');

                                // Tên công việc
                                const nameCell = document.createElement('td');
                                nameCell.textContent = task.name;
                                nameCell.classList.add('criteria-name-assign');

                                const dataResultCell = document.createElement('td');
                                dataResultCell.textContent = selectedDataResult;
                                dataResultCell.classList.add('criteria-result-assign');


                                row.appendChild(checkboxCell);
                                //row.appendChild(taskCodeAssignCell);
                                row.appendChild(codeCell);
                                row.appendChild(nameCell);
                                // row.appendChild(dataResultCell);


                                tasksTableBody.appendChild(row);
                            });
                        })
                        .catch(error => console.error('Error fetching tasks:', error));
                }
                document.getElementById('assign-criteria-save').addEventListener('click', function() {
                    const selectedCheckboxes = document.querySelectorAll(
                        '#existing-criteria input[type="checkbox"]:checked');
                    const taskCodeAssign = document.getElementById('task-assign-select');
                    const selectedOption = taskAssignSelect.options[taskAssignSelect.selectedIndex];
                    const selectedDataResult = selectedOption.getAttribute('data-result');

                    const taskCode = taskCodeAssign.value;

                    selectedCheckboxes.forEach(checkbox => {
                        const row = checkbox.closest('tr'); // Lấy hàng chứa checkbox
                        const criteriaCode = row.querySelector('.criteria-code-assign').textContent;
                        const criteriaName = row.querySelector('.criteria-name-assign').textContent;
                        const taskCode = taskCodeAssign.value;
                        const requiredResult = selectedDataResult;
                        existingCriteriaCodes.push(criteriaCode);


                        var hasCri = hasCriteria(taskCode, criteriaCode);
                        if (!hasCri) {
                            addCriteria(taskCode, criteriaCode);
                        }

                        const criteriaHTML = `
                                <tr>
                                        <td class="py-2 px-4 border-b">${taskCode}</td>
                                        <td class="py-2 px-4 border-b">${criteriaCode}</td>
                                        <td class="py-2 px-4 border-b">${criteriaName}</td>
                                        <td class="py-2 px-4 border-b">${requiredResult}</td>
                                        <td class="py-2 px-4 border-b">
                                            <button type="button" class="bg-blue-500 text-white px-2 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300  remove-criteria">Xóa</button>
                                            <input type="hidden" name="criterias[]" value="${taskCode}|${criteriaCode}|${criteriaName}|${requiredResult}">
                                        </td>
                                    </tr>
                            `;

                        document.getElementById('criteria-list').insertAdjacentHTML('beforeend',
                            criteriaHTML);
                    });
                    checkTasksAndToggleButtons();
                    // Đóng modal
                    document.getElementById('assign-criteria-modal').classList.add('hidden');
                });

                document.getElementById('task-assign-select').addEventListener('change', function() {
                    // Lấy taskcode hiện tại từ select box
                    const taskCode = this.value;

                    // Cập nhật danh sách criteria dựa trên taskcode mới
                    fetchCriteria();
                });

                /////=============================Giao việc====================================/////


                const organizationsMap = new Map();
                let taskCodeRow;
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
                // // Hiển thị ô tìm kiếm khi checkbox "Khác" được chọn
                // otherFilterCheckbox.addEventListener('change', function () {
                //     if (this.checked) {
                //         searchOtherSection.classList.remove('hidden');
                //     } else {
                //         searchOtherSection.classList.add('hidden');
                //     }
                // });

                // Hiển thị popup chọn đầu việc có sẵn

                document.getElementById('task-list').addEventListener('click', function(e) {
         
                    //taskCodeRow
                    if (e.target.classList.contains('assign-task-organizations')) {
                        var row = e.target.closest('tr');
        
                        // Lấy dữ liệu từ các ô trong dòng
                        var cells = row.getElementsByTagName('td');
                        console.log(cells);
                        var taskCode = cells[0].textContent.trim(); // Dữ liệu từ cột đầu tiên
                        var taskName = cells[1].textContent.trim(); // Dữ liệu từ cột thứ hai
                        taskCodeRow = taskCode;
                        // if (criteriaMap.size === 0) {
                        //     alert("Vui lòng chọn tiêu chí cho đầu công việc!");
                        //     return;
                        // }
                        document.getElementById('check-all-organizations').checked = false;
                        document.querySelectorAll('#existing-organizations input.organization-checkbox').forEach(
                        checkbox => {
                            checkbox.checked = false;
                        });
                        document.getElementById('assign-organizations-modal').classList.remove('hidden');
                    }
                });
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
                                var hasCri = hasOrganizations(taskCodeRow, code);
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
                                taskCodeAssignCell.textContent = taskCodeRow;
                                taskCodeAssignCell.classList.add('task-code');
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
                                var hasCri = hasOrganizations(taskCodeRow, code);
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
                                taskCodeAssignCell.textContent = taskCodeRow;
                                taskCodeAssignCell.classList.add('task-code');
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

                document.getElementById('assign-organizations-save').addEventListener('click', function() {
                    const selectedCheckboxes = document.querySelectorAll(
                        '#existing-organizations input[type="checkbox"]:checked');
                    console.log(selectedCheckboxes);
                    console.log(selectedCheckboxes);
                    selectedCheckboxes.forEach(checkbox => {
                        const row = checkbox.closest('tr'); // Lấy hàng chứa checkbox
                        const organizationCode = row.querySelector('.organization-code').textContent;
                        const organizationName = row.querySelector('.organization-name').textContent;
                        const organizationEmail = row.querySelector('.organization-email').textContent;
                        const organizationPhone = row.querySelector('.organization-phone').textContent;
                        const taskCode = row.querySelector('.task-code').textContent;
                        const organizationHTML = `
                            <tr>
                                <td class="py-2 px-4 border-b">
                                    <input type="hidden" name="organizations[]" value="${taskCode}|${organizationCode}|${organizationName}|${organizationEmail}|${organizationPhone}">
                                </td>
                            </tr>
                            `;

                        document.getElementById('existing-organizations-document').insertAdjacentHTML('beforeend',
                        organizationHTML);
                    });
                    // Đóng modal
                    document.getElementById('assign-organizations-modal').classList.add('hidden');
                });
            });
        </script>
    </div>
@endsection
