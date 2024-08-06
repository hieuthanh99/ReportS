@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800"></h1>
        <a href="{{ route('categories.create') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4"> <i class="fas fa-plus"></i></a>
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
            <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                <tr>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">STT</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Mã danh mục</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Tên danh mục</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $index => $category)
                    <tr class="border-b border-gray-200">
                        <td class="py-3 border border-gray-300 px-6">{{ $index + $categories->firstItem() }}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ $category->CategoryID }}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ $category->CategoryName }}</td>
                      
                        <td class="py-3 border border-gray-300 px-6 flex items-center space-x-2">
                            <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                                    onclick="window.location.href='{{ route('categories.edit', $category->CategoryID) }}'">
                                    <i class="fas fa-edit"></i> <!-- Biểu tượng cho "Cập nhật" -->
                                </button>
                                <form action="{{ route('categories.destroy', $category->CategoryID) }}"" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                    class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                        onclick="return confirm('Bạn có chắc chắn rằng muốn xóa văn bản này?');">
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
