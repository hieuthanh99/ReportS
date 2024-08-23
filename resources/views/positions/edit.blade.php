@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6">Edit Position</h1>

    <form action="{{ route('positions.update', $position->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="code" class="block text-gray-700 text-sm font-medium mb-2">Code <span class="text-red-500">*</span></label>
            <input type="text" id="code" name="code" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('code', $position->code) }}" required>
            @error('code')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Name <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('name', $position->name) }}" required>
            @error('name')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-medium mb-2">Description</label>
            <textarea id="description" name="description" class="form-textarea w-full border border-gray-300 rounded-lg p-2">{{ old('description', $position->description) }}</textarea>
            @error('description')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Update Position</button>
        </div>
    </form>
</div>
@endsection
