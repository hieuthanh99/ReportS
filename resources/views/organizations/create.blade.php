@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Thêm mới tổ chức</h1>
    <form action="{{ route('organizations.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" class="form-control" id="code" name="code" required>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="tỉnh">Tỉnh</option>
                <option value="bộ">Bộ</option>
            </select>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>
        <input type="hidden" name="parent_id" value="{{ $parentId }}">
        <button type="submit" class="btn btn-primary mt-3">Lưu</button>
    </form>
</div>
@endsection
