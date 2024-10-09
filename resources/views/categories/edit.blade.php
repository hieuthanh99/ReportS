@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Sửa danh mục</h1>
    <form action="{{ route('categories.update', $category->CategoryID) }}" method="POST" class="bg-white p-6 rounded-lg shadow-lg">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="CategoryName" class="block text-gray-700 text-sm font-medium mb-2">Tên danh mục</label>
            <input type="text" id="CategoryName" name="CategoryName" value="{{ $category->CategoryName }}" class="form-input w-full border border-gray-300 rounded-lg p-2" required
            oninvalid="this.setCustomValidity('Vui lòng nhập tên danh mục.')" 
                    oninput="setCustomValidity('')">
        </div>
        <button type="submit" class="bg-blue-400 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Cập nhật</button>
    </form>
</div>
@endsection
