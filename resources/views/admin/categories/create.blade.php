@extends('layouts.master')

@section('content')

<h3>Thêm danh mục</h3>

<form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf

    <div class="mb-3">
        <label>Tên</label>
        <input type="text" name="name" class="form-control">
    </div>

    <div class="mb-3">
        <label>Mô tả</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <button class="btn btn-success">Lưu</button>
</form>

@endsection