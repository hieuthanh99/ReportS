@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <script></script>
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                {!! Breadcrumbs::render('DSBC') !!}
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
        <!-- <button id="filterToggle" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300 mb-4">
            Lọc/Filter
        </button> -->
        <form method="GET" action="{{ route('documents.report') }}" id="filterForm">
            <div class="mb-6 flex flex-wrap gap-4 mb-4">
                <!-- Các trường khác -->
                <div class="flex-1 min-w-[200px]">
                    <label for="document_code" class="block text-gray-700 font-medium mb-2">Số hiệu văn bản</label>
                    <input type="text" id="document_code" name="document_code" value="{{ request('document_code') }}" placeholder="Số hiệu văn bản"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                </div>
                <!-- Các trường khác -->
                <div class="flex-1 min-w-[200px]">
                    <label for="document_name" class="block text-gray-700 font-medium mb-2">Trích yếu văn bản</label>
                    <input type="text" id="document_name" name="document_name" value="{{ request('document_name') }}" placeholder="Tên văn bản"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                </div>
                <!-- Các trường khác -->
                <div class="flex-1 min-w-[200px]">
                    <label for="organization_type_id" class="block text-gray-700 font-medium mb-2">Cơ quan ban hành:</label>
                    <select id="organization_type_id" name="organization_type_id" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="">Chọn cơ quan ban hành</option>
                        @foreach($organizationsType as $organization)
                            <option value="{{ $organization->id }}" {{ request('organization_type_id') == $organization->id ? 'selected' : '' }}>
                                {{ $organization->type_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px] hidden" id="organization_id">
                    <label for="organization_id" class="block text-gray-700 font-medium mb-2">&nbsp; </label>
                    <select name="organization_id" id="parent_id" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="" {{ old('organization_id') ? '' : 'selected' }}>Chọn cơ quan tổ chức</option>
                    </select>
                </div>
                <!-- Đoạn code này bao quanh hai trường Ngày phát hành -->
                {{-- <div class="flex gap-4 w-full">
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
                    <!-- Các trường khác -->
                    <div class="flex-1 min-w-[200px]" id="organization_id_hidden"></div>
                   
                </div> --}}
            </div>

        <div class="flex justify-end gap-4">
            <button type="submit"
            class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">
            Tìm kiếm
            </button>
       
        </div>
    </form>
      
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                    <tr>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">STT</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Mã nhiệm vụ</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Tên nhiệm vụ</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Đơn vị phát hành</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Ngày bắt đầu - kết thúc</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Trạng thái</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Chi tiết</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Cập nhật</th>
                        @if(Auth::user()->role !== 'staff')
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Xóa</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($taskDocuments as $index => $document)
                    
                        <tr class="border-b border-gray-200">
                            <td class="py-3 border border-gray-300 px-6">{{ $index + $taskDocuments->firstItem() }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $document->code ?? '' }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $document->name ?? ''}}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $document->getOrganization()->name ?? '' }}</td>
                            <td class="py-3 border border-gray-300 px-6"> {{ $document->getDateFromToTextAttribute() ?? ''  }}</td>
                            <td class="py-3 border border-gray-300 px-6"> {{ $document->getStatusLabelAttributeTaskTarget() ?? ''  }}</td>
                            <td class="py-3 border border-gray-300 px-6">
                                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"
                                onclick="window.location.href='{{ route('documents.report.details', $document->id) }}'">
                                <i class="fas fa-info-circle"></i> <!-- Biểu tượng cho "Chi tiết" -->
                                </button>
                            </td>
                            <td class="py-3 border border-gray-300 px-6">
                                <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                                    onclick="window.location.href='{{ route('documents.report.update', $document) }}'">
                                    <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                                </button>    
                            </td>
                            @if(Auth::user()->role !== 'staff')
                            <td class="py-3 border border-gray-300 px-6">
                               
                                <form id="delete-form-{{ $document->id }}" action="{{ route('documents.destroy', $document) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                        onclick="confirmDelete({{ $document->id }})">
                                        <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                    </button>
                                </form>
                            </td>
                            @endif
                            {{-- <td class="py-3 border border-gray-300 px-6" style="display: flex;text-align: center;"></td>
                            <td class="py-3 border border-gray-300 px-6" style="display: flex;text-align: center;">
                               
                            </td>
                            <td class="py-3 border border-gray-300 px-6" style="    display: flex;
                            text-align: center;">
                                   
                            </td> --}}
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $taskDocuments->links() }} <!-- Render pagination links -->
            </div>
        </div>
    </div>
    <script>
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
                    var customInput = document.getElementById('organization_id');
                customInput.classList.remove('hidden');
                var customInput = document.getElementById('organization_id_hidden');
                customInput.classList.add('hidden');
                })
                .catch(error => console.error('Error:', error));
        });
        // document.getElementById('filterToggle').addEventListener('click', function() {
        //     const filterForm = document.getElementById('filterForm');
        //     filterForm.classList.toggle('hidden');
        // });
        function confirmDelete(itemId) {
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
                    const form = document.getElementById('delete-form-' + itemId);
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Form not found');
                    }
                }
            });
        }
    </script>
@endsection