@extends('layouts.master')

@section('content')

<h3>Sửa sản phẩm</h3>

<form method="POST" enctype="multipart/form-data" action="{{ route('admin.products.update',$product->id) }}">
    @csrf @method('PUT')

    <input name="name" value="{{ $product->name }}" class="form-control mb-2">

    <input name="price" value="{{ $product->price }}" class="form-control mb-2">

    <select name="category_id" class="form-control mb-2">
        @foreach($categories as $c)
        <option value="{{ $c->id }}" {{ $c->id == $product->category_id ? 'selected':'' }}>
            {{ $c->name }}
        </option>
        @endforeach
    </select>

    <img src="/storage/{{ $product->image }}" width="80" class="mb-2">

    <input type="file" name="image" class="form-control mb-2">

    <textarea name="description" class="form-control mb-2">{{ $product->description }}</textarea>

    <button class="btn btn-primary">Cập nhật</button>

</form>

@endsection