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
        <form method="GET" action="{{ route('documents.report') }}" id="filterForm">
            <div class="mb-6 flex flex-wrap gap-4 mb-4">
                <!-- Các trường khác -->
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
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-gray-700 font-medium mb-2">Trạng thái báo cáo:</label>
                    <select id="status" name="status" class="border border-gray-300 rounded-lg p-2 w-full select2">
                        <option value="">Chọn trạng thái</option>
                        <!-- <option value="new">Báo cáo chưa giao việc</option>
                        <option value="complete">Hoàn thành</option> -->
                        <option value="assign">Chưa báo cáo</option>
                        <option value="reject">Bị từ chối</option>
                        <option value="admin_approves">Đã phê duyệt</option>
                        <option value="staff_complete">Chờ phê duyệt</option>
                        <option value="sub_admin_complete">Đã báo cáo</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="status_result" class="block text-gray-700 font-medium mb-2">Tiến độ:</label>
                        <select id="status_result" name="status_result" class="border border-gray-300 rounded-lg p-2 w-full select2">
                            <option value="">Chọn tiến độ</option>
                            <option value="complete_on_time">Hoàn thành đúng hạn</option>
                            <option value="complete_late">Hoàn thành quá hạn</option>
                            <option value="processing">Đang thực hiện</option>
                            <option value="overdue">Quá hạn</option>
                            <option value="upcoming_due">Sắp tới hạn</option>
                        </select>                
                </div>
                
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
                <div class="flex-1 min-w-[200px]">
                    <label for="completion_date" class="block text-gray-700 font-medium mb-2">Thời hạn hoàn thành:</label>
                    <input type="month" id="completion_date" name="completion_date"  placeholder="Chọn tháng/năm"
                    class="border border-gray-300 rounded-lg p-2 w-full">
                </div>
                {{-- <div class="flex-1 min-w-[200px] hidden" id="organization_id">
                    <label for="organization_id" class="block text-gray-700 font-medium mb-2">&nbsp; </label>
                    <select name="organization_id" id="parent_id" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="" {{ old('organization_id') ? '' : 'selected' }}>Chọn cơ quan tổ chức</option>
                    </select>
                </div> --}}
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
                <a class="fa fa-filter" style="margin-top: 45px;cursor: pointer;" onclick="window.location.href='{{ route('documents.report') }}'"></a>
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
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Tên nhiệm vụ</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Cơ quan ban hành</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Thời hạn hoàn thành</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Số hiệu văn bản</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Trạng thái báo cáo</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Tiến độ</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Chi tiết</th>
                        @if(Auth::user()->role === 'staff')
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Báo cáo</th>
                        @else
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Phê duyệt</th>
                        @endif
                        {{-- @if(Auth::user()->role !== 'staff')
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Xóa</th>
                        @endif --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($taskDocuments as $index => $document)
                       @if($document->taskTarget)
                       <tr class="border-b border-gray-200">
                        <td class="py-3 border border-gray-300 px-6">{{ $index + $taskDocuments->firstItem() }}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ $document->taskTarget->name ?? ''}}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ $document->document->issuingDepartment->name ?? '' }}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ $document->taskTarget->getEndDate() ?? '' }}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ $document->document->document_code ?? '' }}</td>
                        <td class="py-3 border border-gray-300 px-6"> {{ $document->getStatusLabelAttributeTaskTarget() ?? ''  }}</td>
                        <td class="py-3 border border-gray-300 px-6"> {{ $document->taskTarget->getStatusLabel() }}</td>
                        <td class="py-3 border border-gray-300 px-6">
                            <button class="bg-blue-400 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"
                            onclick="window.location.href='{{ route('documents.report.details', $document->id) }}'">
                            <i class="fas fa-info-circle"></i> <!-- Biểu tượng cho "Chi tiết" -->
                            </button>
                        </td>
                        <td class="py-3 border border-gray-300 px-6">
                            @if ($document->getStatusLabelAttributeTaskTarget() !== 'Đã phê duyệt')
                                <button class="bg-yellow-300 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                                    onclick="window.location.href='{{ route('documents.report.update', $document) }}'">
                                    <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                                </button>
                            @else
                                <button class="text-white px-4 py-2 rounded-lg shadow transition duration-300 ml-2" style="background-color: rgb(202, 138, 4);"
                                    onclick="window.location.href='{{ route('documents.report.update', $document) }}'" disabled>
                                    <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                                </button>
                            @endif    
                        </td>
                        {{-- @if(Auth::user()->role !== 'staff')
                        <td class="py-3 border border-gray-300 px-6">
                           
                            <form id="delete-form-{{ $document->id }}" action="{{ route('documents.destroy', $document) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="bg-red-400 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                    onclick="confirmDelete({{ $document->id }})">
                                    <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                </button>
                            </form>
                        </td>
                        @endif --}}
                        {{-- <td class="py-3 border border-gray-300 px-6" style="display: flex;text-align: center;"></td>
                        <td class="py-3 border border-gray-300 px-6" style="display: flex;text-align: center;">
                           
                        </td>
                        <td class="py-3 border border-gray-300 px-6" style="    display: flex;
                        text-align: center;">
                               
                        </td> --}}
                        
                    </tr>
                       @endif
                      
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $taskDocuments->links() }} <!-- Render pagination links -->
            </div>
        </div>
    </div>
    <script>
          $(document).ready(function() {
            var currentUrl = window.location.href;
            var params = new URLSearchParams(window.location.search);
            var organizationId = parseInt(params.get('organization_id'));

            var document_code = params.get('document_code');
            var status = params.get('status');
            var status_result = params.get('status_result');
            var completion_date = params.get('completion_date');

            if (!isNaN(organizationId)) {
                var customInput = document.getElementById('organization_id');
                customInput.classList.remove('hidden');
            }
            if (document_code !== null || document_code !== undefined || document_code !== "") {
                var customInput = document.getElementById('document_code');
                customInput.value = document_code;
            }
            if (status !== null || status !== undefined || status !== "") {
                var customInput = document.getElementById('status');
                customInput.value = status;
            }
            if (status_result !== null || status_result !== undefined || status_result !== "") {
                var customInput = document.getElementById('status_result');
                customInput.value = status_result;
            }
            if(completion_date !== null || completion_date !== undefined || completion_date !== ""){
                var customInput = document.getElementById('completion_date');
                customInput.value = completion_date;
            }
        });
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
                        console.log(data);
                        $('#search-results ul').html('');
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
            var selectedCode = $(this).data('code');
            // var customInput = document.getElementById('document_code');
            // customInput.value = selectedCode;  
            $('#document_code').value = selectedCode;   
            $('#search-results').addClass('hidden'); 
        });
        $(document).on('click', '#search-results li', function() {
            console.log($(this).text());
            var customInput = document.getElementById('document_code');
            customInput.value = ($(this).text());
            // $('#document_code').value = ($(this).text());
            $('#search-results').addClass('hidden'); 
        });
    });
     //============================End Search Input Code ====================================
             $(document).ready(function() {
        $('.select2').select2({

            // allowClear: true
        });
        // Xử lý tìm kiếm
        $('.select2').on('select2:open', function() {
            let searchField = $('.select2-search__field');
            
            // Lắng nghe sự kiện khi người dùng nhập vào ô tìm kiếm
            searchField.on('input', function() {
                let searchTerm = $(this).val();

                // Nếu search term chỉ là dấu cách, xóa dấu cách
                if (searchTerm.trim() === "") {
                    $(this).val(''); // Xóa chuỗi chỉ chứa khoảng trắng
                }
            });
        });
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
                confirmButtonText: 'Xoá!',
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