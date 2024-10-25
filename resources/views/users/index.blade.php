
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {!! Breadcrumbs::render('DSTK') !!}
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
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold mb-6 text-gray-800"></h1>
        <div class="mb-6 flex justify-end gap-4 mb-4">
            <a href="{{ route('users.create') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                    Tạo người dùng
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg" >
            <thead class="bg-gray-100 border-b border-gray-300" style="background: #D4D4CF;">
                <tr>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">STT</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Mã danh mục</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Tên danh mục</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Tổ chức</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Chức vụ</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Email</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Số điện thọai</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">Role</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                        Cập nhật
                    </th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium text-center">
                        Xóa
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $category)
                    <tr class="border-b border-gray-200">
                        <td class="py-3 border border-gray-300 px-6">{{ $index + $users->firstItem() }}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ $category->code }}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ $category->name }}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ optional($category->organization)->name ?: 'No Organization' }}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ optional($category->position)->name ?: 'No Position' }}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ $category->email }}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ $category->phone }}</td>
                        <td class="py-3 border border-gray-300 px-6"> 
                            @if($category->role === 'supper_admin')
                            Supper Admin
                            @elseif($category->role === 'admin')
                                Admin
                            @elseif($category->role === 'sub_admin')
                                Sub-Admin
                            @elseif($category->role === 'staff')
                            Nhân viên
                            @else
                                Không xác định
                            @endif
                        </td>
                        <td class="py-3 border border-gray-300 px-6 text-center">   
                            <button class="bg-yellow-300 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
                            onclick="window.location.href='{{ route('users.edit', $category->id) }}'">
                            <i class="fas fa-edit"></i>
                            </button>
                        </td>
                        <td class="py-3 border border-gray-300 px-6 text-center">  
                            <form id="delete-form-{{ $index + $users->firstItem() }}" action="{{ route('users.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="confirmBeforeDelete({ event })">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-400 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2">
                                    <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $users->links() }} <!-- Render pagination links -->
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
// <td class="py-3 border border-gray-300 px-6 flex items-center text-center">
//                             <button class="bg-yellow-300 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300 ml-2"
//                                 onclick="window.location.href='{{ route('users.edit', $category->id) }}'">
//                                 <i class="fas fa-edit"></i>
//                             </button>
//                         </td>
//                         <td class="py-3 border border-gray-300 px-6 flex items-center text-center">
//                             <form action="{{ route('users.destroy', $category->id) }}" method="POST"
//                                 style="display:inline;">
//                                 @csrf
//                                 @method('DELETE')
//                                 <button type="submit"
//                                 class="bg-yellow-300 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"

//                                     onclick="return confirm('Bạn có chắc chắn rằng muốn xóa văn bản này?');">
//                                     <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->

//                                 </button>
//                             </form>
//                         </td>
</script>
@endsection
