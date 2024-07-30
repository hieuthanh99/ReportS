@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Danh mục phân loại</h1>
        <a href="{{ route('categories.create') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">Tạo mới</a>
    </div>
  
    @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg relative">
            {{ session('success') }}
            <button id="close-message" class="absolute top-2 right-2 text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
            <thead class="bg-gray-100 border-b border-gray-300">
                <tr>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Mã danh mục</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Tên danh mục</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr class="border-b border-gray-200">
                        <td class="py-3 px-6">{{ $category->CategoryID }}</td>
                        <td class="py-3 px-6">{{ $category->CategoryName }}</td>
                      
                        <td class="py-3 px-6 flex items-center space-x-2">
                            <!-- Chỉnh sửa danh mục -->
                            <a href="{{ route('categories.edit', $category->CategoryID) }}" class="text-blue-600 hover:text-blue-800 transition duration-300" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                        
                            <!-- Xóa danh mục -->
                            <form action="{{ route('categories.destroy', $category->CategoryID) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition duration-300" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-children').forEach(function(button) {
        button.addEventListener('click', function() {
            const children = button.closest('li').querySelector('.children');
            if (children) {
                children.classList.toggle('hidden');
                button.textContent = children.classList.contains('hidden') ? '+' : '-';
            }
        });
    });
});

</script>
@endsection
