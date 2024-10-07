@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                {!! Breadcrumbs::render('details.tasks.byType', $type) !!}
            </ol>
        </nav>
        <div class="overflow-hidden">
            <div class="p-6">
                @php
                    $text = 'chỉ tiêu';
                    if ($type == 'task') {
                        $text = 'nhiệm vụ';
                    }

                @endphp
                <input type="hidden" name="type" id="type" value="{{ $type }}" />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-white ">
                    <!-- Cột trái -->
                    @if ($type == 'task')
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Tên {{ $text }}:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->name }}</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Nhóm {{ $text }}:</span>
                            <span class="text-gray-900 w-2/3">
                                @foreach ($typeTask as $item)
                                    @if ($taskTarget->type_id == $item->id)
                                        {{ $item->name }}
                                    @endif
                                @endforeach
                            </span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Chu kỳ báo cáo:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->getCycleTypeTextAttribute() }}</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Kết quả yêu cầu:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->request_results_task }}</span>
                  
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Kiểu dữ kiệu báo cáo:</span>
                            <span class="text-gray-900 w-2/3"></span>
                  
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Loại nhiệm vụ:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->getTypeTextAttributeTime() }}</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Số hiệu văn bản:</label>
                            <span class="text-gray-900 w-2/3">{{ $document->document_code }}</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Văn bản giao việc:</span>
                            <span class="text-gray-900 w-2/3">
                                @foreach ($documents as $item)
                                    @if ($item->id == $taskTarget->document_id)
                                        {{ $item->document_name }}
                                    @endif
                                @endforeach
                            </span>
                        </div>
                    @else
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Tên {{ $text }}:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->name }}</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Đơn vị tính:</span>
                            <span class="text-gray-900 w-2/3">

                                @foreach ($units as $item)
                                    @if ($taskTarget->unit == $item->id)
                                        {{ $item->name }}
                                    @endif
                                @endforeach
                            </span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Chỉ tiêu:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->target }}</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Nhóm {{ $text }}:</span>
                            <span class="text-gray-900 w-2/3">
                                @foreach ($typeTask as $item)
                                    @if ($taskTarget->type_id == $item->id)
                                        {{ $item->name }}
                                    @endif
                                @endforeach
                            </span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Chu kỳ báo cáo:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->getCycleTypeTextAttribute() }}</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Số hiệu văn bản:</label>
                            <span class="text-gray-900 w-2/3">{{ $document->document_code }}</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Văn bản giao việc:</span>
                            <span class="text-gray-900 w-2/3">
                                @foreach ($documents as $item)
                                    @if ($item->id == $taskTarget->document_id)
                                        {{ $item->document_name }}
                                    @endif
                                @endforeach
                            </span>
                        </div>
                    @endif
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Ngày bắt đầu:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->getStartDate() }}</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Ngày hoàn thành:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->getEndDate() }}</span>
                        </div>   
                    <!-- <div class="flex items-center mb-4">
                        <span class="text-gray-700 font-medium w-1/3">Mã {{ $text }}:</span>
                        <span class="text-gray-900 w-2/3">{{ $taskTarget->code }}</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 font-medium w-1/3">Văn bản:</span>
                        <span class="text-gray-900 w-2/3">
                            @foreach ($documents as $item)
                                @if ($item->id == $taskTarget->document_id)
                                    {{ $item->document_name }}
                                @endif
                            @endforeach
                        </span>
                    </div>

                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 font-medium w-1/3">Nhóm {{ $text }}:</span>
                        <span class="text-gray-900 w-2/3">
                            @foreach ($typeTask as $item)
                                @if ($taskTarget->type_id == $item->id)
                                    {{ $item->name }}
                                @endif
                            @endforeach
                        </span>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 font-medium w-1/3">Chu kỳ:</span>
                        <span class="text-gray-900 w-2/3">{{ $taskTarget->getCycleTypeTextAttribute() }}</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 font-medium w-1/3">Tên {{ $text }}:</span>
                        <span class="text-gray-900 w-2/3">{{ $taskTarget->name }}</span>
                    </div>

                    @if ($type == 'task')
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Loại nhiệm vụ:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->getTypeTextAttributeTime() }}</span>
                        </div>
                    @else
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Loại chỉ tiêu:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->getTypeTextAttributeTarget() }}</span>
                        </div>
                    @endif

                    @if ($type == 'target')
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Đơn vị tính:</span>
                            <span class="text-gray-900 w-2/3">

                                @foreach ($units as $item)
                                    @if ($taskTarget->unit == $item->id)
                                        {{ $item->name }}
                                    @endif
                                @endforeach
                            </span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Chỉ tiêu:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->target }}</span>
                        </div>
                    @else
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Kết quả:</span>
                            <span class="text-gray-900 w-2/3">
                                @foreach ($workResultTypes as $idx => $item)
                                    @continue($type != 'task' && $idx == 4)
                                    @if ($taskTarget->result_type == $item->key)
                                        {{ $item->value }}
                                    @endif
                                @endforeach
                            </span>


                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Kết quả yêu cầu:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->request_results_task }}</span>
                  
                        </div>
                    @endif

                        {{-- <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Kết quả:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->request_results }}</span>
                        </div> --}}

                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Ngày bắt đầu:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->getStartDate() }}</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-700 font-medium w-1/3">Ngày hoàn thành:</span>
                            <span class="text-gray-900 w-2/3">{{ $taskTarget->getEndDate() }}</span>
                        </div> -->
                        @php
                            // Khởi tạo mảng để lưu trữ số lượng của từng type_name
                            $typeCounts = [];

                            // Duyệt qua các kết quả và đếm số lượng mỗi loại type_name
                            foreach ($mappedResults as $item) {
                                if ($item['organization'] && $item['organization']->organizationType) {
                                    // Chuyển đổi type_name thành chữ thường và chỉ viết hoa chữ cái đầu
                                    $typeName = ucwords(strtolower($item['organization']->organizationType->type_name));

                                    if ($typeName) {
                                        // Tăng số lượng cho type_name
                                        if (!isset($typeCounts[$typeName])) {
                                            $typeCounts[$typeName] = 0;
                                        }
                                        $typeCounts[$typeName]++;
                                    }
                                }
                            }
                        @endphp

                        <!-- Hiển thị kết quả -->
                        @foreach ($typeCounts as $typeName => $count)
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">{{ $typeName }}:</span>
                                <span class="text-gray-900 w-2/3">{{ $count }}</span>
                            </div>
                        @endforeach
                </div>
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th colspan="8" class="border border-gray-300 py-3 px-6 text-left font-medium text-center">Cơ
                                quan, tổ chức đã được giao</th>

                        </tr>
                        <tr>
                            <th class="border border-gray-300 py-3 px-6 text-left font-medium">Mã cơ quan, tổ chức</th>
                            <th class="border border-gray-300 py-3 px-6 text-left font-medium">Tên cơ quan, tổ chức</th>
                            <th class="border border-gray-300 py-3 px-6 text-left font-medium">Loại cơ quan, tổ chức</th>
                            <th class="border border-gray-300 py-3 px-6 text-left font-medium">Tiến độ</th>
                            <th class="border border-gray-300 py-3 px-6 text-left font-medium">Đánh giá tiến độ</th>
                            <th class="border border-gray-300 py-3 px-6 text-left font-medium">Chu kỳ</th>
                            <th class="border border-gray-300 py-3 px-6 text-left font-medium">Kết quả</th>

                            <th class="border border-gray-300 py-3 px-6 text-left font-medium">Lịch sử</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taskDocuments as $index => $item)
                            {{-- @if ($item['organization']) --}}
                                <tr class="border-b border-gray-200">

                                    <td class="py-3 border border-gray-300 px-6">{{ $item->organization->code ?? '' }}
                                    </td>
                                    <td class="py-3 border border-gray-300 px-6">{{ $item->organization->name ?? '' }}
                                    </td>
                                    <td class="py-3 border border-gray-300 px-6">
                                        {{ $item->organization->organizationType->type_name ?? '' }}
                                    </td>
                                    <td class="py-3 border border-gray-300 px-6">
                                        {{ $item->results }}
                                    </td>
                                    <td class="py-3 border border-gray-300 px-6">
                                        {{ $item->getStatusLabelAttribute() ?? '' }}
                                    </td>
                                    <td class="py-3 border border-gray-300 px-6">
                                       {{ $item->getCycleTypeTextAttribute() }}
                                    </td>
                                    <td class="py-3 border border-gray-300 px-6">
                                        @php
                                        $resultCycle = $item->taskResultsByIdTaskTarget()->result ?? 'Nhân viên chưa báo cáo';
                                    @endphp
                                        {{ $resultCycle }}</td>
                                    
                                        <td class="py-3 border border-gray-300 px-6 text-center">
                                            
                                            <button data-document-id="{{ $item->id }}"
                                                data-task-id="{{ $item->code }}" type="button"
                                                class="history-task bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                  <i class="fa fa-history"></i>
                                            </button>
                                          
                                        </td>

                                </tr>
                            {{-- @endif --}}
                        @endforeach
                    </tbody>
                </table>
                {{-- <div class="mt-4">
                    {{ $paginatedResults->links() }}
                </div> --}}
                <div class="mt-4 flex" style="justify-content: space-between">
                    <button type="button" id="back"
                        class="inline-block bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300 mb-4">Quay
                        lại</button>
                </div>
            </div>

        </div>
    </div>
     {{-- lich su --}}
     <div id="history-change-modal" style="z-index: 9999;" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-3/4">
            
            <h2 class="text-xl font-semibold mb-4">Lịch sử cập nhật kết quả</h2>
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
        document.getElementById('back').addEventListener('click', function(event) {
            event.preventDefault();
            var type = document.getElementById('type');
            var selectedValue = type.value;
            // Chuyển hướng đến URL tương ứng với giá trị được chọn
            if (selectedValue) {
                window.location.href = `/tasks/type/${selectedValue}`;
            }
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
                        fetch(`/api/get-history/byId/${documentIdRow}`)
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
