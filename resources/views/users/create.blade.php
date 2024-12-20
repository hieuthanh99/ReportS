@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 bg-white shadow-md rounded-lg overflow-hidden">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {!! Breadcrumbs::render('CTK') !!}
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
    @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg relative">
            {{ session('success') }}
            <button id="close-message" class="absolute top-2 right-2 text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" onsubmit="confirmBeforeCreate({ event })">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="code" class="block text-gray-700 font-medium mb-2">Mã code <span class="text-red-500">*</span></label>
                <input type="text" id="code" name="code" value="{{ old('code') }}" class="w-full border border-gray-300 rounded-lg p-2" required
                oninvalid="this.setCustomValidity('Vui lòng nhập mã code.')" 
                    oninput="setCustomValidity('')">
                <!-- @error('code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>
            <div>
                <label for="name" class="block text-gray-700 font-medium mb-2">Tên <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg p-2" required
                oninvalid="this.setCustomValidity('Vui lòng nhập tên.')" 
                    oninput="setCustomValidity('')">
                <!-- @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-300 rounded-lg p-2" required
                oninvalid="this.setCustomValidity('Vui lòng nhập Email.')" 
                    oninput="setCustomValidity('')">
                <!-- @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>
          
        <div>
            <label for="role" class="block text-gray-700 font-medium mb-2">Role <span class="text-red-500">*</span></label>
            <select id="role" name="role"
            class="form-select w-full border border-gray-300 rounded-lg p-2 select2">
            <option value="supper_admin">Supper Admin</option>
            <option value="admin">Admin</option>
            <option value="sub_admin">Sub-Admin</option>
            <option value="staff">Nhân viên</option>
        </select>
            <!-- @error('role')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror -->
        </div>
       
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">Mật khẩu <span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-lg p-2" required
                oninvalid="this.setCustomValidity('Vui lòng nhập mật khẩu.')" 
                    oninput="setCustomValidity('')">
                <!-- @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>
           
            <div>
                <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Xác nhận mật khẩu <span class="text-red-500">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full border border-gray-300 rounded-lg p-2" required
                oninvalid="this.setCustomValidity('Vui lòng nhập lại mật khẩu.')" 
                    oninput="setCustomValidity('')">
                <!-- @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>
            <div>
                <label for="organization_id" class="block text-gray-700 font-medium mb-2">Tổ chức</label>
                <select id="organization_id" name="organization_id" class="w-full border border-gray-300 rounded-lg p-2 select2">
                    <option value="">Không có tổ chức</option>
                    @foreach($organizations as $organization)
                        <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                    @endforeach
                </select>
                <!-- @error('organization_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>
            <div>
                <label for="position_id" class="block text-gray-700 font-medium mb-2">Chức vụ</label>
                <select id="position_id" name="position_id" class="w-full border border-gray-300 rounded-lg p-2 select2">
                    <option value="">Không có chức vụ</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                    @endforeach
                </select>
                <!-- @error('position_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>
            <div>
                <label for="phone" class="block text-gray-700 font-medium mb-2">Số điện thoại</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="w-full border border-gray-300 rounded-lg p-2">
                <!-- @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>
            <div>
                <label for="address" class="block text-gray-700 font-medium mb-2">Địa chỉ</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" class="w-full border border-gray-300 rounded-lg p-2">
                <!-- @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror -->
            </div>
        </div>
        <div class="mt-6 flex" style="justify-content: space-between">
            <a onclick="window.history.back()" class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-gray-700 transition duration-300 mr-2">Quay lại</a>

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
