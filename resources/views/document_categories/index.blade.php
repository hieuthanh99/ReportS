@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
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
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {!! Breadcrumbs::render('LVB') !!}
        </ol>
    </nav>
    <div class="mb-6 flex gap-4 mb-4" style="justify-content: space-between">
        <span style="padding: 10px 0;">Tổng số lượng: {{ $countsl }} bản ghi</span>
        <div>
            <a href="{{ route('document_categories.create') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300"> Thêm mới</i></a>
            <a href="{{ route('export.Document.Category') }}" target="_blank" style="cursor: pointer;" class="inline-block bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300 mb-4">Xuất Excel</a>
        </div>
    </div>
    
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                    <tr>
      
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">STT</th>
                        <th style="width: 450px;" class="py-3 px-6 text-left text-gray-700 font-medium text-center">Mã loại văn bản</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Tên loại văn bản</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Chi tiết</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                            Cập nhật
                        </th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                            Xóa
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $index => $item)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 border border-gray-300 px-6 text-center">{{ $index + $categories->firstItem() }}</td>
                            <td class="py-3 border border-gray-300 px-6 text-center">{{ $item->code }}</td>
                            <td style="width: 450px;" class="py-3 border border-gray-300 px-6">{{ $item->name }}</td>
                            <td class="py-3 border border-gray-300 px-6">
                                {{ $item->description }}
                            </td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                <button class="bg-yellow-300 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                                    onclick="window.location.href='{{ route('document_categories.edit', $item) }}'">
                                    <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                                </button>
                            </td>
                            <td class="py-3 border border-gray-300 px-6 text-center">
                                {{-- <form action="{{ route('document_categories.destroy', $item) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-400 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                        onclick="return confirm('Bạn có chắc chắn rằng muốn xóa văn bản này?');">
                                        <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                    </button>
                                </form> --}}
                                <form action="{{ route('document_categories.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="confirmBeforeDelete({ event, text: 'Xóa văn bản này, có thể các mục tiêu, nhiệm vụ, kết quả liên quan cũng sẽ bị xóa. Khi đã xóa sẽ không lấy lại thông tin được!' })">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit"
                                        class="bg-red-400 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                        >
                                        <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                    </button>
                                </form>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $categories->links() }} <!-- Render pagination links -->
            </div>
        </div>
    </div>
@endsection
