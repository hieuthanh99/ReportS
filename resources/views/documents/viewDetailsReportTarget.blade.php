@extends('layouts.app')

@section('content')

    <style>

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
            $type = 'task';
            $isEditable = $taskTarget->status == 'assign' || $taskTarget->status == 'reject';
            $result = $taskTarget->taskResultsByIdTaskTarget()->result ?? '';
            $hasOrganization = $taskTarget->hasOrganizationAppro();
            $taskApproval = $taskTarget->getTaskApprovalHistory();

        @endphp
        <div class="bg-white  overflow-hidden">

            <div class="p-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        {!! Breadcrumbs::render('CTBCTG', $document) !!}
                    </ol>
                </nav>
                <form action="{{ route('documents.task.update.cycle', $taskTarget->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="bg-white p-6 ">
                        <h5 class="text-xl font-semibold mb-4">Chỉ tiêu</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-white ">
                            <!-- Cột trái -->
                            <!-- <div class="flex items-center mb-4">
                                    <span class="text-gray-700 font-medium w-1/3">Mã chỉ tiêu:</span>
                                    <span class="text-gray-900 w-2/3">{{ $taskTarget->code }}</span>
                                </div> -->
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Tên chỉ tiêu:</span>
                                <span class="text-gray-900 w-2/3">{{ $taskTarget->name }}</span>
                            </div>
                            <!-- <div class="flex items-center mb-4">
                                    <span class="text-gray-700 font-medium w-1/3">Nhóm chỉ tiêu:</span>
                                    <span class="text-gray-900 w-2/3">
                                   
                                        @foreach ($groupTarget as $item)
    @if ($taskTarget->type_id == $item->id)
    {{ $item->name }}
    @endif
    @endforeach
                                    </span>
                                </div> -->
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Chỉ tiêu:</span>
                                <span class="text-gray-900 w-2/3">
                                    {{ $taskTarget->target }}
                                </span>
                            </div>
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Chu kỳ báo cáo:</span>
                                <span class="text-gray-900 w-2/3">{{ $taskTarget->getCycleTypeTextAttribute() }}</span>
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


                            <!-- <div class="flex items-center mb-4">
                                    <span class="text-gray-700 font-medium w-1/3">Loại chỉ tiêu:</span>
                                    <span class="text-gray-900 w-2/3">{{ $taskTarget->getTypeTextAttributeTarget() }}</span>
                                </div> -->
                            <!-- <div class="flex items-center mb-4">
                                    <span class="text-gray-700 font-medium w-1/3">Ngày bắt đầu:</span>
                                    <span class="text-gray-900 w-2/3">{{ $taskTarget->getStartDate() }}</span>
                                </div> -->
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Thời hạn hoàn thành:</span>
                                <span class="text-gray-900 w-2/3">{{ $taskTarget->getEndDate() }}</span>
                            </div>
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Trạng thái báo cáo:</span>
                                <span class="text-gray-900 w-2/3">
                                    {{ $taskResult->getStatusLabelAttributeTaskTarget() }}
                                </span>

                            </div>
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Tiến độ:</span>
                                <span class="text-gray-900 w-2/3">
                                    {{ $taskTarget->getStatusLabel() }}
                                </span>

                            </div>
                            <!-- <div class="flex items-center mb-4">
                                <label for="document_code" class="text-gray-700 font-medium w-1/3">Số hiệu văn
                                    bản:</label>
                                <span class="text-gray-900 w-2/3">{{ $document->document_code }}</span>
                            </div> -->
                            <div class="flex items-center mb-4">
                                <label for="document_code" class="text-gray-700 font-medium w-1/3">Văn bản giao
                                    việc:</label>
                                <span class="text-gray-900 w-2/3">{{ $document->document_code }}</span>
                            </div>
                            
                        </div>

                        <hr class="mb-6">
                        <h4 class="text-xl font-semibold mb-4">Kết quả báo cáo</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white " style="padding-top: 0">
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Báo cáo kết quả:</span>

                                <span class="text-gray-900 w-2/3">{{ $taskResult->result }}</span>

                            </div>
                            <!-- <div class="flex items-center mb-4">


                                <label class="text-gray-700 font-medium w-1/3" style="width: 300px;">Tệp báo
                                    cáo</label>
                                @php
                                    $file = $taskResult->getFilePath() ?? null;
                                @endphp
                                @if ($file && !empty($file->file_path))
                                    @php
                                        $filePath = storage_path('app/public/' . $file->file_path);
                                        $fileType = file_exists($filePath) ? mime_content_type($filePath) : '';
                                    @endphp
                                @endif
                            </div> -->
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 font-medium w-1/3">Nhận xét:</span>
                                <span class="text-gray-900 w-2/3">
                                    <span>{{ $taskApproval->remarks ?? 'Chưa nhận xét kết quả' }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-white " style="padding-top: 0">
                            <div class="flex items-center mb-4"></div>
                            @if ($file && !empty($file->id))
                            <div class="file-item flex items-center mb-2" data-file-id="{{ $file->id }}"
                                data-file-type="{{ $fileType }}">
                                <img class="file-icon w-12 h-12 mr-2" src="" alt="File icon">
                                <a href="{{ route('file.view', ['id' => $file->id]) }}"
                                    class="text-blue-500 hover:underline" target="_blank">{{ $file->file_name }}</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white p-6 ">
                        
                        @if(Auth::user()->role === 'staff')
                        <h5 class="text-xl font-semibold mb-4">Lịch sử báo cáo</h5>
                        @else
                        <h5 class="text-xl font-semibold mb-4">Lịch sử phê duyệt</h5>
                        @endif
                        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                            <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                                <tr>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium">STT</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Tiến độ</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Kết quả báo cáo</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Trạng thái báo cáo</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Nhận xét</th>
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Thời gian</th>
                                    <!-- <th class="py-3 px-6 text-left text-gray-700 font-medium">Kết quả</th> -->
                                    <!-- <th class="py-3 px-6 text-left text-gray-700 font-medium">Tệp</th> -->
                                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Chu kỳ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $stt = 1;
                                @endphp
                                @foreach ($lstResult as $index => $item)
                                    <tr class="border-b border-gray-200">
                                        <td class="py-3 border border-gray-300 px-6">{{ $stt++ }}</td>
                                        <td class="py-3 border border-gray-300 px-6">{{ $item->taskTarget->getStatusLabel() ?? '' }}</td>
                                        <td class="py-3 border border-gray-300 px-6">{{ $item->result }}</td>
                                        <td class="py-3 border border-gray-300 px-6">{{ $item->taskTarget->getStatusLabelAttributeTaskTarget() ?? '' }}</td>
                                        <td class="py-3 border border-gray-300 px-6">{{ $taskApproval->remarks }}</td>
                                        <td class="py-3 border border-gray-300 px-6">{{ $item->taskTarget->getEndDate() ?? '' }}</td>
                                        <td class="py-3 border border-gray-300 px-6">
                                            {{ $item->getCycleTypeTextAttribute() }} {{ $item->number_type }}</td>
                                        <!-- <td class="py-3 border border-gray-300 px-6">{{ $item->result ?? '' }}</td>
                                        <td class="py-3 border border-gray-300 px-6">
                                            @php
                                                $file = $taskTarget->getFilePath() ?? null;
                                            @endphp
                                            @if ($file && !empty($file->file_path))
                                                <a style="width: 49px;"
                                                    href="{{ route('file.download', ['id' => $file->id, 'type' => 1, 'cycleType' => $taskTarget->cycle_type, 'numberType' => $item->number_type]) }}"
                                                    id="button-file-task-{{ $taskTarget->id }}"
                                                    class="flex items-center space-x-2 px-4 py-2 bg-blue-400 text-white rounded hover:bg-blue-600 transition duration-300 hover:underline"
                                                    download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                        </td> -->

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex" style="justify-content: space-between">
                        <a href="{{ route('documents.report.target') }}"
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-gray-700 transition duration-300 mr-2">Quay
                            lại</a>
                    </div>
            </form>
        </div>

    </div>
    </div>
    <script>
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

            ///////////==================từ chối/duyệt================
            document.querySelectorAll('.button-approved').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const taskId = this.getAttribute('data-id');
                    const remarksValue = document.getElementById('remarks').value;

                    fetch('{{ route('tasks.updateRemarks') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                taskId: taskId,
                                remarks: remarksValue,
                                type: 'Approval'
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
                                // alert(data.message);
                                // Xử lý lỗi
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Xử lý lỗi
                        });
                });
            });
            document.querySelectorAll('.button-reject').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const taskId = this.getAttribute('data-id');
                    const remarksValue = document.getElementById('remarks').value;
                    fetch('{{ route('tasks.updateRemarks') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                taskId: taskId,
                                remarks: remarksValue,
                                type: 'Reject',
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log(data.message)
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Từ chối thành công!',
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
                                // alert(data.message);
                                // Xử lý lỗi
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Xử lý lỗi
                        });
                });
            });

            fileInput.addEventListener('change', updateFileList);
            updateFileList();
        });
    </script>
@endsection
