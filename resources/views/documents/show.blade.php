@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Document Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $document->document_name }}</h5>
            <p class="card-text"><strong>Document Code:</strong> {{ $document->document_code }}</p>
            <p class="card-text"><strong>Issuing Department:</strong> {{ $document->issuing_department }}</p>
            <p class="card-text"><strong>Start Date:</strong> {{ $document->start_date }}</p>
            <p class="card-text"><strong>End Date:</strong> {{ $document->end_date }}</p>
            <a href="{{ route('documents.index') }}" class="btn btn-primary">Back to List</a>
        </div>
    </div>
</div>
@endsection
