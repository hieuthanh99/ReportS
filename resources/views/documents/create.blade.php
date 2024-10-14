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
    </style>
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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                {!! Breadcrumbs::render('CDMVB') !!}
            </ol>
        </nav>
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="p-6" id="document-form">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cột trái -->
                <div class="mb-4">
                    <label for="document_code" class="block text-gray-700 text-sm font-medium mb-2">Số hiệu văn bản <span class="text-red-500">*</span></label>
                    <input type="text" id="document_code" name="document_code" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('document_code') }}" required
                    oninvalid="this.setCustomValidity('Vui lòng nhập số hiệu văn bản.')" 
                    oninput="setCustomValidity('')">
                    @error('document_code')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700 text-sm font-medium mb-2">Loại văn bản <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" class="form-input w-full border border-gray-300 rounded-lg p-2 select2" required
                    oninvalid="this.setCustomValidity('Vui lòng chọn loại văn bản.')" 
                    oninput="setCustomValidity('')">
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Chọn văn bản</option>
                        @foreach ($documentCategory as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
               
                <div class="mb-4">
                    <label for="document_name" class="block text-gray-700 text-sm font-medium mb-2">Trích yếu văn bản <span class="text-red-500">*</span></label>
                    <textarea id="document_name" name="document_name" class="form-input w-full border border-gray-300 rounded-lg p-2 resize-none" rows="4" required
                    oninvalid="this.setCustomValidity('Vui lòng nhập trích yếu văn bản.')" 
                    oninput="setCustomValidity('')">{{ old('document_name') }}</textarea>
                    @error('document_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                {{-- documentCategory --}}
                
        
                <div class="mb-4">
                    <label for="release_date" class="block text-gray-700 text-sm font-medium mb-2">Ngày phát hành <span class="text-red-500">*</span></label>
                    <input type="date" placeholder="dd-mm-yyyy"
                    min="1997-01-01" max="2100-12-31" id="release_date" name="release_date" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('release_date') }}" required
                    oninvalid="this.setCustomValidity('Vui lòng nhập ngày phát hành.')" 
                    oninput="setCustomValidity('')">
                    @error('release_date')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="organization_type_id" class="block text-gray-700 text-sm font-medium mb-2">Loại cơ quan ban hành<span class="text-red-500">*</span></label>
                    <select id="organization_type_id" name="organization_type_id" class="form-input w-full border border-gray-300 rounded-lg p-2 select2" require
                    oninvalid="this.setCustomValidity('Vui lòng chọn loại cơ quan ban hành.')" 
                    oninput="setCustomValidity('')">
                        <option value="">Chọn loại cơ quan ban hành</option>
                        @foreach($organizationsType as $organization)
                            <option value="{{ $organization->id }}" {{ request('organization_type_id') == $organization->id ? 'selected' : '' }}>
                                {{ $organization->type_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="issuing_department" class="block text-gray-700 text-sm font-medium mb-2">Cơ quan ban hành <span class="text-red-500">*</span></label>
                    <select name="issuing_department" id="parent_id" class="border border-gray-300 rounded-lg p-2 w-full select2" require
                    oninvalid="this.setCustomValidity('Vui lòng chọn cơ quan ban hành.')" 
                    oninput="setCustomValidity('')">
                        <option value="" {{ old('issuing_department') ? '' : 'selected' }}>Chọn cơ quan ban hành</option>
                    </select>

                
                </div>
                

                <!-- Hàng upload file -->
                <div class="col-span-2 mb-4">
                    <label for="files" class="block text-gray-700 text-sm font-medium mb-2">Tải lên tài liệu (nhiều tệp)</label>
                    <input type="file" id="files" name="files[]" class="form-input w-full border border-gray-300 rounded-lg p-2" multiple>
                    <p class="text-gray-500 text-sm mt-1">Chọn nhiều tệp để tải lên.</p>
                    <div id="file-list" class="mt-2 file-list"></div>
                </div>
            </div>
        
            <!-- Nút lưu -->
            <div class="mt-4 flex" style="justify-content: space-between">
                <a href="{{ route('documents.index') }}" class="inline-block bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">Quay lại</a>

                <button type="submit" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Lưu</button>
            </div>
        </form>
        
        
        
    
        <script>
            $(document).ready(function() {
    $('.select2').select2();

    // Lắng nghe sự kiện select2:select
    $('#organization_type_id').on('select2:select', function (e) {
        var organizationTypeId = e.params.data.id; // Lấy id của tùy chọn đã chọn

        // Gửi yêu cầu AJAX
        fetch(`/get-organizations/${organizationTypeId}`)
            .then(response => response.json())
            .then(data => {
                var parentSelect = document.getElementById('parent_id');
                parentSelect.innerHTML = '<option value="" disabled selected>Chọn Cơ quan ban hành</option>';

                data.forEach(function (organization) {
                    var option = document.createElement('option');
                    option.value = organization.id;
                    option.text = organization.name;
                    parentSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    });
});

             document.getElementById('document-form').addEventListener('submit', function() {
                // Hiển thị loader khi form được gửi
                document.getElementById('loading').classList.remove('hidden');
            });

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
                updateFileList();
                //=============================================== End file ==========================================
        </script>
    </div>
@endsection
