@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <script></script>
    <div class="container mx-auto px-4 py-6">
        {{-- <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Danh sách văn bản</h1>
        </div> --}}

        <!-- Search Form -->
        <form method="GET" action="{{ route('documents.index') }}" class="">

        <div class="mb-6 flex flex-wrap gap-4 mb-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="document_name" class="block text-gray-700 font-medium mb-2">Tên văn bản</label>
                    <input type="text" id="document_name" name="document_name" value="{{ request('document_name') }}"  placeholder="Tên văn bản"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="organization_id" class="block text-gray-700 font-medium mb-2">Đơn vị phát hành</label>
                    <select name="organization_id" class="border border-gray-300 rounded-lg p-2 w-full">
                        <option value="">Đơn vị thực hiện</option>
                        @foreach($organizations as $organization)
                            <option value="{{ $organization->id }}" {{ request('organization_id') == $organization->id ? 'selected' : '' }}>
                                {{ $organization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label for="execution_time" class="block text-gray-700 font-medium mb-2">Thời gian thực hiện</label>
                    <input type="date" name="execution_time" value="{{ request('execution_time') }}"
                    class="border border-gray-300 rounded-lg p-2 w-full" placeholder="Thời gian thực hiện">
                </div>
       
        </div>

        <div class="flex justify-end gap-4 mb-6">
            <button type="submit"
            class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">
            Tìm kiếm
        </button>
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('documents.create') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4"> <i class="fas fa-plus"></i></a>
        @endif
        </div>
    </form>
        @if (session('success'))
            <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg relative">
                {{ session('success') }}
                <button id="close-message" class="absolute top-2 right-2 text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                    <tr>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">STT</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Mã văn bản</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Tên văn bản</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Đơn vị phát hành</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Thời gian ban hành</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $index => $document)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 border border-gray-300 px-6">{{ $index + $documents->firstItem() }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $document->document_code }}</td>
                            <td class="py-3 border border-gray-300 px-6">{{ $document->document_name }}</td>
                            <td class="py-3 border border-gray-300 px-6">
                                {{ $document->issuingDepartment ? $document->issuingDepartment->name : 'N/A' }}</td>
                            <td class="py-3 border border-gray-300 px-6"> {{ $document->release_date_formatted }}</td>
                            <td class="py-3 border border-gray-300 px-6">
                                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"
                                    onclick="window.location.href='{{ route('documents.show', $document) }}'">
                                    <i class="fas fa-info-circle"></i> <!-- Biểu tượng cho "Chi tiết" -->
                                </button>
                                <!-- Button Edit -->
                                <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                                    onclick="window.location.href='{{ route('documents.edit', $document) }}'">
                                    <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                                </button>
                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('documents.destroy', $document) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                            onclick="return confirm('Bạn có chắc chắn rằng muốn xóa văn bản này?');">
                                            <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                        </button>
                                    </form>
                                @endif
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
@endsection
