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
        session(['success' => null])
        @endphp
        {{-- <button id="filterToggle" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300 mb-4">
            Lọc/Filter
        </button> --}}

        <!-- Search Form type-->
        <form method="GET" action="{{ route('tasks.byType', $type) }}" id="filterForm">

            <div class="mb-6 flex flex-wrap gap-4 mb-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="document_id" class="block text-gray-700 font-medium mb-2">Tên văn bản:</label>
                    <select id="document_id" name="document_id" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="">Chọn văn bản thực hiện</option>

                        @foreach($documents as $item)
                           
                            <option value="{{ $item->id }}" {{ request('document_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->document_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="organization_type_id" class="block text-gray-700 font-medium mb-2">Loại cơ quan:</label>
                    <select id="organization_type_id" name="organization_type_id" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="">Chọn loại cơ quan thực hiện</option>
                        @foreach($organizationsType as $organization)
                            <option value="{{ $organization->id }}" {{ request('organization_type_id') == $organization->id ? 'selected' : '' }}>
                                {{ $organization->type_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="organization_id" class="block text-gray-700 font-medium mb-2">Tên cơ quan:</label>
                    <select name="organization_id" id="parent_id" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="" {{ old('organization_id') ? '' : 'selected' }}>Chọn cơ quan tổ chức cấp trên</option>
                    </select>
                </div>
    
                <div class="flex-1 min-w-[200px]">
                    <label for="execution_time_from" class="block text-gray-700 font-medium mb-2">Từ ngày</label>
                    <input type="date" id="execution_time_from" placeholder="dd-mm-yyyy"
                            min="1997-01-01" max="2100-12-31" name="execution_time_from" value="{{ request('execution_time_from') }}"
                            class="border border-gray-300 rounded-lg p-2 w-full" placeholder="Ngày phát hành">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="execution_time_to" class="block text-gray-700 font-medium mb-2">Đến ngày</label>
                    <input type="date" id="execution_time_to" placeholder="dd-mm-yyyy"
                            min="1997-01-01" max="2100-12-31" name="execution_time_to" value="{{ request('execution_time_to') }}"
                            class="border border-gray-300 rounded-lg p-2 w-full" placeholder="Ngày phát hành">
                </div>

            </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('tasks.create.byType', ['type' => $type]) }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4"> Thêm mới</a>

            <button type="submit"
            class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">
            Tìm kiếm
        </button>

        </div>
    </form>
      
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                    @if( $type == 'target')
                    <tr>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">STT</th>
                        <th style="width: 450px;" class="py-3 px-6 text-left text-gray-700 font-medium">Tên</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Đơn vị tính</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Chỉ tiêu</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Ngày bắt đầu - kết thúc</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Loại</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                           Chi tiết
                        </th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                           Cập nhật
                        </th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                           Xóa 
                        </th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                            Lịch sử 
                        </th>
                    </tr>
                    @else
                    <tr>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">STT</th>
                        <th style="width: 290px;" class="py-3 px-6 text-left text-gray-700 font-medium">Tên nhiệm vụ</th>
                        <th style="width: 100px" class="py-3 px-6 text-left text-gray-700 font-medium">Kết quả</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Nhóm nhiệm vụ</th>
                        <th  style="width: 80px" class="py-3 px-6 text-left text-gray-700 font-medium">Có thời hạn/thường xuyên</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Ngày bắt đầu - kết thúc</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                           Chi tiết
                        </th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                           Cập nhật
                        </th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                           Xóa 
                        </th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                            Lịch sử 
                        </th>
                    </tr>
                    @endif
                </thead>
                <tbody>
                    @if( $type == 'target')
                    @foreach ($taskTargets as $index => $item)
                  
                        <tr class="border-b border-gray-200">
                            <td class="py-3 border border-gray-300 px-6 text-center">{{ $index + $taskTargets->firstItem() }}</td>
                            <td style="width: 450px;" class="py-3 border border-gray-300 px-6">{{ $item->name }}</td>
                            <td class="py-3 border border-gray-300 px-6">
                                {{ $item->getUnitName() }}
                            </td>
                            <td class="py-3 border border-gray-300 px-6"> {{ $item->target }}</td>
                            <td class="py-3 border border-gray-300 px-6"> {{ $item->getDateFromToTextAttribute() }}</td>
                            <td class="py-3 border border-gray-300 px-6"> {{ $item->getTypeTextAttributeTarget() }}</td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"
                                    onclick="window.location.href='{{ route('tasks.show-details', ['code' => $item->code, 'type' => $item->type]) }}'">
                                    <i class="fas fa-info-circle"></i> <!-- Biểu tượng cho "Chi tiết" -->
                                </button>
                            </td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                                onclick="window.location.href='{{ route('tasks.edit.taskTarget',['code' => $item->code, 'type' => $item->type]) }}'">
                                <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                            </button>
                            </td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                               

                                <form id="delete-form-{{ $index + $taskTargets->firstItem() }}" action="{{ route('tasks.destroy.tasktarget', ['code' => $item->code, 'type' => $item->type]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                        onclick="confirmDelete({{ $index + $taskTargets->firstItem() }})">
                                        <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                    </button>
                                </form>
                            </td>

                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <button data-document-id="{{ $item->document_id }}"
                                    data-task-id="{{ $item->code }}"
                                    class="history-task bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                      <i class="fa fa-history"></i>
                                </button>
                            </td>
                            
                        </tr>
                    @endforeach
                    @else
                    @foreach ($taskTargets as $index => $item)
                  
                    <tr class="border-b border-gray-200">
                        <td class="py-3 border border-gray-300 px-6 text-center">{{ $index + $taskTargets->firstItem() }}</td>
                        <td style="width: 290px;" class="py-3 border border-gray-300 px-6">{{ $item->name }}</td>
                        <td style="width: 100px;" class="py-3 border border-gray-300 px-6">{{ $item->request_results }}</td>
                        <td class="py-3 border border-gray-300 px-6">
                                 {{  $item->getGroupName() }}
                       
                        </td>
                        <td style="width: 80px" class="py-3 border border-gray-300 px-6"> {{ $item->getTypeTextAttributeTime() }}</td>
                        <td class="py-3 border border-gray-300 px-6"> {{ $item->getDateFromToTextAttribute() }}</td>
                        <td class="py-3 border border-gray-300 px-6 text-center">
                            <button class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"
                                onclick="window.location.href='{{ route('tasks.show-details', ['code' => $item->code, 'type' => $item->type]) }}'">
                                <i class="fas fa-info-circle"></i> <!-- Biểu tượng cho "Chi tiết" -->
                            </button>
                        </td>
                        <td class="py-3 border border-gray-300 px-6 text-center">
                            <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                            onclick="window.location.href='{{ route('tasks.edit.taskTarget',['code' => $item->code, 'type' => $item->type]) }}'">
                            <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                        </button>
                        </td>
                        <td class="py-3 border border-gray-300 px-6 text-center">
                           

                            <form id="delete-form-{{ $index + $taskTargets->firstItem() }}" action="{{ route('tasks.destroy.tasktarget', ['code' => $item->code, 'type' => $item->type]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                    onclick="confirmDelete({{ $index + $taskTargets->firstItem() }})">
                                    <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                </button>
                            </form>
                        </td>

                        <td class="py-3 border border-gray-300 px-6 text-center">
                            <button data-document-id="{{ $item->document_id }}"
                                data-task-id="{{ $item->code }}"
                                class="history-task bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                  <i class="fa fa-history"></i>
                            </button>
                        </td>
                        
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
     <div id="history-change-modal" style="z-index: 9999;" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-3/4">
            <h2 class="text-xl font-bold mb-4">Lịch sử cập nhật kết quả</h2>
            <div class="mb-4 overflow-x-auto" style="
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
            <button type="button" id="cancel-history-changes" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">Hủy</button>
        </div>
    </div>
    <script>
        document.getElementById('filterToggle').addEventListener('click', function() {
            const filterForm = document.getElementById('filterForm');
            filterForm.classList.toggle('hidden');
        });
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
         document.getElementById('organization_type_id').addEventListener('change', function () {
            var organizationTypeId = this.value;
            
            // Gửi yêu cầu AJAX đến server để lấy danh sách organizations
            fetch(`/get-organizations/${organizationTypeId}`)
                .then(response => response.json())
                .then(data => {
                    // Làm rỗng danh sách `parent_id`
                    var parentSelect = document.getElementById('parent_id');
                    parentSelect.innerHTML = '<option value="" disabled selected>Chọn cơ quan tổ chức cấp trên</option>';

                    // Thêm các tùy chọn mới
                    data.forEach(function (organization) {
                        var option = document.createElement('option');
                        option.value = organization.id;
                        option.text = organization.name;
                        parentSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        });
        
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
                        if(history.type_cycle == 1){
                            cycle_text = 'Tuần';
                        }else if(history.type_cycle == 2){
                            cycle_text = 'Tháng';
                        }else if(history.type_cycle == 3){
                            cycle_text = 'Quý';
                        }else if(history.type_cycle == 4){
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
                        resultCell.textContent =  text_result_cycle;
                        row.appendChild(resultCell);


                        // Thêm hàng vào bảng
                        tableBody.appendChild(row);
                    });
                }
          });
            
    </script>
    
@endsection
