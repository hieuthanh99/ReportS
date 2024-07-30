@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sửa tổ chức</h1>
    <form action="{{ route('organizations.update', $organization) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ $organization->code }}" required>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $organization->name }}" required>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="tỉnh" {{ $organization->type == 'tỉnh' ? 'selected' : '' }}>Tỉnh</option>
                <option value="bộ" {{ $organization->type == 'bộ' ? 'selected' : '' }}>Bộ</option>
            </select>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $organization->email }}">
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $organization->phone }}">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
    </form>
</div>
@endsection
