@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                {!! Breadcrumbs::render('THTDV') !!}
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
        @php
        session(['success' => null])
        @endphp
        <form method="GET" action="{{ route('reports.withUnit') }}" id="filterForm">
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
                <div class="flex-1 min-w-[200px]"></div>
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
                                </div>

        <div class="flex justify-end gap-4">
            <a onclick="exportExcel()" target="_blank" class="inline-block bg-blue-400 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">Xuất Excel</a>
            <button type="submit"
                class="inline-block bg-blue-400 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">
                Tìm kiếm
                </button>
        
        </div>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                    <tr>
                        <th colspan="13" class="py-3 px-6 text-center text-gray-700 font-bold text-xl border border-black-300">BÁO CÁO THỐNG KÊ KẾT QUẢ THỰC HIỆN NHIỆM VỤ CHỈ TIÊU THEO ĐƠN VỊ</th>
                    </tr>
                    <tr>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300"></th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300"></th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300"></th>
                        <th colspan="5" class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Nhiệm vụ</th>
                        <th colspan="5" class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Chỉ tiêu</th>
                    </tr>
                    <tr>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300"></th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300"></th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300"></th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300"></th>
                        <th colspan="2" class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Hoàn thành</th>
                        <th colspan="2" class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Đang thực hiện</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300"></th>
                        <th colspan="2" class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Hoàn thành</th>
                        <th colspan="2" class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Đang thực hiện</th>
                    </tr>
                    <tr>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">STT</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Loại cơ quan</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Cơ quan</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Tổng số</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Đúng hạn</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Quá hạn</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Trong hạn</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Quá hạn</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Tổng số</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Đúng hạn</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Quá hạn</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Trong hạn</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Quá hạn</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $index => $data)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 border border-gray-300 px-6">{{ $index + $datas->firstItem() }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->code }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->name }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->task_count }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->task_completed_in_time }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->task_completed_overdue }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->task_in_progress_in_time }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->task_in_progress_overdue }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->target_count }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->target_completed_in_time }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->target_completed_overdue }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->target_in_progress_in_time }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->target_in_progress_overdue }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $datas->links() }}
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

        // Tạo URL cho xuất Excel kèm theo các tham số
        var url = "{{ url('export-Unit') }}";
        let urlQuery = url  + 
        `?document_id=${document_id}` + 
        `&execution_time_from=${executionTimeFrom}` + 
        `&execution_time_to=${executionTimeTo}`;

       // Chuyển hướng đến URL xuất Excel
       window.location.href = urlQuery;
    }
    </script>
@endsection
