@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <script></script>
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                {!! Breadcrumbs::render('DMVB') !!}
            </ol>
        </nav>
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
        <!-- <button id="filterToggle" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300 mb-4">
            Lọc/Filter
        </button> -->
        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'supper_admin')
        <!-- <a href="{{ route('documents.create') }}" class="inline-block bg-blue-400 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">Thêm mới văn bản</a> -->
        @endif
        <!-- Search Form -->
        <form method="GET" action="{{ route('documents.index') }}" id="filterForm">
            <div class="mb-6 flex flex-wrap gap-4 mb-4">
                <!-- Các trường khác -->
                {{-- <div class="flex-1 min-w-[200px]">
                    <label for="document_code" class="block text-gray-700 font-medium mb-2">Số hiệu văn bản</label>
                    <input type="text" id="document_code" name="document_code" value="{{ request('document_code') }}" placeholder="Số hiệu văn bản"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                           <div id="search-results" class="absolute bg-white w-full border border-gray-300 rounded-lg shadow-lg mt-1 z-10 hidden">
                            <ul class="list-none p-0 m-0"></ul>
                        </div>
                </div> --}}
                <div class="flex-1 min-w-[200px] relative">
                    <label for="document_code" class="block text-gray-700 font-medium mb-2">Số hiệu văn bản</label>
                    <input type="text" id="document_code" name="document_code" placeholder="Số hiệu văn bản"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                
                    <!-- Kết quả tìm kiếm sẽ được hiển thị ngay bên dưới input -->
                    <div id="search-results" class="absolute bg-white w-full border border-gray-300 rounded-lg shadow-lg mt-1 z-10 hidden">
                        <ul class="list-none p-0 m-0"></ul>
                    </div>
                </div>
                <!-- Các trường khác -->
                {{-- <div class="flex-1 min-w-[200px]">
                    <label for="document_name" class="block text-gray-700 font-medium mb-2">Trích yếu văn bản</label>
                    <input type="text" id="document_name" name="document_name" value="{{ request('document_name') }}" placeholder="Tên văn bản"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                </div>
                 --}}
                <div class="flex-1 min-w-[200px] relative">
                    <label for="document_name" class="block text-gray-700 font-medium mb-2">Trích yếu văn bản</label>
                    <input type="text" id="document_name" name="document_name" placeholder="Trích yếu văn bản"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                
                    <!-- Kết quả tìm kiếm sẽ được hiển thị ngay bên dưới input -->
                    <div id="search-results-name" class="absolute bg-white w-full border border-gray-300 rounded-lg shadow-lg mt-1 z-10 hidden">
                        <ul class="list-none p-0 m-0"></ul>
                    </div>
                </div>
                
        
                <!-- Đoạn code này bao quanh hai trường Ngày phát hành -->

                    <!-- <div class="flex-1 min-w-[200px]">
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
                    </div> -->
                    <!-- Các trường khác -->
                    <div class="flex-1 min-w-[200px]">
                        <label for="organization_id" class="block text-gray-700 font-medium mb-2">Cơ quan ban hành:</label>
                        <select id="organization_id" name="organization_id" class="border border-gray-300 rounded-lg p-2 w-full select2">
                            <option value="">Chọn cơ quan ban hành</option>
                            @foreach($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ request('organization_id') == $organization->id ? 'selected' : '' }}>
                                    {{ $organization->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- <div class="flex-1 min-w-[200px] hidden" id="organization_id">
                        <label for="organization_id" class="block text-gray-700 font-medium mb-2">&nbsp; </label>
                        <select name="organization_id" id="parent_id" class="border border-gray-300 rounded-lg p-2 w-full">
                            <option value="" {{ old('organization_id') ? '' : 'selected' }}>Chọn cơ quan tổ chức</option>
                        </select>
                    </div> -->
                    <a class="fa fa-filter" style="margin-top: 45px;cursor: pointer;" onclick="window.location.href='{{ route('documents.index') }}'"></a>
            </div>

        <div class="flex justify-end gap-4">
            
            <button type="submit"
                class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">
                Tìm kiếm
            </button>
            <a href="{{ route('documents.create') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">Thêm mới văn bản</a>
            <a  onclick="window.location.href='{{ route('export.Documents') }}'" target="_blank" style="cursor: pointer;" class="inline-block bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300 mb-4">Xuất Excel</a>
        
        </div>
    </form>
      
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                    <tr>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">STT</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Số hiệu văn bản</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Loại văn bản</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Trích yếu văn bản</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Cơ quan ban hành</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Thời gian ban hành</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                            Chi tiết
                        </th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                            Cập nhật
                        </th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                            Xóa
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $index => $document)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 border border-gray-300 px-6">{{ $index + $documents->firstItem() }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $document->document_code }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $document->category->name ?? "N/A" }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $document->document_name }}</td>
                            <td class="py-3 border border-gray-300 px-6">
                                {{ $document->issuingDepartment ? $document->issuingDepartment->name : 'N/A' }}</td>
                            <td class="py-3 border border-gray-300 px-6"> {{ $document->release_date_formatted }}</td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <button class="bg-blue-400 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"
                                    onclick="window.location.href='{{ route('documents.show', $document) }}'">
                                    <i class="fas fa-info-circle"></i> <!-- Biểu tượng cho "Chi tiết" -->
                                </button>
                              
                               
                            </td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <!-- Button Edit -->
                                <button class="bg-yellow-300 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                                  onclick="window.location.href='{{ route('documents.edit', $document) }}'">
                                  <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                              </button>
                            </td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <form id="delete-form-{{ $document->id }}" action="{{ route('documents.destroy', $document) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="bg-red-400 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                        onclick="confirmDelete({{ $document->id }})">
                                        <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $documents->links() }} <!-- Render pagination links -->
            </div>
        </div>
    </div>
    <script>
    //       $(document).ready(function() {
    //     $('.select2').select2({
    //         placeholder: "Chọn cơ quan",
    //         allowClear: true
    //     });
    // });
        //============================ Search Input Code ====================================
        $(document).ready(function() {
        $('#document_code').on('keyup', function() {
            var query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: "{{ route('documents.search') }}",
                    type: "GET",
                    data: {'document_code': query},
                    success: function(data) {
                        $('#search-results ul').html(''); // Xóa kết quả cũ
                        if (data.length > 0) {
                            $.each(data, function(key, document) {
                                $('#search-results ul').append('<li class="p-2 cursor-pointer hover:bg-gray-200" data-code="'+document.document_code+'">' + document.document_code + '</li>');
                            });
                            $('#search-results').removeClass('hidden');
                        } else {
                            $('#search-results ul').append('<li class="p-2">Không có kết quả.</li>');
                            $('#search-results').removeClass('hidden');
                        }
                    }
                });
            } else {
                $('#search-results').addClass('hidden');
            }
        });
        $(document).on('click', function(event) {
            var selectedCode = $(this).data('code');  // Lấy giá trị từ thuộc tính data-code
            $('#document_code').value = selectedCode;    // Gán giá trị vào input
            $('#search-results').addClass('hidden');  // Ẩn danh sách sau khi chọn
        });
        $(document).on('click', '#search-results li', function() {
            $('#document_code').val($(this).text());
            $('#search-results').addClass('hidden'); 
        });
    });
     //============================End Search Input Code ====================================
      //============================ Search Input Name ====================================
      $(document).ready(function() {
        $('#document_name').on('keyup', function() {
            var query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: "{{ route('documents.search.name') }}",
                    type: "GET",
                    data: {'document_name': query},
                    success: function(data) {
                        $('#search-results-name ul').html(''); // Xóa kết quả cũ
                        if (data.length > 0) {
                            $.each(data, function(key, document) {
                                $('#search-results-name ul').append('<li class="p-2 cursor-pointer hover:bg-gray-200" data-code="'+document.document_name+'">' + document.document_name + '</li>');
                            });
                            $('#search-results-name').removeClass('hidden');
                        } else {
                            $('#search-results-name ul').append('<li class="p-2">Không có kết quả.</li>');
                            $('#search-results-name').removeClass('hidden');
                        }
                    }
                });
            } else {
                $('#search-results-name').addClass('hidden');
            }
        });
        $(document).on('click', function(event) {
            var selectedCode = $(this).data('code');  // Lấy giá trị từ thuộc tính data-code
            $('#document_name').value = selectedCode;    // Gán giá trị vào input
            $('#search-results-name').addClass('hidden');  // Ẩn danh sách sau khi chọn
        });
        $(document).on('click', '#search-results-name li', function() {
            $('#document_name').val($(this).text());
            $('#search-results-name').addClass('hidden'); 
        });
    });
     //============================End Search Input Name ====================================
        document.addEventListener('DOMContentLoaded', function() {
            var organizationTypeSelect = document.getElementById('organization_type_id');
            if(organizationTypeSelect && organizationTypeSelect.value){
                console.log(organizationTypeSelect);
                var organizationTypeId = organizationTypeSelect.value;
                fetchOrganizations(organizationTypeId);
            }
            var currentUrl = window.location.href;
            var params = new URLSearchParams(window.location.search);
            var organizationId = parseInt(params.get('organization_id'));

            var document_code =params.get('document_code');
            var document_name = params.get('document_name');
            console.log("params")
            console.log(document_name)
            if(!isNaN(organizationId)){
                var customInput = document.getElementById('organization_id');
                customInput.classList.remove('hidden');
            }
            if(document_code !== null || document_code !== undefined || document_code !== ""){
                var customInput = document.getElementById('document_code');
                customInput.value = document_code;
            }
            if(document_name !== null || document_name !== undefined || document_name !== ""){
                var customInput = document.getElementById('document_name');
                customInput.value = document_name;
            }

        //    document.getElementById('organization_type_id').addEventListener('change', function () {
        //         var organizationTypeId = this.value;
        //         fetch(`/get-organizations/${organizationTypeId}`)
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
        //                 var customInput = document.getElementById('organization_id');
        //                 customInput.classList.remove('hidden');
                
        //             });
        //         })
        //         .catch(error => console.error('Error:', error));
                
        //         // Gửi yêu cầu AJAX đến server để lấy danh sách organizations
        //         fetchOrganizations(organizationTypeId);
        //     });

            function fetchOrganizations(organizationTypeId) {
                // Gửi yêu cầu AJAX đến server để lấy danh sách organizations
                fetch(`/get-organizations/${organizationTypeId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Làm rỗng danh sách `parent_id`
                        var parentSelect = document.getElementById('parent_id');
                        var currentUrl = window.location.href;
                        var params = new URLSearchParams(window.location.search);
                        var organizationId = parseInt(params.get('organization_id'));

                        console.log(organizationId);
                        parentSelect.innerHTML = '<option value="" disabled selected>Chọn Cơ quan ban hành</option>';
                        // Thêm các tùy chọn mới
                        data.forEach(function (organization) {
                            var option = document.createElement('option');
                            option.value = organization.id;
                            option.text = organization.name;
                            parentSelect.appendChild(option);

                            if (organization.id === organizationId) {
                                console.log(organization.name);
                                parentSelect.value = organization.id;
                            }
                        
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
        //   document.getElementById('filterToggle').addEventListener('click', function() {
        //     const filterForm = document.getElementById('filterForm');
        //     filterForm.classList.toggle('hidden');
        // });
        // document.getElementById('organization_type_id').addEventListener('change', function () {
        //     console.log("test Fliter");
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
        //                 var customInput = document.getElementById('organization_id');
        //         customInput.classList.remove('hidden');
        //         var customInput = document.getElementById('organization_id_hidden');
        //         customInput.classList.add('hidden');
                
        //             });
        //         })
        //         .catch(error => console.error('Error:', error));
        // });
        function confirmDelete(id) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: 'Xóa văn bản này, có thể các mục tiêu, nhiệm vụ, kết quả liên quan cũng sẽ bị xóa. Khi đã xóa sẽ không lấy lại thông tin được!',
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
    </script>
    
@endsection
