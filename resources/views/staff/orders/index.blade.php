@extends('layout.master')

@section('title', 'Đơn hàng')

@section('breadcrumb')
<li class="breadcrumb-item active">Đơn hàng</li>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">

    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <span class="badge-cafe badge-pending" style="font-size:.8rem;padding:7px 14px">
            ⏳ Chờ: {{ $orders->where('status','pending')->count() }}
        </span>

        <span class="badge-cafe badge-paid" style="font-size:.8rem;padding:7px 14px">
            ✅ Đã TT: {{ $orders->where('status','paid')->count() }}
        </span>

        <span class="badge-cafe" style="background:var(--latte);color:var(--brown);font-size:.8rem;padding:7px 14px">
            📋 Tổng: {{ $orders->count() }}
        </span>
    </div>

    <a href="{{ route('staff.orders.create') }}" class="btn-espresso">
        <i class="bi bi-plus-lg"></i> Tạo đơn mới
    </a>
</div>


<div class="card-cafe">

    <div class="card-header-cafe">
        <h5>
            <i class="bi bi-receipt me-2" style="color:var(--caramel)"></i>
            Danh sách đơn hàng
        </h5>
    </div>

    <div class="card-body-cafe" style="padding:0">

        @if($orders->isEmpty())

        <div class="empty-state">
            <i class="bi bi-receipt"></i>
            <p>
                Chưa có đơn hàng nào.
                <a href="{{ route('staff.orders.create') }}" style="color:var(--caramel)">
                    Tạo đơn đầu tiên
                </a>
            </p>
        </div>

        @else

        <div style="overflow-x:auto">

            <table class="table-cafe">

                <thead>
                    <tr>
                        <th style="width:80px">#Đơn</th>
                        <th>Bàn</th>
                        <th>Nhân viên</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thời gian</th>
                        <th style="width:200px;text-align:center">Thao tác</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($orders as $order)

                    <tr>
                        <td>
                            <span style="font-family:'Playfair Display',serif;font-weight:700;color:var(--caramel)">
                                #{{ $order->id }}
                            </span>
                        </td>

                        <td>
                            <div style="display:flex;align-items:center;gap:6px">
                                <span>🪑</span>
                                <span style="font-weight:500">
                                    {{ $order->table?->name ?? 'Không có' }}
                                </span>
                            </div>
                        </td>

                        <td style="font-size:.84rem;color:rgba(26,14,5,.6)">
                            {{ $order->user?->name ?? '—' }}
                        </td>

                        <td>
                            <div style="font-size:.8rem;color:rgba(26,14,5,.55);max-width:180px">

                                @foreach($order->items->take(2) as $item)
                                <div>
                                    • {{ $item->product?->name }} × {{ $item->quantity }}
                                </div>
                                @endforeach

                                @if($order->items->count() > 2)
                                <div style="color:var(--caramel)">
                                    +{{ $order->items->count() - 2 }} món khác
                                </div>
                                @endif

                            </div>
                        </td>

                        <td>
                            <span style="font-weight:700;color:var(--espresso);font-size:.95rem">
                                {{ number_format($order->total_price, 0, ',', '.') }}₫
                            </span>
                        </td>

                        <td>
                            @if($order->status === 'pending')
                            <span class="badge-cafe badge-pending">⏳ Chờ TT</span>
                            @elseif($order->status === 'paid')
                            <span class="badge-cafe badge-paid">✅ Đã TT</span>
                            @else
                            <span class="badge-cafe badge-cancelled">❌ Huỷ</span>
                            @endif
                        </td>

                        <td style="font-size:.78rem;color:rgba(26,14,5,.4)">
                            {{ $order->created_at->format('H:i') }}<br>
                            {{ $order->created_at->format('d/m/Y') }}
                        </td>

                        <td style="text-align:center">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap">

                                {{-- XEM --}}
                                <a href="{{ route('staff.orders.show', $order->id) }}" class="btn-outline-cafe"
                                    style="padding:5px 10px;font-size:.78rem">
                                    <i class="bi bi-eye"></i> Xem
                                </a>



                            </div>
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        @endif

    </div>
</div>

@endsection