@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                {!! Breadcrumbs::render('THCT') !!}
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
        @php
        session(['success' => null])
        @endphp
        <!-- <a href="{{ route('task-documents.export-details') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">Xuất Excel</a> -->
        <form method="GET" action="{{ route('reports.withDetails') }}" id="filterForm">
        </button>
        <a onclick="exportExcel()" target="_blank" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">Xuất Excel</a>
        <form method="GET" action="{{ route('reports.withDetails') }}" class="hidden" id="filterForm">
            <div class="mb-6 flex flex-wrap gap-4 mb-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="document_id" class="block text-gray-700 font-medium mb-2">Tên văn bản:</label>
                    <select id="document_id" name="document_id" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="">Chọn loại cơ quan thực hiện</option>

                        @foreach($documentsSearch as $item)
                           
                            <option value="{{ $item->id }}" {{ request('document_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->document_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="type" class="block text-gray-700 font-medium mb-2">Loại:</label>
                    <select id="type" name="type" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="">Chọn loại</option>
                        <option value="task">Nhiệm vụ</option>
                        <option value="target">Chỉ tiêu</option>
                    </select>
                </div>
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
            </div>

            <div class="flex justify-end gap-4">
            <a href="{{ route('task-documents.export-details') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">Xuất Excel</a>
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
                        <th colspan="12" class="py-3 px-6 text-center text-gray-700 font-bold text-xl border border-black-300">BÁO CÁO CHI TIẾT CHỈ TIÊU/ NHIỆM VỤ</th>
                    </tr>
                    <tr>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">STT</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Mã văn bản</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Tên văn bản</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Mã chỉ tiêu/ nhiệm vụ</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Tên chỉ tiêu/ nhiệm vụ</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Loại Cơ quan thực hiện</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Loại chu kỳ</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Cơ quan thực hiện</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Tiến độ</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Đánh giá tiến độ</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Kết quả</th>
                      
                    </tr>
                </thead>
                <tbody>
                    @foreach ($taskDocuments as $index => $data)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 border border-gray-300 px-6">{{ $index + $taskDocuments->firstItem() }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->document->document_code }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->document->document_name }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->code }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->name }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->organization->organizationType->type_name }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->getCycleTypeTextAttribute() }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->organization->name }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->results }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->getTaskStatusDescription() }}</td>
                           
                            <td class="py-3 border border-gray-300 px-6">{{ $data->latestTaskResult()->result ?? '' }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $taskDocuments->links() }}
            </div>
        </div>
    </div>
    <script>
            document.getElementById('filterToggle').addEventListener('click', function() {
            const filterForm = document.getElementById('filterForm');
            filterForm.classList.toggle('hidden');
        });
        function exportExcel() {

        let params = new URLSearchParams(window.location.search);
        let document_id = document.getElementById('document_id').value;
        let executionTimeFrom = document.getElementById('execution_time_from').value;
        let executionTimeTo = document.getElementById('execution_time_to').value;
        let type = params.get('type') || null;
        
        // Tạo URL cho xuất Excel kèm theo các tham số
        var url = "{{ url('export-Document-Details') }}";
        let urlQuery = url  + 
        `?document_id=${document_id}` + 
        `&type=${type}` + 
        `&execution_time_from=${executionTimeFrom}` + 
        `&execution_time_to=${executionTimeTo}`;

       // Chuyển hướng đến URL xuất Excel
       window.location.href = urlQuery;
    }
        </script>
@endsection
