@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
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
      {{--   {!! Breadcrumbs::render('home') !!} --}}
        <a href="{{ url('export-Document') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">Xuất Excel</a>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                    <tr>
                        <th colspan="13" class="py-3 px-6 text-center text-gray-700 font-bold text-xl border border-black-300">BÁO CÁO TỔNG HỢP KẾT QUẢ THỰC HIỆN NHIỆM VỤ CHỈ TIÊU THEO VĂN BẢN</th>
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
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Mã văn bản</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium border border-black-300">Tên văn bản</th>
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
                            <td class="py-3 border border-gray-300 px-6">{{ $data->document_code }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $data->document_name }}</td>
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
@endsection
