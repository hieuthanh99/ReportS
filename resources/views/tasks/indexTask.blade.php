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
        @php
        session(['success' => null])
        @endphp
        <!-- Search Form -->
        <form method="GET" action="{{ route('tasks.index') }}" class="">
            <div class="mb-6 flex flex-wrap gap-4 mb-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="document_name" class="block text-gray-700 font-medium mb-2">Tên nhiệm vụ:</label>
                    <input type="text" id="document_name" name="document_name" value="{{ request('document_name') }}"  placeholder="Tên văn bản"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="organization_id" class="block text-gray-700 font-medium mb-2">Phân loại:</label>
                    <select id="organization_id" name="organization_id" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="">Chọn đơn vị thực hiện</option>
                        @foreach($organizations as $organization)
                            <option value="{{ $organization->id }}" {{ request('organization_id') == $organization->id ? 'selected' : '' }}>
                                {{ $organization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex-1 min-w-[200px]">
                    <label for="execution_time" class="block text-gray-700 font-medium mb-2">Chu kỳ báo cáo:</label>
                    <select id="new-task-reporting-cycle" name="reporting_cycle"
                            class="form-select w-full border border-gray-300 rounded-lg p-2">
                            <option value="">Lựa chọn chu kỳ</option>
                            <option value="1">Tuần</option>
                            <option value="2">Tháng</option>
                            <option value="3">Quý</option>
                            <option value="4">Năm</option>
                        </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label for="type" class="block text-gray-700 font-medium mb-2">Loại:</label>
                    <select id="new-task-reporting-cycle" name="type"
                            class="form-select w-full border border-gray-300 rounded-lg p-2">
                            <option value="">Lựa chọn chu kỳ</option>
                            <option value="task">Nhiệm vụ</option>
                            <option value="target">Chỉ tiêu</option>
                        </select>
                </div>
        </div>

        <div class="flex justify-end gap-4">
            <button type="submit"
            class="inline-block bg-blue-400 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">
            Tìm kiếm
        </button>
        <a href="{{ route('tasks.create') }}" class="inline-block bg-blue-400 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4"> Thêm mới</a>

        </div>
    </form>
      
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                    <tr>
      
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Mã</th>
                        <th style="width: 450px;" class="py-3 px-6 text-left text-gray-700 font-medium">Tên</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Chu kỳ</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Loại</th>
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
                </thead>
                <tbody>
                    @foreach ($taskTargets as $index => $item)
                        <tr class="border-b border-gray-200">
                       
                            <td class="py-3 border border-gray-300 px-6">{{ $item->code }}</td>
                            <td style="width: 450px;" class="py-3 border border-gray-300 px-6">{{ $item->name }}</td>
                            <td class="py-3 border border-gray-300 px-6">
                                {{ $item->getCycleTypeTextAttribute() }}
                            </td>
                            <td class="py-3 border border-gray-300 px-6"> {{ $item->getTypeTextAttribute() }}</td>
                            <td class="py-3 border border-gray-300 px-6"> {{ $item->getDateFromToTextAttribute() }}</td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <button class="bg-blue-400 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"
                                    onclick="window.location.href='{{ route('tasks.show', $item->code) }}'">
                                    <i class="fas fa-info-circle"></i> <!-- Biểu tượng cho "Chi tiết" -->
                                </button>
                            </td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <button class="bg-yellow-300 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                                onclick="window.location.href='{{ route('tasks.edit', $item->code) }}'">
                                <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                            </button>
                            </td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <form action="{{ route('tasks.destroy', $item->code) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-yellow-300 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                        onclick="return confirm('Bạn có chắc chắn rằng muốn xóa văn bản này?');">
                                        <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                    </button>
                                </form>
                            </td>

                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <button data-document-id="{{ $item->document_id }}"
                                    data-task-id="{{ $item->code }}"
                                    class="history-task bg-blue-400 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                      <i class="fa fa-history"></i>
                                </button>
                            
                            </td>
                            
                        </tr>
                    @endforeach
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
