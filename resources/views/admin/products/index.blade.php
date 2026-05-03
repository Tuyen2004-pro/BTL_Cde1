@extends('layouts.master')

@section('content')

<h3>Sản phẩm</h3>

<a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Thêm</a>

<table class="table table-bordered">
    <tr>
        <th>Ảnh</th>
        <th>Tên</th>
        <th>Size & Giá</th>
        <th>Danh mục</th>
        <th>Thao tác</th>
    </tr>

    @foreach($products as $p)
    <tr>
        <td>
            <img src="{{ asset('storage/' . $p->image) }}" width="80">
        </td>

        <td>{{ $p->name }}</td>

        <td>
            @foreach($p->sizes as $s)
            <div>
                {{ $s->size }} - {{ number_format($s->price) }} đ
            </div>
            @endforeach
        </td>

        <td>{{ $p->category->name }}</td>

        <td>
            <a href="{{ route('admin.products.edit',$p->id) }}" class="btn btn-warning btn-sm">Sửa</a>

            <form action="{{ route('admin.products.destroy',$p->id) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Xóa</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

@endsection