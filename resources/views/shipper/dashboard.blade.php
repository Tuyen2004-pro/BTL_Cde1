@extends('layout.master')

@section('title', 'Dashboard Shipper')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

<div style="margin-bottom:28px">
    <div
        style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--espresso);margin-bottom:4px">
        Xin chào, {{ auth()->user()->name }} 🛵
    </div>
    <div style="color:rgba(26,14,5,.45);font-size:.875rem">
        {{ now()->format('l, d/m/Y') }} · Chúc bạn giao hàng thuận lợi hôm nay!
    </div>
</div>

@php
$deliveries = \App\Models\Delivery::where('shipper_id', auth()->id())
->with('order.items.product')
->get();

$pending = $deliveries->where('status', 'pending');
$delivering = $deliveries->where('status', 'delivering');
$done = $deliveries->where('status', 'delivered');
@endphp

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon gold-bg"><i class="bi bi-hourglass-split" style="color:var(--caramel)"></i></div>
            <div>
                <div class="stat-label">Chờ lấy</div>
                <div class="stat-value">{{ $pending->count() }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon blue-bg"><i class="bi bi-truck" style="color:#3b82f6"></i></div>
            <div>
                <div class="stat-label">Đang giao</div>
                <div class="stat-value">{{ $delivering->count() }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon green-bg"><i class="bi bi-check-circle-fill" style="color:#10b981"></i></div>
            <div>
                <div class="stat-label">Đã giao</div>
                <div class="stat-value">{{ $done->count() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Active deliveries -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card-cafe">
            <div class="card-header-cafe">
                <h5><i class="bi bi-truck me-2" style="color:var(--caramel)"></i>Đơn cần giao</h5>
                <span class="badge-cafe badge-gold" style="background:var(--latte);color:var(--brown)">
                    {{ $pending->count() + $delivering->count() }} đơn
                </span>
            </div>

            <div class="card-body-cafe" style="padding:0">
                @if($pending->isEmpty() && $delivering->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-emoji-smile"></i>
                    <p>Bạn đã hoàn thành tất cả đơn hàng! 🎉</p>
                </div>
                @else
                <div style="padding:16px;display:flex;flex-direction:column;gap:12px">

                    @foreach($pending->merge($delivering) as $delivery)

                    @php
                    $isDelivering = $delivery->status === 'delivering';
                    @endphp

                    <!-- <div style="
                            border:1.5px solid {{ $isDelivering ? '#93c5fd' : 'rgba(26,14,5,.1)' }};
                            border-radius:12px;
                            padding:16px;
                            background:{{ $isDelivering ? '#eff6ff' : '#ffffff' }};
                        "> -->

                    <!-- Header -->
                    <div
                        style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px">
                        <div>
                            <span
                                style="font-family:'Playfair Display',serif;font-weight:700;color:var(--caramel);font-size:1rem">
                                #{{ $delivery->order->id }}
                            </span>
                            <span style="font-size:.78rem;color:rgba(26,14,5,.4);margin-left:8px">
                                {{ $delivery->order->created_at->diffForHumans() }}
                            </span>
                        </div>

                        @if($isDelivering)
                        <span class="badge-cafe" style="background:#dbeafe;color:#1e40af">
                            🚚 Đang giao
                        </span>
                        @else
                        <span class="badge-cafe badge-pending">
                            📦 Chờ lấy
                        </span>
                        @endif
                    </div>

                    <!-- Address -->
                    <div style="font-size:.84rem;margin-bottom:8px">
                        <i class="bi bi-geo-alt me-1" style="color:var(--caramel)"></i>
                        <strong>{{ $delivery->address }}</strong>
                    </div>

                    <!-- Items -->
                    <div style="font-size:.8rem;color:rgba(26,14,5,.55);margin-bottom:12px">
                        @foreach($delivery->order->items->take(3) as $item)
                        <span
                            style="background:var(--latte);padding:2px 8px;border-radius:20px;margin-right:4px;display:inline-block;margin-bottom:4px">
                            {{ optional($item->product)->name }} ×{{ $item->quantity }}
                        </span>
                        @endforeach
                    </div>

                    <!-- Footer -->
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-weight:700;color:var(--espresso)">
                            {{ number_format($delivery->order->total_price, 0, ',', '.') }}₫
                        </span>

                        <div style="display:flex;gap:8px">
                            @if($delivery->status === 'pending')
                            <form action="/deliveries/{{ $delivery->id }}/pick" method="POST">
                                @csrf
                                <button type="submit" class="btn-outline-cafe"
                                    style="padding:6px 14px;font-size:.8rem">
                                    <i class="bi bi-box-arrow-up"></i> Lấy hàng
                                </button>
                            </form>
                            @endif

                            @if($delivery->status === 'delivering')
                            <form action="/deliveries/{{ $delivery->id }}/done" method="POST">
                                @csrf
                                <button type="submit" class="btn-gold" style="padding:6px 14px;font-size:.8rem">
                                    <i class="bi bi-check-circle-fill"></i> Đã giao
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                </div>
                @endforeach

            </div>
            @endif
        </div>
    </div>
</div>

<!-- History -->
<div class="col-lg-5">
    <div class="card-cafe">
        <div class="card-header-cafe">
            <h5><i class="bi bi-clock-history me-2" style="color:var(--caramel)"></i>Lịch sử giao</h5>
        </div>

        <div class="card-body-cafe" style="padding:0">
            @if($done->isEmpty())
            <div class="empty-state">
                <i class="bi bi-check2-all"></i>
                <p>Chưa có đơn nào hoàn thành</p>
            </div>
            @else
            <table class="table-cafe">
                <thead>
                    <tr>
                        <th>#Đơn</th>
                        <th>Địa chỉ</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($done->take(10) as $delivery)
                    <tr>
                        <td><span style="font-weight:700;color:var(--caramel)">#{{ $delivery->order->id }}</span>
                        </td>
                        <td
                            style="font-size:.8rem;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            {{ $delivery->address }}
                        </td>
                        <td style="font-weight:600;font-size:.85rem">
                            {{ number_format($delivery->order->total_price / 1000, 0) }}K
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    <!-- Today summary -->
    @php
    $todayDone = $done->filter(fn($d) => $d->updated_at >= today());
    $todayEarning = $todayDone->sum(fn($d) => $d->order->total_price);
    @endphp

    <div class="card-cafe" style="margin-top:16px">
        <div class="card-header-cafe">
            <h5><i class="bi bi-bar-chart me-2" style="color:var(--caramel)"></i>Hôm nay của bạn</h5>
        </div>

        <div class="card-body-cafe">
            <div style="text-align:center;padding:10px 0">
                <div
                    style="font-size:.8rem;color:rgba(26,14,5,.45);letter-spacing:1px;text-transform:uppercase;margin-bottom:8px">
                    Đã giao thành công
                </div>

                <div
                    style="font-family:'Playfair Display',serif;font-size:3rem;font-weight:700;color:var(--espresso)">
                    {{ $todayDone->count() }}
                </div>

                <div style="font-size:.85rem;color:var(--caramel);font-weight:600;margin-top:8px">
                    Tổng: {{ number_format($todayEarning, 0, ',', '.') }}₫
                </div>
            </div>
        </div>
    </div>

</div>
</div>

@endsection