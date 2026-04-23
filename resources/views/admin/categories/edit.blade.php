@extends('layouts.master')

@section('content')

<h3>Sửa danh mục</h3>

<form method="POST" action="{{ route('admin.categories.update',$category->id) }}">
    @csrf @method('PUT')

    <input type="text" name="name" value="{{ $category->name }}" class="form-control mb-3">
    <textarea name="description" class="form-control mb-3">{{ $category->description }}</textarea>

    <button class="btn btn-primary">Cập nhật</button>
</form>

@endsection