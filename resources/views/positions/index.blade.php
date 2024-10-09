@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                {!! Breadcrumbs::render('DSCV') !!}
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
        <!-- Search Form -->
        {{-- <form method="GET" action="{{ route('tasks.index') }}" class="">
            <div class="mb-6 flex flex-wrap gap-4 mb-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="document_name" class="block text-gray-700 font-medium mb-2">Tên nhiệm vụ/Chỉ tiêu:</label>
                    <input type="text" id="document_name" name="document_name" value="{{ request('document_name') }}"  placeholder="Tên văn bản"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="organization_id" class="block text-gray-700 font-medium mb-2">Phân loại:</label>
                    <select id="organization_id" name="organization_id" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="">Chọn đơn vị thực hiện</option>
                        @foreach($organizations as $organization)
                            <option value="{{ $organization->id }}" {{ request('organization_id') == $organization->id ? 'selected' : '' }}>
                                {{ $organization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex-1 min-w-[200px]">
                    <label for="execution_time" class="block text-gray-700 font-medium mb-2">Chu kỳ báo cáo:</label>
                    <select id="new-task-reporting-cycle" name="reporting_cycle"
                            class="form-select w-full border border-gray-300 rounded-lg p-2">
                            <option value="">Lựa chọn chu kỳ</option>
                            <option value="1">Tuần</option>
                            <option value="2">Tháng</option>
                            <option value="3">Quý</option>
                            <option value="4">Năm</option>
                        </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label for="type" class="block text-gray-700 font-medium mb-2">Loại:</label>
                    <select id="new-task-reporting-cycle" name="type"
                            class="form-select w-full border border-gray-300 rounded-lg p-2">
                            <option value="">Lựa chọn chu kỳ</option>
                            <option value="task">Nhiệm vụ</option>
                            <option value="target">Chỉ tiêu</option>
                        </select>
                </div>
        </div>

        <div class="flex justify-end gap-4">
            <button type="submit"
            class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">
            Tìm kiếm
        </button>
        </div>
    </form> --}}
    <div class="mb-6 flex justify-end gap-4 mb-4">
        <a href="{{ route('positions.create') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"> Thêm mới</i></a>
    </div>
   
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                    <tr>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">STT</th>
                        <th style="width: 450px;" class="py-3 px-6 text-left text-gray-700 font-medium text-center">Mã chức vụ</th>
                        <th style="width: 450px;" class="py-3 px-6 text-left text-gray-700 font-medium text-center">Tên chức vụ</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                            Cập nhật
                        </th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                            Xóa
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($types as $index => $item)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 border border-gray-300 px-6 text-center">{{ $index + $types->firstItem() }}</td>
                            <td class="py-3 border border-gray-300 px-6 text-center">{{ $item->code }}</td>
                            <td class="py-3 border border-gray-300 px-6 text-center">{{ $item->name }}</td>
                          
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <button class="bg-yellow-300 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                                    onclick="window.location.href='{{ route('positions.edit', $item) }}'">
                                    <i class="fas fa-edit"></i> 
                                </button>
                            </td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <form id="delete-form-{{ $index + $types->firstItem() }}" action="{{ route('positions.destroy', $item) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="bg-yellow-300 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                        onclick="confirmDelete({{ $index + $types->firstItem() }})">
                                        <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                    </button>
                                </form>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $types->links() }}
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(id) {
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
                    document.getElementById('delete-form-'+ id).submit();
                }
            });
        }
    </script>
@endsection
