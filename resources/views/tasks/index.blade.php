@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                {!! Breadcrumbs::render('tasks.byType', $type) !!}
            </ol>
        </nav>
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
        @php
            session(['success' => null]);
            $text = 'chỉ tiêu';
            if ($type == 'task') {
                $text = 'nhiệm vụ';
            }
        @endphp

        {{-- <button id="filterToggle" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300 mb-4">
            Lọc/Filter
        </button> --}}

        <!-- Search Form type-->
        <form method="GET" action="{{ route('tasks.byType', $type) }}" id="filterForm">

            <div class="mb-6 flex flex-wrap gap-4 mb-4">
                <div class="flex-1 min-w-[200px] relative">
                    <label for="document_code" class="block text-gray-700 font-medium mb-2">Số hiệu văn bản</label>
                    <input type="text" id="document_code" name="document_code" placeholder="Số hiệu văn bản"
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">

                    <!-- Kết quả tìm kiếm sẽ được hiển thị ngay bên dưới input -->
                    <div id="search-results"
                        class="absolute bg-white w-full border border-gray-300 rounded-lg shadow-lg mt-1 z-10 hidden">
                        <ul class="list-none p-0 m-0"></ul>
                    </div>
                </div>
                {{-- <div class="flex-1 min-w-[200px]">
                    <label for="document_id" class="block text-gray-700 font-medium mb-2">Tên văn bản:</label>
                    <select id="document_id" name="document_id" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="">Chọn văn bản thực hiện</option>
                        @foreach ($documents as $item)
                            <option value="{{ $item->id }}" {{ request('document_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->document_name }}
                            </option>
                        @endforeach
                    </select>               
                </div> --}}
                <div class="flex-1 min-w-[200px]">
                    <label for="organization_id" class="block text-gray-700 font-medium mb-2">Cơ quan ban hành:</label>
                    <select id="organization_id" name="organization_id"
                        class="border border-gray-300 rounded-lg p-2 w-full select2">
                        <option value="">Chọn cơ quan ban hành</option>
                        @foreach ($organizations as $organization)
                            <option value="{{ $organization->id }}"
                                {{ request('organization_id') == $organization->id ? 'selected' : '' }}>
                                {{ $organization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="type_id" class="block text-gray-700 font-medium mb-2">Nhóm {{ $text }}:</label>
                    <select id="type_id" name="type_id" class="border border-gray-300 rounded-lg p-2 w-full select2">
                        <option value="" data-code="">Chọn loại {{ $text }}</option>
                        @foreach ($typeTask as $item)
                            <option value="{{ $item->id }}" {{ old('type_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-6 flex flex-wrap gap-4 mb-4">
                @if()
                <div class="flex-1 min-w-[200px]">
                    <label for="organization_id" class="block text-gray-700 font-medium mb-2">Loại nhiệm vụ:</label>
                    <select id="organization_id" name="organization_id" class="border border-gray-300 rounded-lg p-2 w-full select2">
                        <option value="" disabled selected>-- Chọn loại chỉ tiêu --</option>
                        <option value="single">Đơn</option> <!-- "Đơn" -> "single" -->
                        <option value="aggregate">Tổng hợp</option> <!-- "Tổng hợp" -> "aggregate" -->
                    </select>
                </div>
                @else
                    <div class="flex-1 min-w-[200px]">
                        <label for="target_type" class="block text-gray-700 font-medium mb-2">Loại chỉ tiêu:</label>
                        <select id="target_type" name="target_type"
                            class="border border-gray-300 rounded-lg p-2 w-full select2">
                            <option value="" disabled selected>Chọn loại chỉ tiêu</option>
                            <option value="single">Đơn</option> <!-- "Đơn" -> "single" -->
                            <option value="aggregate">Tổng hợp</option> <!-- "Tổng hợp" -> "aggregate" -->
                        </select>
                    </div>
                @endif
                <div class="flex-1 min-w-[200px]">
                    <label for="organization_id" class="block text-gray-700 font-medium mb-2">Ngày hoàn thành:</label>
                    <select id="organization_id" name="organization_id"
                        class="border border-gray-300 rounded-lg p-2 w-full select2">
                        <option value="">Chọn cơ quan ban hành</option>
                        @foreach ($organizations as $organization)
                            <option value="{{ $organization->id }}"
                                {{ request('organization_id') == $organization->id ? 'selected' : '' }}>
                                {{ $organization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="organization_id" class="block text-gray-700 font-medium mb-2">Tiến độ:</label>
                    <select id="organization_id" name="organization_id"
                        class="border border-gray-300 rounded-lg p-2 w-full select2">
                        <option value="">Chọn cơ quan ban hành</option>
                        @foreach ($organizations as $organization)
                            <option value="{{ $organization->id }}"
                                {{ request('organization_id') == $organization->id ? 'selected' : '' }}>
                                {{ $organization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="flex-1 min-w-[200px] hidden" id="organization_id">
                    <label for="organization_id" class="block text-gray-700 font-medium mb-2">&nbsp; </label>
                    <select name="organization_id" id="parent_id" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="" {{ old('organization_id') ? '' : 'selected' }}>Chọn cơ quan tổ chức</option>
                    </select>
                </div> --}}
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('tasks.create.byType', ['type' => $type]) }}"
                    class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">
                    Thêm mới</a>

                <button type="submit"
                    class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">
                    Tìm kiếm
                </button>

            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                    @if ($type == 'target')
                        <tr>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">STT</th>
                            <th style="width: 290px;" class="py-3 px-6 text-left text-gray-700 font-medium text-center">Tên
                                chỉ tiêu</th>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Đơn vị tính</th>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Chỉ tiêu</th>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Ngày bắt đầu - kết thúc
                            </th>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Loại</th>
                            <th style="width: 100px;" class="py-3 px-6 text-left text-gray-700 font-medium text-center">Số
                                đơn vị được giao</th>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                                Chi tiết
                            </th>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                                Cập nhật
                            </th>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                                Xóa
                            </th>
                            {{-- <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                            Lịch sử 
                        </th> --}}
                        </tr>
                    @else
                        <tr>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">STT</th>
                            <th style="width: 290px;" class="py-3 px-6 text-left text-gray-700 font-medium text-center">Tên
                                nhiệm vụ</th>
                            <th style="width: 100px" class="py-3 px-6 text-left text-gray-700 font-medium text-center">Kết
                                quả</th>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Nhóm nhiệm vụ</th>
                            <th style="width: 80px" class="py-3 px-6 text-left text-gray-700 font-medium text-center">Có
                                thời hạn/thường xuyên</th>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Ngày bắt đầu - kết thúc
                            </th>
                            <th style="width: 100px;" class="py-3 px-6 text-left text-gray-700 font-medium text-center">Số
                                đơn vị được giao</th>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                                Chi tiết
                            </th>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                                Cập nhật
                            </th>
                            <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                                Xóa
                            </th>
                            {{-- <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                            Lịch sử 
                        </th> --}}
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @if ($type == 'target')
                        @foreach ($taskTargets as $index => $item)
                            <tr class="border-b border-gray-200">
                                <td class="py-3 border border-gray-300 px-6 text-center">
                                    {{ $index + $taskTargets->firstItem() }}</td>
                                <td style="width: 290px;" class="py-3 border border-gray-300 px-6">{{ $item->name }}
                                </td>
                                <td class="py-3 border border-gray-300 px-6">
                                    {{ $item->getUnitName() }}
                                </td>
                                <td class="py-3 border border-gray-300 px-6"> {{ $item->target }}</td>
                                <td class="py-3 border border-gray-300 px-6"> {{ $item->getDateFromToTextAttribute() }}
                                </td>
                                <td class="py-3 border border-gray-300 px-6"> {{ $item->getTypeTextAttributeTarget() }}
                                </td>
                                <td style="width: 100px;" class="py-3 border border-gray-300 px-6">
                                    {{ $item->countOrganization() }}</td>
                                <td class="py-3 border border-gray-300 px-6 text-center">
                                    <button
                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"
                                        onclick="window.location.href='{{ route('tasks.show-details', ['id' => $item->id, 'type' => $item->type]) }}'">
                                        <i class="fas fa-info-circle"></i> <!-- Biểu tượng cho "Chi tiết" -->
                                    </button>
                                </td>
                                <td class="py-3 border border-gray-300 px-6 text-center">
                                    <button
                                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                                        onclick="window.location.href='{{ route('tasks.edit.taskTarget', ['id' => $item->id, 'type' => $item->type]) }}'">
                                        <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                                    </button>
                                </td>
                                <td class="py-3 border border-gray-300 px-6 text-center">


                                    <form id="delete-form-{{ $index + $taskTargets->firstItem() }}"
                                        action="{{ route('tasks.destroy.tasktarget', ['code' => $item->code, 'type' => $item->type]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                            onclick="confirmDelete({{ $index + $taskTargets->firstItem() }})">
                                            <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                        </button>
                                    </form>
                                </td>

                                {{-- <td class="py-3 border border-gray-300 px-6 text-center">
                                <button data-document-id="{{ $item->document_id }}"
                                    data-task-id="{{ $item->code }}"
                                    class="history-task bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                      <i class="fa fa-history"></i>
                                </button>
                            </td>
                             --}}
                            </tr>
                        @endforeach
                    @else
                        @foreach ($taskTargets as $index => $item)
                            <tr class="border-b border-gray-200">
                                <td class="py-3 border border-gray-300 px-6 text-center">
                                    {{ $index + $taskTargets->firstItem() }}</td>
                                <td style="width: 290px;" class="py-3 border border-gray-300 px-6">{{ $item->name }}
                                </td>
                                <td style="width: 100px;" class="py-3 border border-gray-300 px-6">
                                    @foreach ($workResultTypes as $idx => $result_type)
                                        @continue($type != 'task' && $idx == 4)
                                        @if ($item->result_type == $result_type->key)
                                            {{ $result_type->value }}
                                        @endif
                                    @endforeach
                                </td>
                                <td class="py-3 border border-gray-300 px-6">
                                    {{ $item->getGroupName() }}

                                </td>
                                <td style="width: 80px" class="py-3 border border-gray-300 px-6">
                                    {{ $item->getTypeTextAttributeTime() }}</td>
                                <td class="py-3 border border-gray-300 px-6"> {{ $item->getDateFromToTextAttribute() }}
                                </td>
                                <td style="width: 100px;" class="py-3 border border-gray-300 px-6">
                                    {{ $item->countOrganization() }}</td>
                                <td class="py-3 border border-gray-300 px-6 text-center">
                                    <button
                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"
                                        onclick="window.location.href='{{ route('tasks.show-details', ['id' => $item->id, 'type' => $item->type]) }}'">
                                        <i class="fas fa-info-circle"></i> <!-- Biểu tượng cho "Chi tiết" -->
                                    </button>
                                </td>
                                <td class="py-3 border border-gray-300 px-6 text-center">
                                    <button
                                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                                        onclick="window.location.href='{{ route('tasks.edit.taskTarget', ['id' => $item->id, 'type' => $item->type]) }}'">
                                        <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                                    </button>
                                </td>
                                <td class="py-3 border border-gray-300 px-6 text-center">
                                    <form id="delete-form-{{ $index + $taskTargets->firstItem() }}"
                                        action="{{ route('tasks.destroy.tasktarget', ['code' => $item->code, 'type' => $item->type]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                            onclick="confirmDelete({{ $index + $taskTargets->firstItem() }})">
                                            <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                        </button>
                                    </form>
                                </td>
                                {{-- <td class="py-3 border border-gray-300 px-6 text-center">
                            <button data-document-id="{{ $item->document_id }}"
                                data-task-id="{{ $item->code }}"
                                class="history-task bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                  <i class="fa fa-history"></i>
                            </button>
                        </td> --}}

                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="mt-4">
                {{ $taskTargets->links() }} <!-- Render pagination links -->
            </div>
        </div>
    </div>
    {{-- lich su --}}
    <div id="history-change-modal" style="z-index: 9999;"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-3/4">
            <h2 class="text-xl font-bold mb-4">Lịch sử cập nhật kết quả</h2>
            <div class="mb-4 overflow-x-auto"
                style="
                max-height: 400px;
                overflow-y: auto;
                overflow-x: auto;
                text-align: center;">
                <table id="history-changes-table" class="w-full border border-gray-300 rounded-lg">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">STT</th>
                            <th class="py-2 px-4 border-b">Tiến độ</th>
                            <th class="py-2 px-4 border-b">Mô tả chi tiết</th>
                            <th class="py-2 px-4 border-b">Thời gian</th>
                            <th class="py-2 px-4 border-b">Chu kỳ</th>
                        </tr>
                    </thead>
                    <tbody id="history-changes-tbody" style="text-align: center">
                        <!-- Danh sách chỉ tiêu sẽ được chèn vào đây bằng JavaScript -->
                    </tbody>
                </table>
            </div>
            <button type="button" id="cancel-history-changes"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">Hủy</button>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                allowClear: true
            });
        });
        //============================ Search Input Code ====================================
        $(document).ready(function() {
            $('#document_code').on('keyup', function() {
                var query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: "{{ route('documents.search') }}",
                        type: "GET",
                        data: {
                            'document_code': query
                        },
                        success: function(data) {
                            $('#search-results ul').html('');
                            if (data.length > 0) {
                                $.each(data, function(key, document) {
                                    $('#search-results ul').append(
                                        '<li class="p-2 cursor-pointer hover:bg-gray-200" data-code="' +
                                        document.document_code + '">' + document
                                        .document_code + '</li>');
                                });
                                $('#search-results').removeClass('hidden');
                            } else {
                                $('#search-results ul').append(
                                    '<li class="p-2">Không có kết quả.</li>');
                                $('#search-results').removeClass('hidden');
                            }
                        }
                    });
                } else {
                    $('#search-results').addClass('hidden');
                }
            });
            $(document).on('click', function(event) {
                var selectedCode = $(this).data('code'); // Lấy giá trị từ thuộc tính data-code
                $('#document_code').value = selectedCode; // Gán giá trị vào input
                $('#search-results').addClass('hidden'); // Ẩn danh sách sau khi chọn
            });
            $(document).on('click', '#search-results li', function() {
                $('#document_code').val($(this).text());
                $('#search-results').addClass('hidden');
            });
        });
        //============================End Search Input Code ====================================
        // document.getElementById('filterToggle').addEventListener('click', function() {
        //     const filterForm = document.getElementById('filterForm');
        //     filterForm.classList.toggle('hidden');
        // });
        function confirmDelete(id) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: 'Xác nhận xóa!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Có, xóa!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
        //  document.getElementById('organization_type_id').addEventListener('change', function () {
        //     var organizationTypeId = this.value;

        //     // Gửi yêu cầu AJAX đến server để lấy danh sách organizations
        //     fetch(`/get-organizations/${organizationTypeId}`)
        //         .then(response => response.json())
        //         .then(data => {
        //             // Làm rỗng danh sách `parent_id`
        //             var parentSelect = document.getElementById('parent_id');
        //             parentSelect.innerHTML = '<option value="" disabled selected>Chọn Cơ quan ban hành</option>';

        //             // Thêm các tùy chọn mới
        //             data.forEach(function (organization) {
        //                 var option = document.createElement('option');
        //                 option.value = organization.id;
        //                 option.text = organization.name;
        //                 parentSelect.appendChild(option);
        //             });
        //             var customInput = document.getElementById('organization_id');
        //         customInput.classList.remove('hidden');
        //         })
        //         .catch(error => console.error('Error:', error));
        // });

        document.addEventListener('DOMContentLoaded', function() {
            //============================== Lich su ==========================
            // history-change-cri-modal
            const cancelHistoryBtn = document.getElementById('cancel-history-changes');
            const assignHistoryModal = document.getElementById('history-change-modal');

            function hideModalHistory() {
                assignHistoryModal.classList.add('hidden');
            }

            cancelHistoryBtn.addEventListener('click', hideModalHistory);
            document.querySelectorAll('.history-task').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const documentIdRow = this.getAttribute('data-document-id');
                    const taskCodeRow = this.getAttribute('data-task-id');
                    document.getElementById('history-change-modal').classList.remove('hidden');
                    fetch(`/api/get-history/${taskCodeRow}`)
                        .then(response => response.json())
                        .then(data => {
                            const histories = data.histories;
                            console.log(histories);
                            const tableBody = document.getElementById('history-changes-tbody');

                            populateTable(histories, tableBody);
                        });
                });
            });

            function populateTable(histories, tableBody) {

                // Xóa các hàng cũ nếu có
                tableBody.innerHTML = '';

                // Tạo và chèn các hàng mới từ dữ liệu
                histories.forEach((history, index) => {
                    const row = document.createElement('tr');

                    // Cột STT
                    const sttCell = document.createElement('td');
                    sttCell.classList.add('py-2', 'px-4', 'border-b');
                    sttCell.textContent = index + 1;
                    row.appendChild(sttCell);


                    let cycle_text;
                    if (history.type_cycle == 1) {
                        cycle_text = 'Tuần';
                    } else if (history.type_cycle == 2) {
                        cycle_text = 'Tháng';
                    } else if (history.type_cycle == 3) {
                        cycle_text = 'Quý';
                    } else if (history.type_cycle == 4) {
                        cycle_text = 'Năm';
                    }
                    const text_result_cycle = cycle_text + ' ' + history.number_cycle;
                    // Các cột khác
                    const mappingIdCell = document.createElement('td');
                    mappingIdCell.classList.add('py-2', 'px-4', 'border-b');
                    mappingIdCell.textContent = history.result;
                    row.appendChild(mappingIdCell);

                    const typeSaveCell = document.createElement('td');
                    typeSaveCell.classList.add('py-2', 'px-4', 'border-b');
                    typeSaveCell.textContent = history.description;
                    row.appendChild(typeSaveCell);


                    const descriptionCell = document.createElement('td');
                    descriptionCell.classList.add('py-2', 'px-4', 'border-b');
                    descriptionCell.textContent = history.update_date;
                    row.appendChild(descriptionCell);

                    const resultCell = document.createElement('td');
                    resultCell.classList.add('py-2', 'px-4', 'border-b');
                    resultCell.textContent = text_result_cycle;
                    row.appendChild(resultCell);


                    // Thêm hàng vào bảng
                    tableBody.appendChild(row);
                });
            }
        });
    </script>

@endsection
