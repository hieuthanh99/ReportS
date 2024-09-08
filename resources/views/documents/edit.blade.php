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
        width: 100px;
        height: 100px;
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
    .remove-button {
        background-color: #f44336; /* Màu đỏ */
        border: none;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        line-height: 1;
        transition: background-color 0.3s, transform 0.3s;
    }

    .remove-button:hover {
        background-color: #d32f2f;
        transform: scale(1.1);
    }

    .remove-button:focus {
        outline: none;
    }
</style>
<div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {!! Breadcrumbs::render('UDMVB', $document) !!}
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
    <div class="overflow-hidden">
        <div class="">
        <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data"
            class="p-6 ">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cột trái -->
                <div class="mb-4">
                    <input type="hidden" id="document_id" name="document_id"
                    value="{{ $document->id }}"
                    class="form-input w-full border border-gray-300 rounded-lg p-2" >
                    <label for="document_code" class="block text-gray-700 text-sm font-medium mb-2">Số hiệu văn bản:</label>
                  
                        <input type="text" id="document_code" name="document_code"
                            value="{{ $document->document_code }}"
                            class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                        @error('document_code')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror
           
                </div>
                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700 text-sm font-medium mb-2">Loại văn bản:</label>

                    <select name="category_id" id="category_id" required
                            class="form-input w-full border border-gray-300 rounded-lg p-2">
                        @foreach ($documentCategory as $item)
                            <option value="{{ $item->id }}"
                                {{ $document->category_id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="document_name" class="block text-gray-700 text-sm font-medium mb-2">Trích yếu văn bản:</label>

                    {{--       <input type="text" id="document_name" name="document_name" value="{{ $document->document_name }}"
                          class="form-input w-full border border-gray-300 rounded-lg p-2" required> --}}
                    <textarea id="document_name" name="document_name" class="form-input w-full border border-gray-300 rounded-lg p-2 resize-none" rows="4" required>{{ $document->document_name }}</textarea>


                </div>

                <div class="mb-4">
                    <label for="release_date" class="block text-gray-700 text-sm font-medium mb-2">Ngày phát hành:</label>

                    <input type="date" placeholder="dd-mm-yyyy"
                           min="1997-01-01" max="2100-12-31" id="release_date" name="release_date"
                           @if ($document->creator != auth()->user()->id) readonly @endif
                           value="{{ $document->release_date }}"
                           class="form-input w-full border border-gray-300 rounded-lg p-2">

                </div>

                <div class="mb-4">
                    <label for="organization_type_id" class="block text-gray-700 text-sm font-medium mb-2">Loại cơ quan:</label>

                    <select name="organization_type_id" id="organization_type_id" required
                            class="form-input w-full border border-gray-300 rounded-lg p-2">

                        @foreach ($organizationsType as $organization)
                            <option value="{{ $organization->id }}"
                                {{ $document->issuingDepartment->organization_type_id == $organization->id ? 'selected' : '' }}>
                                {{ $organization->type_name }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <div class="mb-4">
                    <label for="issuing_department" class="block text-gray-700 text-sm font-medium mb-2">Cơ quan:</label>
                    <select id="parent_id" name="issuing_department" required

                            class="form-input w-full border border-gray-300 rounded-lg p-2">
                        @foreach ($organizations as $organization)
                            <option value="{{ $organization->id }}"
                                {{ $document->issuing_department == $organization->id ? 'selected' : '' }}>
                                {{ $organization->name }}
                            </option>
                        @endforeach
                    </select>

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
                            {{-- <span class="text-gray-700">{{ $file->file_name }}</span> --}}
                            <a href="{{ route('file.view', ['id' => $file->id]) }}" class="text-blue-500 hover:underline" target="_blank">{{ $file->file_name }}</a>
                            <button type="button" @if ($document->creator != auth()->user()->id) disabled @endif
                                class="remove-button  remove-file-button ml-2 bg-red-500 text-white px-2 py-1 rounded remove-file-button">×</button>
                        </div>
                    @endforeach
                </div>
                <div id="file-list" class="mt-2 file-list"></div>
            </div>
            <div class="mt-4 flex" style="justify-content: space-between">
                <a href="{{ route('documents.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-gray-700 transition duration-300 mt-4">Quay lại</a>

                <button type="submit" id="save-button"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 mt-4">Lưu
                </button>
            </div>
        </form>
        </div>
     </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                    removeButton.className = 'remove-button ml-2 bg-red-500 text-white px-2 py-1 rounded remove-file-button';
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
        });
    </script>
@endsection
