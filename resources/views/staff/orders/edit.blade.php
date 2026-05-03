@extends('layout.master')

@section('title', 'Sửa đơn #' . $order->id)

@section('content')

<form id="order-form" action="{{ route('staff.orders.update', $order->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- BÀN --}}
            <div class="card shadow-sm p-3 mb-3">
                <h5>Bàn</h5>
                <input type="text" class="form-control" value="{{ $order->table->name ?? 'Không có' }}" disabled>
            </div>

            <div class="card shadow-sm p-3">
                <h5 class="mb-3">Chọn sản phẩm</h5>

                {{-- DANH MỤC --}}
                <div class="mb-3">
                    <button type="button" class="btn btn-outline-primary me-2 filter-btn" data-id="all">Tất cả</button>

                    @foreach($categories as $cat)
                    <button type="button" class="btn btn-outline-primary me-2 filter-btn" data-id="{{ $cat->id }}">
                        {{ $cat->name }}
                    </button>
                    @endforeach
                </div>

                <div class="row">
                    @foreach($products as $product)
                    <div class="col-md-4 mb-4 product-item" data-category="{{ $product->category_id }}">

                        <div class="card h-100 p-2 shadow-sm product-card" data-bs-toggle="modal"
                            data-bs-target="#productModal{{ $product->id }}">

                            <img src="/storage/{{ $product->image }}"
                                style="height:140px;object-fit:cover;border-radius:10px">

                            <h6 class="mt-2">{{ $product->name }}</h6>

                            <p class="text-danger">
                                {{ number_format($product->price) }}đ
                            </p>

                            <span class="badge bg-success qty-badge" id="badge-{{ $product->id }}" style="display:none">
                                Đã chọn (<span class="badge-qty">0</span>)
                            </span>

                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">

            <div class="card shadow-sm p-3 mb-3">
                <h5>Món đã chọn</h5>
                <div id="order-preview"></div>

                <hr>

                <h6>Tổng tiền: <span id="total-price">0đ</span></h6>
            </div>

            <div class="card shadow-sm p-3">
                <button class="btn btn-warning w-100 mb-2">
                    Cập nhật đơn
                </button>

                <a href="{{ route('staff.orders.index') }}" class="btn btn-outline-secondary w-100">
                    Huỷ
                </a>
            </div>

        </div>

    </div>

    {{-- hidden --}}
    <div id="items-container"></div>

    {{-- MODAL --}}
    @foreach($products as $product)
    <div class="modal fade" id="productModal{{ $product->id }}">
        <div class="modal-dialog">
            <div class="modal-content p-3">

                <h5>{{ $product->name }}</h5>

                <img src="/storage/{{ $product->image }}" style="height:200px;object-fit:cover;border-radius:10px">

                <p class="text-danger mt-2">
                    {{ number_format($product->price) }}đ
                </p>

                <label>Size</label>
                <select class="form-control mb-2 size">
                    @foreach($product->sizes as $s)
                    <option value="{{ $s->id }}" data-price="{{ $s->price }}">
                        {{ $s->size }}
                        @if($s->price > 0)
                        (+{{ number_format($s->price) }}đ)
                        @endif
                    </option>
                    @endforeach
                </select>

                <label>Đường</label>
                <select class="form-control mb-2 sugar">
                    <option>Không</option>
                    <option>Ít</option>
                    <option>Nhiều</option>
                </select>

                <label>Đá</label>
                <select class="form-control mb-2 ice">
                    <option>Không</option>
                    <option>Ít</option>
                    <option>Nhiều</option>
                </select>

                <label>Số lượng</label>
                <input type="number" value="1" min="1" class="form-control mb-3 qty">

                <button type="button" class="btn btn-success w-100 add-to-cart" data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                    Thêm món
                </button>

            </div>
        </div>
    </div>
    @endforeach

</form>

{{-- JSON DATA (KHÔNG LỖI FORMAT) --}}
<script id="initial-cart-data" type="application/json">
    {
        !!json_encode(
            $order - > items - > map(function($item) {
                return [
                    'product_id' => $item - > product_id,
                    'name' => $item - > product - > name,
                    'base_price' => (int) $item - > product - > price,
                    'size' => $item - > size,
                    'size_id' => $item - > size_id,
                    'extra' => (int)($item - > price - $item - > product - > price),
                    'sugar' => $item - > sugar,
                    'ice' => $item - > ice,
                    'qty' => (int) $item - > quantity
                ];
            })
        ) !!
    }
</script>

{{-- JS --}}
<script src="{{ asset('js/order-edit.js') }}"></script>

@endsection