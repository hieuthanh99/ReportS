@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
@endphp
<script>
    
</script>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Danh sách văn bản</h1>
        <a href="{{ route('documents.create') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300 mb-4">Tạo văn bản</a>
    </div>
      @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg relative">
            {{ session('success') }}
            <button id="close-message" class="absolute top-2 right-2 text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
            <thead class="bg-gray-100 border-b border-gray-300">
                <tr>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">#</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Mã văn bản</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Tên văn bản</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Đơn vị phát hành</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Ngày phát hành</th>
                    <th class="py-3 px-6 text-left text-gray-700 font-medium">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                <tr class="border-b border-gray-200">
                    <td class="py-3 px-6">{{ $document->id }}</td>
                    <td class="py-3 px-6">{{ $document->document_code }}</td>
                    <td class="py-3 px-6">{{ $document->document_name }}</td>
                    <td class="py-3 px-6">  {{ $document->issuingDepartment ? $document->issuingDepartment->name : 'N/A' }}</td>
                    <td class="py-3 px-6"> {{ $document->release_date_formatted }}</td>
                    <td class="py-3 px-6">
                        
                        <a href="{{ route('documents.show', $document) }}" class="text-blue-500 hover:underline">View</a>
                        <a href="{{ route('documents.edit', $document) }}" class="text-yellow-500 hover:underline ml-2">Edit</a>
                        <form action="{{ route('documents.destroy', $document) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500 hover:underline ml-2" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
