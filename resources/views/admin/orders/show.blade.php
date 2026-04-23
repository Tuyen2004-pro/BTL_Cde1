@extends('layouts.master')

@section('content')

<h3>Chi tiết đơn hàng #{{ $order->id }}</h3>

<p><b>Khách:</b> {{ $order->user->name }}</p>
<p><b>Địa chỉ:</b> {{ $order->address }}</p>
<p><b>Trạng thái:</b> {{ $order->status }}</p>

<hr>

<h5>Sản phẩm:</h5>

<table class="table table-bordered">
    <tr>
        <th>Tên</th>
        <th>Số lượng</th>
        <th>Giá</th>
    </tr>

    @foreach($order->details as $d)
    <tr>
        <td>{{ $d->product->name }}</td>
        <td>{{ $d->quantity }}</td>
        <td>{{ number_format($d->price) }}</td>
    </tr>
    @endforeach

</table>

---

<form method="POST" action="{{ route('admin.orders.update',$order->id) }}">
    @csrf @method('PUT')

    <select name="status" class="form-control mb-2">
        <option value="confirmed">Xác nhận</option>
        <option value="shipping">Đang giao</option>
        <option value="completed">Hoàn thành</option>
        <option value="cancelled">Hủy</option>
    </select>

    <button class="btn btn-success">Cập nhật</button>
</form>

@endsection