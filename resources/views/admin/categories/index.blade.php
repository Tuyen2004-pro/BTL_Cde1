@extends('layouts.master')

@section('content')

<h3>Danh mục</h3>

<a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Thêm</a>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Action</th>
    </tr>

    @foreach($categories as $c)
    <tr>
        <td>{{ $c->id }}</td>
        <td>{{ $c->name }}</td>
        <td>
            <a href="{{ route('admin.categories.edit',$c->id) }}" class="btn btn-warning btn-sm">Sửa</a>
            <form action="{{ route('admin.categories.destroy',$c->id) }}" method="POST" style="display:inline">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">Xóa</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

@endsection