@extends('layouts.app')

@section('content')
   
    <div class="container mx-auto px-4 py-6">
        
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
        <form action="{{ route('indicator_groups.update', $indicatorGroup) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg" onsubmit="confirmBeforeSave({ event })">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                {!! Breadcrumbs::render('ULCT', $indicatorGroup) !!}
            </ol>
        </nav>
        @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cột trái -->
                <div class="mb-4">
                    <label for="code" class="block text-gray-700 text-sm font-medium mb-2">Mã loại chỉ tiêu <span class="text-red-500">*</span></label>
                    <input type="text" id="code" name="code" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('code', $indicatorGroup->code) }}" required>
                    @error('code')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4"></div>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Tên loại chỉ tiêu <span class="text-red-500">*</span></label>
                    <textarea id="name" name="name" class="form-input w-full border border-gray-300 rounded-lg p-2 resize-none" rows="4" required>{{ old('name', $indicatorGroup->name) }}</textarea>
                    @error('name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-medium mb-2">Chi tiết loại chỉ tiêu<span class="text-red-500">*</span></label>
                    <textarea id="description" name="description" class="form-input w-full border border-gray-300 rounded-lg p-2 resize-none" rows="4" required>{{ old('description', $indicatorGroup->description) }}</textarea>
                    @error('description')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
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
