@extends('layout.master')

@section('title', 'Dashboard Nhân viên')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

<div style="margin-bottom:28px">
    <div
        style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--espresso);margin-bottom:4px">
        Xin chào, {{ auth()->user()->name }} 👋
    </div>
    <div style="color:rgba(26,14,5,.45);font-size:.875rem">
        {{ now()->format('l, d/m/Y') }} · Ca làm việc hôm nay
    </div>
</div>

<!-- Stats -->
@php
$myOrders = \App\Models\Order::where('user_id', auth()->id())->get();
$pendingCount = $myOrders->where('status', 'pending')->count();
$paidCount = $myOrders->where('status', 'paid')->count();
$todayOrders = $myOrders->where('created_at', '>=', today())->count();
$todayRevenue = $myOrders->where('created_at', '>=', today())->where('status', 'paid')->sum('total_price');
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon espresso-bg"><i class="bi bi-receipt" style="color:var(--espresso)"></i></div>
            <div>
                <div class="stat-label">Đơn hôm nay</div>
                <div class="stat-value">{{ $todayOrders }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon gold-bg"><i class="bi bi-hourglass-split" style="color:var(--caramel)"></i></div>
            <div>
                <div class="stat-label">Chờ TT</div>
                <div class="stat-value">{{ $pendingCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon green-bg"><i class="bi bi-check-circle" style="color:#10b981"></i></div>
            <div>
                <div class="stat-label">Đã TT</div>
                <div class="stat-value">{{ $paidCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon blue-bg"><i class="bi bi-cash-stack" style="color:#3b82f6"></i></div>
            <div>
                <div class="stat-label">Doanh thu hôm nay</div>
                <div class="stat-value" style="font-size:1.1rem">{{ number_format($todayRevenue/1000, 0) }}K</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Quick actions -->
    <div class="col-lg-4">
        <div class="card-cafe" style="margin-bottom:16px">
            <div class="card-header-cafe">
                <h5><i class="bi bi-lightning-fill me-2" style="color:var(--caramel)"></i>Thao tác nhanh</h5>
            </div>
            <div class="card-body-cafe">
                <div style="display:flex;flex-direction:column;gap:10px">

                    <!-- FIXED -->
                    <a href="{{ route('staff.orders.create') }}" class="btn-espresso"
                        style="justify-content:center;padding:13px">
                        <i class="bi bi-plus-circle-fill"></i> Tạo đơn hàng mới
                    </a>

                    <!-- FIXED -->
                    <a href="{{ route('staff.orders.index') }}" class="btn-outline-cafe"
                        style="justify-content:center;padding:12px">
                        <i class="bi bi-list-ul"></i> Xem tất cả đơn hàng
                    </a>
                </div>
            </div>
        </div>

        <!-- Table status -->
        @php $tables = \App\Models\Table::all(); @endphp

        <div class="card-cafe">
            <div class="card-header-cafe">
                <h5>
                    <i class="bi bi-layout-three-columns me-2" style="color:var(--caramel)"></i>
                    Trạng thái bàn
                </h5>
            </div>

            <div class="card-body-cafe">
                <div class="table-grid">

                    @foreach($tables as $table)

                    @php
                    $status = $table->status ?? 'empty';
                    $isUsing = $status === 'using';
                    @endphp

                    <div class="table-box {{ $isUsing ? 'using' : 'empty' }}">

                        <div class="table-icon">🪑</div>

                        <div class="table-name">
                            {{ $table->name }}
                        </div>

                        <div class="table-status">
                            {{ $isUsing ? 'Đang dùng' : 'Trống' }}
                        </div>

                    </div>

                    @endforeach

                </div>
            </div>
        </div>
    </div>

    <!-- Recent orders -->
    <div class="col-lg-8">
        <div class="card-cafe">
            <div class="card-header-cafe">
                <h5><i class="bi bi-clock-history me-2" style="color:var(--caramel)"></i>Đơn hàng gần đây của tôi</h5>

                <!-- FIXED -->
                <a href="{{ route('staff.orders.index') }}" class="btn-outline-cafe"
                    style="padding:6px 14px;font-size:.8rem">Xem tất cả</a>
            </div>

            <div class="card-body-cafe" style="padding:0">
                @php
                $recentOrders = \App\Models\Order::where('user_id', auth()->id())
                ->with('items.product','table')
                ->latest()
                ->take(8)
                ->get();
                @endphp

                @if($recentOrders->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-receipt"></i>
                    <p>Chưa có đơn nào hôm nay</p>
                </div>
                @else
                <table class="table-cafe">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bàn</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thời gian</th>
                            <th style="text-align:center">Xem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $o)
                        <tr>
                            <td><span style="font-weight:700;color:var(--caramel)">#{{ $o->id }}</span></td>
                            <td>{{ $o->table?->name ?? '—' }}</td>
                            <td><strong>{{ number_format($o->total_price, 0, ',', '.') }}₫</strong></td>
                            <td>
                                @if($o->status === 'pending')
                                <span class="badge-cafe badge-pending">⏳ Chờ</span>
                                @else
                                <span class="badge-cafe badge-paid">✅ TT</span>
                                @endif
                            </td>
                            <td style="font-size:.78rem;color:rgba(26,14,5,.4)">
                                {{ $o->created_at->diffForHumans() }}
                            </td>
                            <td style="text-align:center">

                                <!-- FIXED -->
                                <a href="{{ route('staff.orders.show', $o->id) }}" class="btn-outline-cafe"
                                    style="padding:5px 10px;font-size:.78rem">
                                    <i class="bi bi-eye"></i>
                                </a>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

            </div>
        </div>
    </div>
</div>
<style>
    .table-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .table-box {
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        font-size: 13px;
        border: 1px solid;
        transition: 0.2s;
    }

    .table-box:hover {
        transform: scale(1.05);
    }

    .table-box.empty {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }

    .table-box.using {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .table-icon {
        font-size: 18px;
    }

    .table-name {
        font-weight: 600;
    }

    .table-status {
        font-size: 11px;
    }
</style>
@endsection