@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <script></script>
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
        <!-- Search Form -->
        <form method="GET" action="{{ route('documents.index') }}" class="">
            <div class="mb-6 flex flex-wrap gap-4 mb-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="document_code" class="block text-gray-700 font-medium mb-2">Số hiệu văn bản</label>
                    <input type="text" id="document_code" name="document_code" value="{{ request('document_code') }}"  placeholder="Số hiệu văn bản"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="document_name" class="block text-gray-700 font-medium mb-2">Trích yếu văn bản</label>
                    <input type="text" id="document_name" name="document_name" value="{{ request('document_name') }}"  placeholder="Tên văn bản"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                </div>
            
                <div class="flex-1 min-w-[200px]">
                    <label for="organization_id" class="block text-gray-700 font-medium mb-2">Cơ quan phát hành</label>
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
                    <label for="execution_time" class="block text-gray-700 font-medium mb-2">Thời gian thực hiện</label>
                    <input type="date" placeholder="dd-mm-yyyy"
                    min="1997-01-01" max="2100-12-31" name="execution_time" value="{{ request('execution_time') }}"
                    class="border border-gray-300 rounded-lg p-2 w-full" placeholder="Thời gian thực hiện">
                </div>
        </div>

        <div class="flex justify-end gap-4">
            <button type="submit"
            class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">
            Tìm kiếm
            </button>
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'supper_staff')
            <a href="{{ route('documents.create') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">Thêm mới văn bản</a>
            @endif
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
                                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"
                                    onclick="window.location.href='{{ route('documents.show', $document) }}'">
                                    <i class="fas fa-info-circle"></i> <!-- Biểu tượng cho "Chi tiết" -->
                                </button>
                              
                               
                            </td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <!-- Button Edit -->
                                <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                                  onclick="window.location.href='{{ route('documents.edit', $document) }}'">
                                  <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                              </button>
                            </td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <form id="delete-form" action="{{ route('documents.destroy', $document) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                        onclick="confirmDelete()">
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
        function confirmDelete() {
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
            document.getElementById('delete-form').submit();
        }
    });
}
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#organization_id', {
                placeholder: 'Chọn đơn vị phát hành',
                allowEmptyOption: true,
                onInitialize: function() {
                    console.log('Tom Select initialized!');
                }
            });
        });
    </script>
    
@endsection
