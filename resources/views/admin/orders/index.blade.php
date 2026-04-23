@extends('layouts.master')

@section('content')

<h3>Đơn hàng</h3>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Khách</th>
        <th>Tiền</th>
        <th>Trạng thái</th>
        <th></th>
    </tr>

    @foreach($orders as $o)
    <tr>
        <td>{{ $o->id }}</td>
        <td>{{ $o->user->name }}</td>
        <td>{{ number_format($o->total_price) }}</td>
        <td>{{ $o->status }}</td>
        <td>
            <a href="{{ route('admin.orders.show',$o->id) }}" class="btn btn-info btn-sm">Xem</a>
        </td>
    </tr>
    @endforeach

</table>

@endsection