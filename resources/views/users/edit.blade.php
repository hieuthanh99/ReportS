@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 bg-white shadow-md rounded-lg overflow-hidden">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {!! Breadcrumbs::render('CNTK') !!}
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
    <form action="{{ route('users.update', $user->id) }}" method="POST" onsubmit="confirmBeforeSave({ event })">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            <div class="mb-4">
                <label for="code" class="block text-gray-700">Mã code <span class="text-red-500">*</span></label>
                <input type="text" id="code" name="code" value="{{ old('code', $user->code) }}" class="w-full border border-gray-300 rounded-lg p-2" readonly>
                <!-- @error('code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Tên <span class="text-red-500">*</span>:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required
                oninvalid="this.setCustomValidity('Vui lòng nhập tên.')" 
                    oninput="setCustomValidity('')">
                <!-- @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror -->
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email <span class="text-red-500">*</span>:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required
                oninvalid="this.setCustomValidity('Vui lòng nhập Email.')" 
                    oninput="setCustomValidity('')">
                <!-- @error('email')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror -->
            </div>

            <div class="mb-4">
                <label for="role" class="block text-gray-700">Role:</label>
                <select id="role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg select2">
                    <option value="supper_admin" {{ old('role', $user->role) == 'supper_admin' ? 'selected' : '' }}>Supper Admin</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="sub_admin" {{ old('role', $user->role) == 'sub_admin' ? 'selected' : '' }}>Sub-Admin</option>
                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Nhân viên</option>

                </select>
                <!-- @error('role')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror -->
            </div>
           
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Mật khẩu <span class="text-red-500">*</span>:</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg" require
                oninvalid="this.setCustomValidity('Vui lòng nhập mật khẩu.')" 
                    oninput="setCustomValidity('')">
                <p class="text-gray-500 text-sm">Để trống nếu không thay đổi mật khẩu.</p>
                <!-- @error('password')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror -->
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700">Xác nhận mật khẩu <span class="text-red-500">*</span>:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg" require
                oninvalid="this.setCustomValidity('Vui lòng nhập lại mật khẩu.')" 
                    oninput="setCustomValidity('')">
                <!-- @error('password_confirmation')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror -->
            </div>

            <div class="mb-4">
                <label for="organization_id" class="block text-gray-700">Tổ chức:</label>
                <select id="organization_id" name="organization_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg select2">
                    <option value="">Không có tổ chức</option>
                    @foreach($organizations as $organization)
                        <option value="{{ $organization->id }}" {{ $user->organization_id == $organization->id ? 'selected' : '' }}>
                            {{ $organization->name }}
                        </option>
                    @endforeach
                </select>
                <!-- @error('organization_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror -->
            </div>
            <div class="mb-4">
                <label for="position_id" class="block text-gray-700">Chức vụ:</label>
                <select id="position_id" name="position_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg select2">
                    <option value="">Không có chức vụ</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}" {{ $user->position_id == $position->id ? 'selected' : '' }}>
                            {{ $position->name }}
                        </option>
                    @endforeach
                </select>
                <!-- @error('position_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror -->
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-gray-700">Số điện thoại:</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <!-- @error('phone')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror -->
            </div>

            <div class="mb-4">
                <label for="address" class="block text-gray-700">Địa chỉ:</label>
                <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <!-- @error('address')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror -->
            </div>
        </div>
        
            <div class="mt-4 flex" style="justify-content: space-between">
                <a onclick="window.history.back()" class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-gray-700 transition duration-300 mr-2">Quay lại</a>
                <button type="submit" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Cập nhật</button>
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
