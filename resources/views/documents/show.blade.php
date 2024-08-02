@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Chi tiết văn bản - {{ $document->document_name }}</h1>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <h5 class="text-2xl font-semibold mb-6">{{ $document->document_name }}</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 font-medium w-1/3">Mã văn bản:</span>
                        <span class="text-gray-900 w-2/3">{{ $document->document_code }}</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 font-medium w-1/3">Đơn vị phát hành:</span>
                        <span class="text-gray-900 w-2/3">{{ $document->issuingDepartment->name }}</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 font-medium w-1/3">Ngày phát hành:</span>
                        <span class="text-gray-900 w-2/3">{{ $document->getReleaseDateFormattedAttribute() }}</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 font-medium w-1/3">Người tạo:</span>
                        <span class="text-gray-900 w-2/3">{{ $document->creator }}</span>
                    </div>
                </div>
                <div class="mt-6">
                    <h5 class="text-xl font-semibold mb-4">Tệp đính kèm:</h5>
                    <ul>
                        @foreach ($document->files as $file)
                            <li class="file-item flex items-center mb-2"
                                data-file-type="{{ mime_content_type(storage_path('app/public/' . $file->file_path)) }}">
                                <img class="file-icon w-12 h-12 mr-2" src="" alt="File icon">
                                <a href="{{ asset('storage/' . $file->file_path) }}" class="text-blue-500 hover:underline"
                                    download>{{ $file->file_name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-6">
                    <h5 class="text-xl font-semibold mb-4">Danh sách đầu công việc:</h5>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>

                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mã đầu việc</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tên đầu việc</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Chu kỳ</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kết quả yêu cầu</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Trạng thái</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($document->taskDocuments as $task)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="hidden" id="documentId" name="documentId"
                                                value="{{ $document->id }}">
                                            <input type="hidden" id="taskCode" name="taskCode"
                                                value="{{ $task->task_code }}">
                                            <input type="hidden" id="taskId" name="taskId"
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
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $task->task_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $task->getCycleAttribute() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $task->required_result }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $task->getStatusAttribute() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
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
                                                                Mã chỉ tiêu</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Tên chỉ tiêu</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Kết quả yêu cầu</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($criterias[$task->task_code] as $criterion)
                                                            <tr>
                                                                <td class="px-6 py-4 whitespace-nowrap"></td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    {{ $criterion->CriteriaCode }}</td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    {{ $criterion->CriteriaName }}</td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    {{ $criterion->RequestResult }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="2" class="px-6 py-4 text-gray-500">Không có
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
                                        <td colspan="3" class="px-6 py-4 text-gray-500">Không có đầu công việc nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="{{ route('documents.index') }}"
                    class="mt-15 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Back
                    to List</a>
            </div>
        </div>
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
    <div id="assign-organizations-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-3/4">
            <h2 class="text-xl font-bold mb-4">Danh sách chỉ tiêu</h2>

            <!-- Phần chọn bộ lọc -->
            <div class="mb-4">
                <div class="flex space-x-4">
                    <div class="flex items-center">
                        <input type="radio" id="unit-filter" name="filter" class="filter-radio" value="unit-filter">
                        <label for="unit-filter" class="text-gray-700 text-sm font-medium ml-2">Các đơn vị trực
                            thuộc</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="all-provinces-filter" name="filter" class="filter-radio"
                            value="all-provinces-filter">
                        <label for="all-provinces-filter" class="text-gray-700 text-sm font-medium ml-2">Tất cả các
                            tỉnh</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="all-units-filter" name="filter" class="filter-radio"
                            value="all-units-filter">
                        <label for="all-units-filter" class="text-gray-700 text-sm font-medium ml-2">Tất cả các bộ</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="other-filter" name="filter" class="filter-radio"
                            value="other-filter">
                        <label for="other-filter" class="text-gray-700 text-sm font-medium ml-2">Khác</label>
                    </div>
                </div>
            </div>


            <!-- Phần tìm kiếm chỉ tiêu -->
            <div class="mb-4">
                <input type="text" id="search-organizations"
                    class="form-input w-full border border-gray-300 rounded-lg p-2"
                    placeholder="Tìm kiếm cơ quan/tổ chức">
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

            <button type="button" id="assign-organizations-save"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Gán</button>
            <button type="button" id="cancel-organizations-criteria"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">Hủy</button>
        </div>
    </div>
    <script>
        //=============================Row====================
        function toggleRow(taskId) {
            const criteriaRow = document.getElementById(`criteria-row-${taskId}`);
            const icon = criteriaRow.previousElementSibling.querySelector('svg');
            if (criteriaRow) {
                criteriaRow.classList.toggle('hidden');
                icon.classList.toggle('rotate-90');
            }
        }

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

        document.addEventListener('DOMContentLoaded', function() {
            const fileList = document.querySelectorAll('.file-item');

            fileList.forEach(function(item) {
                const fileType = item.dataset.fileType;
                const iconUrl = getFileIcon(fileType);

                const iconElement = item.querySelector('.file-icon');
                if (iconElement) {
                    iconElement.src = iconUrl;
                }
            });



            //===========================================Giao viec===================================================

            const searchOtherSection = document.getElementById('search-organizations');
            searchOtherSection.classList.add('hidden');
            const organizationsMap = new Map();
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

            let documentID;
            let taskCode;
            let taskId;
            document.querySelectorAll('.open-popup-button').forEach(button => {
                button.addEventListener('click', function() {

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
            document.getElementById('close-assigned-organizations-modal').addEventListener('click', function() {
                document.getElementById('assigned-organizations-modal').classList.add('hidden');
            });

            document.getElementById('assign-organizations-save').addEventListener('click', function() {
                const selectedCheckboxes = document.querySelectorAll(
                    '#existing-organizations input[type="checkbox"]:checked');
                const selectedOrganizations = [];

                selectedCheckboxes.forEach(checkbox => {
                    const row = checkbox.closest('tr'); // Lấy hàng chứa checkbox
                    const organizationCode = row.querySelector('.organization-code').textContent;
                    selectedOrganizations.push(organizationCode);
                });

                // Gửi request Ajax
                fetch('/save-assign-organizations', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            documentId: documentID,
                            taskCode: taskCode,
                            organizations: selectedOrganizations
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Organizations assigned successfully.');
                            // Đóng modal
                            document.getElementById('assign-organizations-modal').classList.add(
                                'hidden');
                        } else {
                            console.log('Error:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

            });

            // Khi checkbox "Chọn tất cả" thay đổi trạng thái
            document.getElementById('check-all-organizations').addEventListener('change', function(event) {
                const checked = event.target.checked;
                document.querySelectorAll('#existing-organizations input.organization-checkbox').forEach(
                    checkbox => {
                        checkbox.checked = checked;
                    });
            });

            document.getElementById('cancel-organizations-criteria').addEventListener('click',
            function hideModalOr() {
                document.getElementById('assign-organizations-modal').classList.add('hidden');
            });

            const filterRadios = document.querySelectorAll('.filter-radio');

            // Thêm sự kiện change cho tất cả các radio button
            filterRadios.forEach(radio => {
                radio.addEventListener('change', function() {
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
        });
    </script>
@endsection
