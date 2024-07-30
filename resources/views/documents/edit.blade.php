@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Document</h1>
    <form action="{{ route('documents.update', $document) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="document_code">Document Code</label>
            <input type="text" class="form-control" id="document_code" name="document_code" value="{{ $document->document_code }}" required>
        </div>
        <div class="form-group">
            <label for="document_name">Document Name</label>
            <input type="text" class="form-control" id="document_name" name="document_name" value="{{ $document->document_name }}" required>
        </div>
        <div class="form-group">
            <label for="issuing_department">Issuing Department</label>
            <input type="text" class="form-control" id="issuing_department" name="issuing_department" value="{{ $document->issuing_department }}" required>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $document->start_date }}" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $document->end_date }}" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
