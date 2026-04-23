@extends('layout.master')

@section('title', 'Tạo đơn hàng')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('orders.index') }}" style="color:var(--caramel);text-decoration:none">Đơn
        hàng</a></li>
<li class="breadcrumb-item active">Tạo mới</li>
@endsection

@push('styles')
<style>
    .product-item {
        background: #fff;
        border: 1.5px solid rgba(26, 14, 5, .08);
        border-radius: 12px;
        padding: 16px;
        transition: all .2s;
        position: relative;
    }

    .product-item:hover {
        border-color: var(--caramel);
        box-shadow: 0 4px 16px rgba(196, 127, 46, .1);
    }

    .product-item.selected {
        border-color: var(--gold);
        background: rgba(232, 168, 56, .05);
    }

    .qty-control {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1.5px solid rgba(107, 63, 31, .2);
        border-radius: 8px;
        overflow: hidden;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        background: var(--latte);
        border: none;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 700;
        color: var(--brown);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .15s;
    }

    .qty-btn:hover {
        background: var(--caramel);
        color: #fff;
    }

    .qty-input {
        width: 44px;
        height: 32px;
        border: none;
        text-align: center;
        font-size: .9rem;
        font-weight: 600;
        color: var(--espresso);
        background: #fff;
        -moz-appearance: textfield;
    }

    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    #order-summary {
        position: sticky;
        top: 80px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px dashed rgba(26, 14, 5, .08);
        font-size: .85rem;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .search-box {
        position: relative;
        margin-bottom: 16px;
    }

    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(107, 63, 31, .4);
    }

    .search-box input {
        width: 100%;
        background: var(--cream);
        border: 1.5px solid rgba(107, 63, 31, .15);
        border-radius: 9px;
        padding: 9px 14px 9px 36px;
        font-size: .875rem;
        color: var(--espresso);
        font-family: 'DM Sans', sans-serif;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--caramel);
    }
</style>
@endpush

@section('content')
<form action="{{ route('orders.store') }}" method="POST" id="orderForm">
    @csrf
    <div class="row g-4">
        <!-- Left: products -->
        <div class="col-lg-8">
            <div class="card-cafe">
                <div class="card-header-cafe">
                    <h5><i class="bi bi-cup-hot me-2" style="color:var(--caramel)"></i>Chọn sản phẩm</h5>
                    <div class="search-box" style="margin:0;width:220px">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchProduct" placeholder="Tìm sản phẩm...">
                    </div>
                </div>
                <div class="card-body-cafe">
                    <div class="row g-3" id="productGrid">
                        @foreach($products as $product)
                        <div class="col-md-6 product-col"
                            data-name="{{ \Illuminate\Support\Str::lower($product->name) }}">
                            <div class="product-item" id="pi-{{ $product->id }}">
                                <div style="display:flex;gap:12px;align-items:flex-start">

                                    {{-- IMAGE --}}
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        style="width:52px;height:52px;border-radius:10px;object-fit:cover;flex-shrink:0">
                                    @else
                                    <div style="width:52px;height:52px;border-radius:10px;background:var(--latte);
                            display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0">
                                        ☕
                                    </div>
                                    @endif

                                    {{-- INFO --}}
                                    <div style="flex:1;min-width:0">

                                        <div
                                            style="font-weight:600;font-size:.9rem;color:var(--espresso);margin-bottom:2px">
                                            {{ $product->name }}
                                        </div>

                                        <div style="font-size:.75rem;color:rgba(26,14,5,.4);margin-bottom:8px">
                                            {{ optional($product->category)->name ?? 'Khác' }}
                                        </div>

                                        <div
                                            style="font-weight:700;color:var(--caramel);font-size:.9rem;margin-bottom:10px">
                                            {{ number_format($product->price, 0, ',', '.') }}₫
                                        </div>

                                        {{-- QTY --}}
                                        <div class="qty-control">
                                            <button type="button" class="qty-btn"
                                                onclick="changeQty('{{ $product->id }}', -1)">−</button>

                                            <input type="number" name="products[{{ $product->id }}]"
                                                id="qty-{{ $product->id }}" class="qty-input" value="0" min="0" max="99"
                                                data-price="{{ $product->price }}" data-name="{{ $product->name }}"
                                                onchange="updateSummary()">

                                            <button type="button" class="qty-btn"
                                                onclick="changeQty('{{ $product->id }}', 1)">+</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: summary -->
        <div class="col-lg-4">
            <div id="order-summary">
                <div class="card-cafe" style="margin-bottom:16px">
                    <div class="card-header-cafe">
                        <h5><i class="bi bi-layout-three-columns me-2" style="color:var(--caramel)"></i>Chọn bàn</h5>
                    </div>
                    <div class="card-body-cafe">
                        <label class="form-label-cafe">Bàn <span style="color:#dc2626">*</span></label>
                        <select name="table_id" class="form-select-cafe" required>
                            <option value="">— Chọn bàn —</option>
                            @foreach(\App\Models\Table::where('status','available')->orWhereNull('status')->get() as
                            $table)
                            <option value="{{ $table->id }}">
                                {{ $table->name }}{{ $table->capacity ? ' ('.$table->capacity.' người)' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card-cafe">
                    <div class="card-header-cafe">
                        <h5><i class="bi bi-cart3 me-2" style="color:var(--caramel)"></i>Tóm tắt đơn</h5>
                    </div>
                    <div class="card-body-cafe">
                        <div id="summary-items" style="min-height:60px;margin-bottom:16px">
                            <div style="text-align:center;color:rgba(26,14,5,.3);font-size:.84rem;padding:20px 0">
                                <i class="bi bi-bag"
                                    style="font-size:1.5rem;display:block;margin-bottom:8px;opacity:.4"></i>
                                Chưa chọn món nào
                            </div>
                        </div>

                        <div style="border-top:2px solid rgba(26,14,5,.08);padding-top:16px">
                            <div style="display:flex;justify-content:space-between;align-items:center">
                                <span style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:600">Tổng
                                    cộng</span>
                                <span id="total-display"
                                    style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--caramel)">0₫</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-gold"
                            style="width:100%;justify-content:center;margin-top:16px;padding:14px">
                            <i class="bi bi-check-circle-fill"></i> Xác nhận tạo đơn
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn-outline-cafe"
                            style="width:100%;justify-content:center;margin-top:10px;padding:10px">
                            <i class="bi bi-arrow-left"></i> Huỷ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function changeQty(id, delta) {
        const input = document.getElementById('qty-' + id);
        let val = parseInt(input.value) + delta;
        if (val < 0) val = 0;
        if (val > 99) val = 99;
        input.value = val;
        updateSummary();
    }

    function updateSummary() {
        const inputs = document.querySelectorAll('.qty-input');
        let total = 0;
        let html = '';

        inputs.forEach(input => {
            const qty = parseInt(input.value);
            if (qty > 0) {
                const price = parseFloat(input.dataset.price);
                const name = input.dataset.name;
                const sub = price * qty;
                total += sub;
                html += `<div class="summary-row">
                <span style="max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${name} ×${qty}</span>
                <span style="font-weight:600;color:var(--caramel)">${formatNum(sub)}₫</span>
            </div>`;
                // highlight card
                const card = input.closest('.product-item');
                if (card) card.classList.add('selected');
            } else {
                const card = input.closest('.product-item');
                if (card) card.classList.remove('selected');
            }
        });

        document.getElementById('summary-items').innerHTML = html ||
            `<div style="text-align:center;color:rgba(26,14,5,.3);font-size:.84rem;padding:20px 0"><i class="bi bi-bag" style="font-size:1.5rem;display:block;margin-bottom:8px;opacity:.4"></i>Chưa chọn món nào</div>`;
        document.getElementById('total-display').textContent = formatNum(total) + '₫';
    }

    function formatNum(n) {
        return n.toLocaleString('vi-VN');
    }

    // Search
    document.getElementById('searchProduct').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.product-col').forEach(col => {
            col.style.display = col.dataset.name.includes(q) ? '' : 'none';
        });
    });
</script>
@endpush