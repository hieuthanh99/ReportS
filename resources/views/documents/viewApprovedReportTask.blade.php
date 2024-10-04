@extends('layouts.app')

@section('content')

    <style>
        /* .table-container {
                                        width: 2100px;
                                        border-collapse: collapse;
                                        overflow-x: auto;
                                    }

                                    .table-container th,
                                    .table-container td {
                                        text-align: center;
                                        border: 1px solid #ddd;
                                        padding: 8px;
                                        text-align: left;
                                        word-wrap: break-word;
                                        white-space: normal;
                                    }

                                    .table-container th {
                                        text-align: center;
                                        background-color: #f4f4f4;
                                        font-weight: bold;
                                    }

                                    th:nth-child(3),
                                    td:nth-child(3) {
                                        position: -webkit-sticky;
                                        position: sticky;
                                        left: 0;
                                        padding: 5px;
                                        background-color: #f9f9f9;
                                        z-index: 10;
                                    } */

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

        input[readonly],
        textarea[readonly] {
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
        @php
            $type = 'task';
            $isEditable = $taskTarget->status == 'assign' || $taskTarget->status == 'reject';
            $result = $taskTarget->taskResultsByIdTaskTarget()->result ?? 'Nhân viên chưa báo cáo';
            $hasOrganization = $taskTarget->hasOrganizationAppro();
            $taskApproval = $taskTarget->getTaskApprovalHistory();
        @endphp
        <div class="bg-white  overflow-hidden">

            <div class="p-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        {!! Breadcrumbs::render('CTBC', $document) !!}
                    </ol>
                </nav>
                <form action="{{ route('documents.task.update.cycle', $taskTarget->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="bg-white p-6 ">
                        <h5 class="text-xl font-semibold mb-4">Thông tin văn bản</h5>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-white ">
                            <!-- Cột trái -->
                            <div class="flex items-center mb-4">
                                <label for="document_code" class="text-gray-700 font-medium w-1/3">Mã văn
                                    bản:</label>
                                <span class="text-gray-900 w-2/3">{{ $document->document_code }}</span>
                            </div>
                            <div class="flex items-center mb-4">
                                <label for="document_name" class="text-gray-700 font-medium w-1/3">Tên văn
                                    bản:</label>
                                <span class="text-gray-900 w-2/3">{{ $document->document_name }}</span>

                            </div>
                            <div class="flex items-center mb-4">
                                <label for="issuing_department" class="text-gray-700 font-medium w-1/3">Cơ
                                    quan, tổ chức phát
                                    hành:</label>
                                <span class="text-gray-900 w-2/3">{{ $document->issuingDepartment->name ?? '' }}</span>
                            </div>
                            <div class="flex items-center mb-4">
                                <label for="release_date" class="text-gray-700 font-medium w-1/3">Ngày phát
                                    hành:</label>
                                <span class="text-gray-900 w-2/3">{{ $document->getReleaseDateFormattedAttribute() }}</span>
                            </div>
                        </div>

                        <!-- Hàng upload file -->
                        <div class="mb-4 gap-6 p-6 bg-white" style="margin: 20px 0; padding-top: 0">
                            <label for="issuing_department" class="text-gray-700 font-medium w-1/3">Danh sách
                                tệp tin:</label>
                            <div id="file-list-data-document" class="mt-2 file-list-data-document">
                                @if (!$document->files->isEmpty())
                                    @foreach ($document->files as $file)
                                        @php
                                            $filePath = storage_path('app/public/' . $file->file_path);
                                            $fileType = file_exists($filePath) ? mime_content_type($filePath) : '';
                                        @endphp
                                        <div class="file-item flex items-center mb-2" data-file-id="{{ $file->id }}"
                                            data-file-type="{{ $fileType }}">
                                            <img class="file-icon w-12 h-12 mr-2" src="" alt="File icon">
                                            <a href="{{ route('file.view', ['id' => $file->id]) }}"
                                                class="text-blue-500 hover:underline"
                                                target="_blank">{{ $file->file_name }}</a>
                                        </div>
                                    @endforeach
                                @else
                                    <span>Không có tệp nào</span>
                                @endif

                            </div>
                        </div>
                    </div>
                    <hr class="mb-6">
                    <div class="bg-white p-6 ">
                        <h5 class="text-xl font-semibold mb-4">Nhiệm vụ</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-white ">
                            <!-- Cột trái -->
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Mã nhiệm vụ:</span>
                                <span class="text-gray-900 w-2/3">{{ $taskTarget->code }}</span>
                            </div>
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Tên nhiệm vụ:</span>
                                <span class="text-gray-900 w-2/3">{{ $taskTarget->name }}</span>
                            </div>
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Nhóm nhiệm vụ:</span>
                                <span class="text-gray-900 w-2/3">
                                    @foreach ($groupTask as $item)
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
                                <span class="text-gray-700 font-medium w-1/3">Loại nhiệm vụ:</span>
                                <span class="text-gray-900 w-2/3">{{ $taskTarget->getTypeTextAttributeTime() }}</span>
                            </div>
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
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Ngày bắt đầu:</span>
                                <span class="text-gray-900 w-2/3">{{ $taskTarget->getStartDate() }}</span>
                            </div>
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Ngày hoàn thành:</span>
                                <span class="text-gray-900 w-2/3">{{ $taskTarget->getEndDate() }}</span>
                            </div>
                        </div>
                      
                   
                    </div>
                       
                    <hr class="mb-6">
                    <div class="bg-white p-6 ">
                        
                        <h5 class="text-xl font-semibold mb-4">Danh sách cơ quan thực hiện</h5>
                        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                            <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                                <tr>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">STT</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Cơ quan thực hiện</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Trạng thái</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Hoàn thành</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Nhận xét báo cáo</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Báo cáo kết quả</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Tệp</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Lịch sử</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Duyệt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $stt = 1;
                                @endphp
                                @foreach ($taskDocuments as $index => $item)
                                    @php
                                        $taskApproval = $item->getTaskApprovalHistory();
                                        $result = $item->taskResultsByIdTaskTarget()->result ?? 'Nhân viên chưa báo cáo';
                                        $hasOrganization = $item->hasOrganizationAppro();
                                    @endphp
                                    <tr class="border-b border-gray-200">
                                        <td class="py-3 border border-gray-300 px-6">{{ $stt++ }}</td>
                                        <td class="py-3 border border-gray-300 px-6">{{ $item->organization->name??'' }}</td>
                                        <td class="py-3 border border-gray-300 px-6">{{ $item->getStatusLabelAttributeTaskTarget() ?? '' }}</td>
                                        <td class="py-3 border border-gray-300 px-6">
                                            @if ($item->is_completed)
                                            <span class="text-gray-900 w-2/3"> Hoàn thành</span>
                                        @else
                                        <span class="text-gray-900 w-2/3">Chưa hoàn thành</span>
                                        @endif
                                        </td>
                                        <td class="py-3 border border-gray-300 px-6">{{ $taskApproval->remarks ?? 'Chưa nhận xét kết quả' }}</td>
                                        <td class="py-3 border border-gray-300 px-6">{{ $result  }}</td>
                                        <td class="py-3 border border-gray-300 px-6" >
                                    @php
                                        $file = $item->getFilePath() ?? null;
                                    @endphp
                                    @if ($file && !empty($file->file_path))
                                            @php
                                                $filePath = storage_path('app/public/' . $file->file_path);
                                                $fileType = file_exists($filePath) ? mime_content_type($filePath) : '';
                                            @endphp

                                            <div class="file-item flex items-center mb-2 text-center"  
                                                data-file-id="{{ $file->id }}" data-file-type="{{ $fileType }}"
                                                style="margin-top: 20px; word-wrap:break-word">
                                               
                                                <a href="{{ route('file.view', ['id' => $file->id]) }}"
                                                    class="text-blue-500 hover:underline"
                                                    target="_blank"> <i style="text-align: center; margin-right: 0" class="fa-2x fas fa-download"></i></a>
                                            </div>

                                            @else
                                        <label class="text-gray-900 w-2/3">&nbsp;</label>
                                    @endif
                                        </td>
                                        <td class="py-3 border border-gray-300 px-6 text-center">
                                            <button data-document-id="{{ $item->document_id }}"
                                                data-task-id="{{ $item->code }}" type="button"
                                                class="history-task bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                                  <i class="fa fa-history"></i>
                                            </button>
                                        </td>
                                        <td class="py-3 border border-gray-300 px-6">
                                            @if ($item->status == 'sub_admin_complete' &&
                                                    (Auth::user()->role == 'admin' || Auth::user()->role == 'supper_admin'))
                                                <button data-id="{{ $item->id }}" id="button-apprrover-{{ $item->id }}"
                                                    style="margin:  10px 0" type="button"
                                                    class="button-approved bg-green-500 text-white px-2 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300">
                                                    Hoàn thành
                                                </button>
                                            @elseif($item->status == 'complete')
                                            <i class="fas fa-check" style="color: green;"></i>

                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                
                </form>
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
        //============================== Hoàn thành ==========================
    document.addEventListener('DOMContentLoaded', function () {
    // Lắng nghe sự kiện click cho tất cả các nút hoàn thành
    document.querySelectorAll('.button-approved').forEach(button => {
        button.addEventListener('click', function () {
            const itemId = this.getAttribute('data-id'); 
            const url = `/update-status/${itemId}`; 
            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: 'completed'
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
                            }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Có lỗi xảy ra trong quá trình gửi yêu cầu.');
            });
        });
    });
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
          
          var result = "<?php echo $result; ?>";
          var taskTarget = "<?php $taskTarget->result_type; ?>";

          if(taskTarget == 'BOOL')
          {
            let yesBtnSet = document.getElementById('yes');
            let noBtnSet = document.getElementById('no');
// Kiểm tra giá trị và chọn radio button tương ứng
if (result === "Yes") {
    yesBtnSet.setAttribute('checked', 'checked')
    noBtnSet.checked = false
} else if (result === "No") {
    yesBtnSet.checked = false
    noBtnSet.setAttribute('checked', 'checked')
}
          }
  
        function selectType(value) {
            document.getElementById('issuing_department').value = value
            let yesBtn = document.getElementById('yes');
            let noBtn = document.getElementById('no');
            if (value == yesBtn.value) {
                yesBtn.setAttribute('checked', 'checked')
                noBtn.checked = false
            } else {
                yesBtn.checked = false
                noBtn.setAttribute('checked', 'checked')
            }
        }
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

        //=============================================== End file ==========================================
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
            fileInput = document.getElementById('fileInput-' + id);
            file = fileInput.files[0];
            console.log(fileInput);
            console.log(file);
            if (file) {
                const cycleType = document.getElementById('task-cycle_type-input-' + id).value;
                const numberType = document.getElementById('task-number-type-input-' + id).value;
                const formData = new FormData();
                formData.append('files', file);
                formData.append('file_id', id);
                formData.append('numberType', numberType);
                formData.append('cycleType', cycleType);

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
                            document.getElementById('button-file-task-' + data.id).style.display = 'none';
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

           
            fileInput.addEventListener('change', updateFileList);
            updateFileList();
        });
    </script>
@endsection
