@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    @if ($errors->any())
        <div class="error-message bg-red-500 text-white p-4 rounded-lg mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="error-message bg-red-500 text-white p-4 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="success-message bg-green-500 text-white p-4 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('organizations.store') }}" method="POST"  class="bg-white p-6 rounded-lg shadow-lg">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="code" class="block text-gray-700">Mã cơ quan, tổ chức <span class="text-red-500">*</span></label>
                <input type="text" name="code" id="code" class="w-full border rounded-lg px-3 py-2 mt-1" value="{{ old('code') }}" >
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Tên cơ quan, tổ chức <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" class="w-full border rounded-lg px-3 py-2 mt-1"value="{{ old('name') }}">
            </div>
            <div class="mb-4">
                <label for="organization_type_id" class="block text-gray-700">Loại cơ quan, tổ chức <span class="text-red-500">*</span></label>
                <select name="organization_type_id" id="organization_type_id" class="w-full border rounded-lg px-3 py-2 mt-1">
                    <option value="" {{ old('organization_type_id') ? '' : 'selected' }}>Chọn loại cơ quan</option>
                    @foreach ($oranizationType as $category)
                        <option value="{{ $category->id }}" {{ old('organization_type_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->type_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="parent_id" class="block text-gray-700">Chọn Cơ quan, tổ chức cấp trên <span class="text-red-500">*</span></label>
                <select name="parent_id" id="parent_id" class="w-full border rounded-lg px-3 py-2 mt-1">
                    <option value="" {{ old('parent_id') ? '' : 'selected' }}>Chọn cơ quan tổ chức cấp trên</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="w-full border rounded-lg px-3 py-2 mt-1" value="{{ old('email') }}">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700">Số điện thoại</label>
                <input type="text" name="phone" id="phone" class="w-full border rounded-lg px-3 py-2 mt-1" value="{{ old('phone') }}">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-700">Địa chỉ</label>
                <input type="text" name="address" id="address" class="w-full border rounded-lg px-3 py-2 mt-1" value="{{ old('address') }}">
            </div>
            <div class="mb-4">
                <label for="website" class="block text-gray-700">Địa chỉ website</label>
                <input type="text" name="website" id="website" class="w-full border rounded-lg px-3 py-2 mt-1" value="{{ old('website') }}">
            </div>
        </div>

        <div class="flex justify-end mt-4" style="justify-content: space-between">
            <a href="{{ route('organizations.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-gray-700 transition duration-300 mr-2">Quay lại</a>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Lưu</button>
        </div>
    </form>
</div>
<script>
     document.getElementById('organization_type_id').addEventListener('change', function () {
                var organizationTypeId = this.value;
                
                // Gửi yêu cầu AJAX đến server để lấy danh sách organizations
                fetch(`/get-organizations/${organizationTypeId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Làm rỗng danh sách `parent_id`
                        var parentSelect = document.getElementById('parent_id');
                        parentSelect.innerHTML = '<option value="" disabled selected>Chọn cơ quan tổ chức cấp trên</option>';

                        // Thêm các tùy chọn mới
                        data.forEach(function (organization) {
                            var option = document.createElement('option');
                            option.value = organization.id;
                            option.text = organization.name;
                            parentSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            });
</script>
@endsection
