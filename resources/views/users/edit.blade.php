@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Cập nhật người dùng</h1>

    @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg relative">
            {{ session('success') }}
            <button id="close-message" class="absolute top-2 right-2 text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700">Tên:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
            @error('name')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
            @error('email')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label for="role" class="block text-gray-700">Vai trò:</label>
            <select id="role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Nhân viên</option>
            </select>
            @error('role')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Mật khẩu:</label>
            <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            <p class="text-gray-500 text-sm">Để trống nếu không thay đổi mật khẩu.</p>
            @error('password')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700">Xác nhận mật khẩu:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            @error('password_confirmation')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="organization_id" class="block text-gray-700">Tổ chức:</label>
            <select id="organization_id" name="organization_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <option value="">Không có tổ chức</option>
                @foreach($organizations as $organization)
                    <option value="{{ $organization->id }}" {{ $user->organization_id == $organization->id ? 'selected' : '' }}>
                        {{ $organization->name }}
                    </option>
                @endforeach
            </select>
            @error('organization_id')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-gray-700">Số điện thoại:</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            @error('phone')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="address" class="block text-gray-700">Địa chỉ:</label>
            <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            @error('address')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Cập nhật</button>
        </div>
    </form>
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
