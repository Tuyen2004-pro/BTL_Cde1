@extends('layouts.master')

@section('content')

<div class="container">

    <h3 class="mb-3">🧾 Chi tiết đơn hàng #{{ $order->id }}</h3>

    {{-- THÔNG TIN ĐƠN --}}
    <div class="card mb-3">
        <div class="card-body">

            <p><b>👤 Nhân viên:</b> {{ $order->user->name ?? 'N/A' }}</p>

            <p><b>🪑 Bàn:</b> {{ $order->table->name ?? 'N/A' }}</p>

            <p><b>⏰ Thời gian:</b> {{ $order->created_at }}</p>

            <p>
                <b>📌 Trạng thái:</b>
                @if($order->status == 'pending')
                <span class="badge bg-warning text-dark">Đang mở</span>
                @elseif($order->status == 'paid')
                <span class="badge bg-success">Đã thanh toán</span>
                @elseif($order->status == 'cancelled')
                <span class="badge bg-danger">Đã huỷ</span>
                @endif
            </p>

            {{-- HIỂN THỊ LÝ DO HUỶ --}}
            @if($order->status == 'cancelled' && $order->cancelled)
            <div style="color:red">
                Lý do huỷ: {{ $order->cancelled->reason }}
            </div>
            @endif

        </div>
    </div>

    {{-- DANH SÁCH MÓN --}}
    <h5>🍵 Danh sách món</h5>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên món</th>
                <th>Size</th>
                <th>Đường</th>
                <th>Đá</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>

        <tbody>
            @php $total = 0; @endphp

            @foreach($order->items as $item)

            @php
            $subtotal = $item->quantity * $item->price;
            $total += $subtotal;
            @endphp

            <tr>
                <td>{{ $item->product->name }}</td>

                <td>{{ $item->size ?? '-' }}</td>

                <td>
                    {{ $item->sugar ? $item->sugar  : '-' }}
                </td>

                <td>
                    {{ $item->ice ? $item->ice  : '-' }}
                </td>

                <td>{{ $item->quantity }}</td>

                <td>{{ number_format($item->price) }} đ</td>

                <td>{{ number_format($subtotal) }} đ</td>
            </tr>

            @endforeach

        </tbody>

        <tfoot>
            <tr>
                <th colspan="6" class="text-end">Tổng tiền</th>
                <th>{{ number_format($total) }} đ</th>
            </tr>
        </tfoot>

    </table>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        ← Quay lại
    </a>

</div>

@endsection