@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Chi tiết văn bản - {{ $document->document_name }}</h1>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <h5 class="text-2xl font-semibold mb-2">{{ $document->document_name }}</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <p class="text-gray-700 font-medium">Mã văn bản:</p>
                    <p class="text-gray-900">{{ $document->document_code }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-700 font-medium">Đơn vị phát hành:</p>
                    <p class="text-gray-900">{{ $document->issuingDepartment->name }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-700 font-medium">Ngày phát hành:</p>
                    <p class="text-gray-900">{{ $document->issuingDepartment ? $document->issuingDepartment->name : 'N/A' }}</p>
                </div>
            </div>
            <a href="{{ route('documents.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Back to List</a>
        </div>
    </div>
</div>
@endsection
