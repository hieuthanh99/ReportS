@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {!! Breadcrumbs::render('details.tasks.byType', $type) !!}
        </ol>
    </nav>
    <div class="overflow-hidden">
        <div class="p-6">
            @php
                $text = "Chỉ tiêu";
                if($type == 'task') $text = "Nhiệm vụ";

            @endphp
            <input type="hidden" name="type" id="type" value="{{ $type }}"/>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-white ">
                <!-- Cột trái -->
                <div class="flex items-center mb-4">
                    <span class="text-gray-700 font-medium w-1/3">Mã {{ $text }}:</span>
                    <span class="text-gray-900 w-2/3">{{ $taskTarget->code }}</span>
                </div>
                <div class="flex items-center mb-4">
                    <span class="text-gray-700 font-medium w-1/3">Tên {{ $text }}:</span>
                    <span class="text-gray-900 w-2/3">{{ $taskTarget->name }}</span>
                </div>
                <div class="flex items-center mb-4">
                    <span class="text-gray-700 font-medium w-1/3">Kết quả:</span>
                    <span class="text-gray-900 w-2/3">{{ $taskTarget->request_results }}</span>
                </div>
                <div class="flex items-center mb-4">
                    <span class="text-gray-700 font-medium w-1/3">Loại chu kì:</span>
                    <span class="text-gray-900 w-2/3">{{ $taskTarget->getCycleTypeTextAttribute() }}</span>
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
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-100 border-b border-gray-300" >
                    <tr>
                        <th colspan="7" class="border border-gray-300 py-3 px-6 text-left font-medium text-center">Cơ quan, tổ chức đã được giao</th>
                        
                    </tr>
                    <tr>
                        <th class="border border-gray-300 py-3 px-6 text-left font-medium">Mã cơ quan, tổ chức</th>
                        <th class="border border-gray-300 py-3 px-6 text-left font-medium">Tên cơ quan, tổ chức</th>
                        <th class="border border-gray-300 py-3 px-6 text-left font-medium">Loại cơ quan, tổ chức</th>
                        <th class="border border-gray-300 py-3 px-6 text-left font-medium">Tiến độ</th>
                        <th class="border border-gray-300 py-3 px-6 text-left font-medium">Đánh giá tiến độ</th>
                        <th class="border border-gray-300 py-3 px-6 text-left font-medium">Chu kỳ</th>
                        <th class="border border-gray-300 py-3 px-6 text-left font-medium">Kết quả</th>
                     
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paginatedResults as $index => $item)
                        <tr class="border-b border-gray-200">
                       
                            <td class="py-3 border border-gray-300 px-6">{{ $item['organization']->code }}</td>
                            <td style="width: 450px;" class="py-3 border border-gray-300 px-6">{{ $item['organization']->name }}</td>
                            <td class="py-3 border border-gray-300 px-6">
                                {{ $item['organization']->organizationType->type_name ?? "" }}
                            </td>
                            <td class="py-3 border border-gray-300 px-6">
                                {{ $item['task']->results ?? "" }}
                            </td>
                            <td class="py-3 border border-gray-300 px-6">
                                {{ $item['task']->getStatusLabelAttribute() ?? "" }}
                            </td>
                            <td class="py-3 border border-gray-300 px-6"> 
                                @php
                                $type = $item['latest_result']->type ?? null;
                                $numberType = $item['latest_result']->number_type ?? '';
                            @endphp
                                @switch($type)
                                    @case(1) {{-- Tuần --}}
                                        Tuần {{ $numberType }}
                                        @break
                                    @case(2) {{-- Tháng --}}
                                        Tháng {{ $numberType }}
                                        @break
                                    @case(3) {{-- Quý --}}
                                        Quý {{ $numberType }}
                                        @break
                                    @case(4) {{-- Năm --}}
                                        Năm {{ $numberType }}
                                        @break
                                    @default
                                        
                                @endswitch
                            </td>
                            <td class="py-3 border border-gray-300 px-6"> {{ $item['latest_result']->result ?? ''}}</td>
                        
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $paginatedResults->links() }}
            </div>
            <div class="mt-4 flex" style="justify-content: space-between">
                <button type="button"  id="back" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">Quay lại</button>
            </div>
        </div>
      
    </div>
</div>
<script>
     document.getElementById('back').addEventListener('click', function(event) {
                    event.preventDefault();
                    var type = document.getElementById('type');
                    var selectedValue = type.value;
                    // Chuyển hướng đến URL tương ứng với giá trị được chọn
                    if (selectedValue) {
                        window.location.href = `/tasks/type/${selectedValue}`;
                    }
                });
</script>
@endsection
