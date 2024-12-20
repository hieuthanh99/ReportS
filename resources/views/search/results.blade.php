@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                    <tr>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">STT</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Mã</th>
                        <th style="width: 450px;" class="py-3 px-6 text-left text-gray-700 font-medium">Tên</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Chu kỳ</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Loại</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Ngày bắt đầu - kết thúc</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                           Chi tiết
                        </th>
                       
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                            Lịch sử 
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($taskTargets as $index => $item)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 border border-gray-300 px-6 text-center">{{ $index + $taskTargets->firstItem() }}</td>

                            <td class="py-3 border border-gray-300 px-6">{{ $item->code }}</td>
                            <td style="width: 450px;" class="py-3 border border-gray-300 px-6">{{ $item->name }}</td>
                            <td class="py-3 border border-gray-300 px-6">
                                {{ $item->getCycleTypeTextAttribute() }}
                            </td>
                            <td class="py-3 border border-gray-300 px-6"> {{ $item->getTypeTextAttribute() }}</td>
                            <td class="py-3 border border-gray-300 px-6"> {{ $item->getDateFromToTextAttribute() }}</td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <button class="bg-blue-400 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"
                                    onclick="window.location.href='{{ route('tasks.show-details', ['code' => $item->code, 'type' => $item->type]) }}'">
                                    <i class="fas fa-info-circle"></i> <!-- Biểu tượng cho "Chi tiết" -->
                                </button>
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
                            <th class="py-2 px-4 border-b">Kết quả báo cáo</th>
                            <th class="py-2 px-4 border-b">Trạng thái báo cáo</th>
                            <th class="py-2 px-4 border-b">Nhận xét</th>
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
        // document.getElementById('filterToggle').addEventListener('click', function() {
        //     const filterForm = document.getElementById('filterForm');
        //     filterForm.classList.toggle('hidden');
        // });
        function confirmDelete() {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: 'Xác nhận xóa!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xoá!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').submit();
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
                    parentSelect.innerHTML = '<option value="" disabled selected>Chọn Cơ quan ban hành</option>';

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
                            cycle_text = 'Chu kỳ tuần';
                        }else if(history.type_cycle == 2){
                            cycle_text = 'Chu kỳ tháng';
                        }else if(history.type_cycle == 3){
                            cycle_text = 'Chu kỳ quý';
                        }else if(history.type_cycle == 4){
                            cycle_text = 'Chu kỳ năm';
                        }
                        const text_result_cycle = cycle_text + ' ' + history.number_cycle;
                        // Các cột khác
                        const mappingIdCell = document.createElement('td');
                        mappingIdCell.classList.add('py-2', 'px-4', 'border-b');
                        mappingIdCell.textContent = history.status_label;
                        row.appendChild(mappingIdCell);

                        const resultsCell = document.createElement('td');
                        resultsCell.classList.add('py-2', 'px-4', 'border-b');
                        resultsCell.textContent = history.result;
                        row.appendChild(resultsCell);
                        
                        const statusCodeCell = document.createElement('td');
                        statusCodeCell.classList.add('py-2', 'px-4', 'border-b');
                        statusCodeCell.textContent = history.task_result_status_label;
                        row.appendChild(statusCodeCell);

                        const remarkCell = document.createElement('td');
                        remarkCell.classList.add('py-2', 'px-4', 'border-b');
                        remarkCell.textContent = history.remarks;
                        row.appendChild(remarkCell);

                        // const typeSaveCell = document.createElement('td');
                        // typeSaveCell.classList.add('py-2', 'px-4', 'border-b');
                        // typeSaveCell.textContent = history.description;
                        // row.appendChild(typeSaveCell);

                        
                        const descriptionCell = document.createElement('td');
                        descriptionCell.classList.add('py-2', 'px-4', 'border-b');
                        descriptionCell.textContent = history.update_date;
                        row.appendChild(descriptionCell);

                        const resultCell = document.createElement('td');
                        resultCell.classList.add('py-2', 'px-4', 'border-b');
                        if(history.number_cycle !== null ){
                            resultCell.textContent = text_result_cycle;
                        }
                        row.appendChild(resultCell);


                        // Thêm hàng vào bảng
                        tableBody.appendChild(row);
                    });
                }
          });
            
    </script>
    
@endsection
