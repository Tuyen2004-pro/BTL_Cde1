@extends('layouts.master')

@section('content')

<h3>Sửa sản phẩm</h3>

<form method="POST" enctype="multipart/form-data" action="{{ route('admin.products.update',$product->id) }}">
    @csrf @method('PUT')

    {{-- Tên --}}
    <input name="name" value="{{ $product->name }}" class="form-control mb-2">

    {{-- Giá base (có thể bỏ nếu không dùng) --}}
    <input name="price" value="{{ $product->price }}" class="form-control mb-2">

    {{-- Danh mục --}}
    <select name="category_id" class="form-control mb-2">
        @foreach($categories as $c)
        <option value="{{ $c->id }}" {{ $c->id == $product->category_id ? 'selected':'' }}>
            {{ $c->name }}
        </option>
        @endforeach
    </select>

    {{-- Ảnh --}}
    <img src="/storage/{{ $product->image }}" width="80" class="mb-2">

    <input type="file" name="image" class="form-control mb-2">

    {{-- Mô tả --}}
    <textarea name="description" class="form-control mb-2">{{ $product->description }}</textarea>

    {{-- ================= SIZE ================= --}}
    <h5 class="mt-3">Size & Giá</h5>

    <div class="row">
        @php
        $sizes = $product->sizes->keyBy('size');
        @endphp

        <div class="col">
            <label>Size S</label>
            <input type="number" name="sizes[S]" class="form-control" value="{{ $sizes['S']->price ?? '' }}">
        </div>

        <div class="col">
            <label>Size M</label>
            <input type="number" name="sizes[M]" class="form-control" value="{{ $sizes['M']->price ?? '' }}">
        </div>

        <div class="col">
            <label>Size L</label>
            <input type="number" name="sizes[L]" class="form-control" value="{{ $sizes['L']->price ?? '' }}">
        </div>
    </div>

    <button class="btn btn-primary mt-3">Cập nhật</button>

</form>

@endsection