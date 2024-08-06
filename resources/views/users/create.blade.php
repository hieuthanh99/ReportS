@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Tạo mới người dùng</h1>
        <a href="{{ route('users.index') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">Danh sách người dùng</a>
    </div>

    @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg relative">
            {{ session('success') }}
            <button id="close-message" class="absolute top-2 right-2 text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" class="bg-white border border-gray-300 rounded-lg shadow-lg p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="code" class="block text-gray-700 font-medium mb-2">Mã code</label>
                <input type="text" id="code" name="code" value="{{ old('code') }}" class="w-full border border-gray-300 rounded-lg p-2" required>
                @error('code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="name" class="block text-gray-700 font-medium mb-2">Tên</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg p-2" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-300 rounded-lg p-2" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
          
        <div>
            <label for="role" class="block text-gray-700 font-medium mb-2">Role</label>
            <select id="role" name="role"
            class="form-select w-full border border-gray-300 rounded-lg p-2">
            <option value="admin">Admin</option>
            <option value="staff">Nhân viên</option>
        </select>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
       
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">Mật khẩu</label>
                <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-lg p-2" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
           
            <div>
                <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full border border-gray-300 rounded-lg p-2" required>
                @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="organization_id" class="block text-gray-700 font-medium mb-2">Tổ chức</label>
                <select id="organization_id" name="organization_id" class="w-full border border-gray-300 rounded-lg p-2">
                    <option value="">Không có tổ chức</option>
                    @foreach($organizations as $organization)
                        <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                    @endforeach
                </select>
                @error('organization_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="phone" class="block text-gray-700 font-medium mb-2">Số điện thoại</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="w-full border border-gray-300 rounded-lg p-2">
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="address" class="block text-gray-700 font-medium mb-2">Địa chỉ</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" class="w-full border border-gray-300 rounded-lg p-2">
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Tạo người dùng</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 5000);
        }
    });
</script>
@endsection
