@extends('layouts.app')

@section('content')
   
<div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg " style="margin-top: 10px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {!! Breadcrumbs::render('UO', $organization) !!}
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
        <form action="{{ route('organizations.update', $organization->id) }}" method="POST" enctype="multipart/form-data" class="p-6" id="document-form">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cột trái -->
                <div class="mb-4">
                    <label for="code" class="block text-gray-700 text-sm font-medium mb-2">Mã cơ quan, tổ chức(Tối đa 5 ký tự) <span class="text-red-500">*</span></label>
                    <input type="text" id="code" name="code" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('code', $organization->code) }}" required
                    oninvalid="this.setCustomValidity('Vui lòng nhập mã cơ quan, tổ chức.')" 
                    oninput="setCustomValidity('')"
                    maxlength="5">
                    @error('code')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Tên cơ quan, tổ chức <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('name', $organization->name) }}" required
                    oninvalid="this.setCustomValidity('Vui lòng nhập tên cơ quan, tổ chức.')" 
                    oninput="setCustomValidity('')">

                </div>
             {{--    <div class="mb-4">
                    <label for="type" class="block text-gray-700 text-sm font-medium mb-2">Loại <span class="text-red-500">*</span></label>
                        <select class="form-input w-full border border-gray-300 rounded-lg p-2" id="type" name="type" required
                        oninvalid="this.setCustomValidity('Vui lòng chọn loại.')" 
                        oninput="setCustomValidity('')">
                            <option value="tỉnh" {{ $organization->type == 'tỉnh' ? 'selected' : '' }}>Tỉnh</option>
                            <option value="bộ" {{ $organization->type == 'bộ' ? 'selected' : '' }}>Bộ</option>
                        </select>
                </div> --}}
                <div class="mb-4">
                    <label for="organization_type_id" class="block text-gray-700 text-sm font-medium mb-2">Loại cơ quan, tổ chức <span class="text-red-500">*</span></label>
                        <select name="organization_type_id" id="organization_type_id"
                            class="form-input w-full border border-gray-300 rounded-lg p-2 select2" require
                            oninvalid="this.setCustomValidity('Vui lòng chọn loại cơ quan, tổ chức.')" 
                            oninput="setCustomValidity('')">
                            <option value="" disabled {{ old('organization_type_id') ? '' : 'selected' }}>Chọn loại cơ quan, tổ chức</option>

                            @foreach ($organizationType as $item)
                                <option value="{{ $item->id }}"
                                    {{ $item->id == $organization->organization_type_id ? 'selected' : '' }}>
                                    {{ $item->type_name }}
                                </option>
                            @endforeach
                        </select>
                </div>
                <div class="mb-4" style="display:none">
                    <label for="parent_id" class="block text-gray-700 text-sm font-medium mb-2">Cơ quan, tổ chức cha:</label>
                        <select name="parent_id" id="parent_id"
                            class="form-input w-full border border-gray-300 rounded-lg p-2 select2" require
                            oninvalid="this.setCustomValidity('Vui lòng chọn cơ quan, tổ chức.')" 
                            oninput="setCustomValidity('')">
                            <option value="" disabled {{ old('parent_id') ? '' : 'selected' }}>Chọn cơ quan, tổ chức</option>
                            @foreach ($organizations as $item)
                                <option value="{{ $item->id }}"
                                    {{ $item->id == $organization->parent_id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="text" id="email" name="email" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('email', $organization->email) }}" required
                    oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ email.')" 
                    oninput="setCustomValidity('')">

                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 text-sm font-medium mb-2">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="text" id="phone" name="phone" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('phone', $organization->phone) }}" required
                    oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại.')" 
                    oninput="setCustomValidity('')">

                </div>
                <div class="mb-4">
                    <label for="address" class="block text-gray-700 text-sm font-medium mb-2">Địa chỉ </label>
                    <input type="text" id="address" name="address" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('address', $organization->address) }}">

                </div>
                <div class="mb-4">
                    <label for="website" class="block text-gray-700 text-sm font-medium mb-2">Website </label>
                    <input type="text" id="website" name="website" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('website', $organization->website) }}">

                </div>
                
            </div>
            <!-- Nút lưu -->
            <div class="mt-4 flex" style="justify-content: space-between">
                <a onclick="window.history.back()" class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-gray-700 transition duration-300 mr-2">Quay lại</a>

                <button type="submit" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Lưu</button>
            </div>
        </form>
    </div>
@endsection
