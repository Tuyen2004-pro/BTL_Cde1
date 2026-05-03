@extends('layout.master')

@section('title', 'Chi tiết đơn #' . $order->id)

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('staff.orders.index') }}" style="color:var(--caramel);text-decoration:none">
        Đơn hàng
    </a>
</li>
<li class="breadcrumb-item active">Đơn #{{ $order->id }}</li>
@endsection

@section('content')
<div class="row g-4">

    <!-- Order Info -->
    <div class="col-lg-8">
        <div class="card-cafe" style="margin-bottom:20px">

            <div class="card-header-cafe">
                <div>
                    <h5 style="margin-bottom:4px">
                        <i class="bi bi-receipt me-2" style="color:var(--caramel)"></i>
                        Đơn hàng #{{ $order->id }}
                    </h5>

                    <div style="font-size:.78rem;color:rgba(26,14,5,.4)">
                        <i class="bi bi-clock me-1"></i>
                        {{ $order->created_at->format('H:i — d/m/Y') }}
                    </div>
                </div>

                <div>
                    @if($order->status === 'pending')
                    <span class="badge-cafe badge-pending" style="font-size:.85rem;padding:8px 16px">
                        ⏳ Chờ thanh toán
                    </span>
                    @elseif($order->status === 'paid')
                    <span class="badge-cafe badge-paid" style="font-size:.85rem;padding:8px 16px">
                        ✅ Đã thanh toán
                    </span>
                    @else
                    <span class="badge-cafe badge-cancelled" style="font-size:.85rem;padding:8px 16px">
                        ❌ Đã huỷ
                    </span>
                    @endif
                </div>
            </div>

            <!-- 👉 HIỂN THỊ LÝ DO HỦY -->
            @if($order->status === 'cancelled')
            <div style="margin:12px;padding:12px;background:#ffecec;border-radius:10px;font-size:.85rem">
                <strong>❗ Lý do hủy:</strong><br>
                {{ $order->cancelled->reason }}
            </div>
            @endif

            <div class="card-body-cafe" style="padding:0">
                <table class="table-cafe">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th style="text-align:center">Số lượng</th>
                            <th style="text-align:right">Đơn giá</th>
                            <th style="text-align:right">Thành tiền</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px">

                                    @if($item->product?->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                        style="width:40px;height:40px;border-radius:8px;object-fit:cover">
                                    @else
                                    <div
                                        style="width:40px;height:40px;border-radius:8px;background:var(--latte);display:flex;align-items:center;justify-content:center;font-size:1.1rem">
                                        ☕
                                    </div>
                                    @endif

                                    <div>
                                        <div style="font-weight:600">
                                            {{ $item->product?->name ?? 'Sản phẩm đã xoá' }}
                                        </div>

                                        @if($item->product?->category)
                                        <div style="font-size:.72rem;color:rgba(26,14,5,.4)">
                                            {{ $item->product->category->name }}
                                        </div>
                                        @endif

                                        <div style="font-size:.75rem;color:rgba(26,14,5,.5);margin-top:4px">

                                            @if($item->size)
                                            <span style="margin-right:8px">
                                                📏 Size: <strong>{{ $item->size }}</strong>
                                            </span>
                                            @endif

                                            @if($item->sugar)
                                            <span style="margin-right:8px">
                                                🍬 Đường: {{ $item->sugar }}
                                            </span>
                                            @endif

                                            @if($item->ice)
                                            <span>
                                                ❄️ Đá: {{ $item->ice }}
                                            </span>
                                            @endif

                                        </div>

                                    </div>
                                </div>
                            </td>

                            <td style="text-align:center">
                                <span
                                    style="background:var(--latte);color:var(--brown);padding:4px 12px;border-radius:20px;font-weight:600;font-size:.85rem">
                                    × {{ $item->quantity }}
                                </span>
                            </td>

                            <td style="text-align:right;color:rgba(26,14,5,.6);font-size:.875rem">
                                {{ number_format($item->price, 0, ',', '.') }}₫
                            </td>

                            <td style="text-align:right;font-weight:700;color:var(--espresso)">
                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="3"
                                style="text-align:right;padding:16px;font-family:'Playfair Display',serif;font-size:1rem;font-weight:600;color:var(--espresso)">
                                Tổng cộng
                            </td>
                            <td style="text-align:right;padding:16px">
                                <span
                                    style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--caramel)">
                                    {{ number_format($order->total_price, 0, ',', '.') }}₫
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div style="display:flex;gap:12px;flex-wrap:wrap">

            <!-- BACK -->
            <a href="{{ route('staff.orders.index') }}" class="btn-outline-cafe">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>

            <!-- PAY -->
            @if($order->status === 'pending')
            <form action="{{ route('staff.orders.pay', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-gold">
                    <i class="bi bi-credit-card-fill"></i> Xác nhận thanh toán
                </button>
            </form>
            @endif

            <!-- EDIT -->
            <a href="{{ route('staff.orders.edit', $order->id) }}"
                class="btn-espresso {{ $order->status !== 'pending' ? 'disabled-link' : '' }}" @if($order->status !==
                'pending') onclick="return false;" @endif>
                <i class="bi bi-pencil-square"></i> Sửa đơn
            </a>

            <!-- CANCEL -->
            @if($order->status === 'pending')
            <button class="btn-danger-cafe" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $order->id }}">
                <i class="bi bi-x-circle"></i> Hủy đơn
            </button>
            @endif

            <!-- PRINT -->
            <button onclick="window.print()" class="btn-outline-cafe">
                <i class="bi bi-printer"></i> In đơn
            </button>

        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">

        <div class="card-cafe" style="margin-bottom:16px">
            <div class="card-header-cafe">
                <h5 style="font-size:.95rem">
                    <i class="bi bi-info-circle me-2" style="color:var(--caramel)"></i>
                    Thông tin đơn
                </h5>
            </div>

            <div class="card-body-cafe">
                <div style="font-size:.875rem;line-height:1.6">

                    <div
                        style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed rgba(26,14,5,.08)">
                        <span>Mã đơn</span>
                        <strong>#{{ $order->id }}</strong>
                    </div>

                    <div
                        style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed rgba(26,14,5,.08)">
                        <span>Bàn</span>
                        <strong>{{ $order->table?->name ?? '—' }}</strong>
                    </div>

                    <div
                        style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed rgba(26,14,5,.08)">
                        <span>Nhân viên</span>
                        <strong>{{ $order->user?->name ?? '—' }}</strong>
                    </div>

                    <div
                        style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed rgba(26,14,5,.08)">
                        <span>Số món</span>
                        <strong>{{ $order->items->sum('quantity') }} món</strong>
                    </div>

                    <div style="display:flex;justify-content:space-between;padding:8px 0">
                        <span>Tạo lúc</span>
                        <strong>{{ $order->created_at->format('H:i d/m/Y') }}</strong>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL HỦY -->
<div class="modal fade" id="cancelModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px">

            <form action="{{ route('staff.orders.cancel', $order->id) }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">❌ Hủy đơn #{{ $order->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label style="font-weight:600;margin-bottom:6px">Lý do hủy</label>
                    <textarea name="reason" class="form-control" rows="4" placeholder="Nhập lý do hủy..."
                        required></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline-cafe" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn-danger-cafe">Xác nhận hủy</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection