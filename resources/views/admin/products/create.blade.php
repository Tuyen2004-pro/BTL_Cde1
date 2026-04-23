@extends('layouts.master')

@section('content')

<h3>Thêm sản phẩm</h3>

<form method="POST" enctype="multipart/form-data" action="{{ route('admin.products.store') }}">
    @csrf

    <input name="name" class="form-control mb-2" placeholder="Tên">

    <input name="price" class="form-control mb-2" placeholder="Giá">

    <select name="category_id" class="form-control mb-2">
        @foreach($categories as $c)
        <option value="{{ $c->id }}">{{ $c->name }}</option>
        @endforeach
    </select>

    <input type="file" name="image" class="form-control mb-2">

    <textarea name="description" class="form-control mb-2"></textarea>

    <button class="btn btn-success">Lưu</button>

</form>

@endsection