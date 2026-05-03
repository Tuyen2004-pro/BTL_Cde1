@extends('layouts.master')

@section('title', 'Tạo đơn hàng')

@section('content')

<form id="order-form" action="{{ route('staff.orders.store') }}" method="POST">
    @csrf

    <div class="row g-4">

        <!-- LEFT -->
        <div class="col-lg-8">

            {{-- CHỌN BÀN --}}
            <div class="card shadow-sm p-3 mb-3">
                <h5>Chọn bàn</h5>

                <select name="table_id" class="form-control" required>
                    <option value="">-- Chọn bàn --</option>
                    @foreach($tables as $table)
                    <option value="{{ $table->id }}">
                        {{ $table->name }}
                    </option>
                    @endforeach
                </select>
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

                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-lg-4">

            <div class="card shadow-sm p-3 mb-3">

                <h5>Món đã chọn</h5>

                <div id="order-preview">
                    <p class="text-muted">Chưa chọn món</p>
                </div>

                <hr>

                <h6>Tổng tiền: <span id="total-price">0đ</span></h6>

            </div>

            <div class="card shadow-sm p-3">

                <h5>Xác nhận đơn</h5>

                <button type="submit" class="btn btn-warning w-100 mb-2">
                    Tạo đơn
                </button>

                <a href="{{ route('staff.orders.index') }}" class="btn btn-outline-secondary w-100">
                    Huỷ
                </a>

            </div>
        </div>

    </div>

    {{-- hidden items --}}
    <div id="items-container"></div>

    {{-- ================= MODAL ================= --}}
    @foreach($products as $product)
    <div class="modal fade" id="productModal{{ $product->id }}">
        <div class="modal-dialog">
            <div class="modal-content p-3">

                <h5>{{ $product->name }}</h5>

                <img src="/storage/{{ $product->image }}" style="height:200px;object-fit:cover;border-radius:10px">

                <p class="text-danger mt-2">
                    {{ number_format($product->price) }}đ
                </p>

                {{-- SIZE --}}
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

                {{-- ĐƯỜNG --}}
                <label>Đường</label>
                <select class="form-control mb-2 sugar">
                    <option value="Không">Không</option>
                    <option value="Ít">Ít</option>
                    <option value="Nhiều">Nhiều</option>
                </select>

                {{-- ĐÁ --}}
                <label>Đá</label>
                <select class="form-control mb-2 ice">
                    <option value="Không">Không</option>
                    <option value="Ít">Ít</option>
                    <option value="Nhiều">Nhiều</option>
                </select>

                {{-- QTY --}}
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

{{-- ================= SCRIPT ================= --}}
<script>
    let cart = [];

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.onclick = function() {
            let id = this.dataset.id;
            document.querySelectorAll('.product-item').forEach(p => {
                p.style.display = (id === 'all' || p.dataset.category == id) ? 'block' : 'none';
            });
        }
    });

    // ADD TO CART
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.onclick = function() {

            let modal = this.closest('.modal');

            let sizeSelect = modal.querySelector('.size');
            let sizeOption = sizeSelect.selectedOptions[0];

            let item = {
                product_id: this.dataset.id,
                name: this.dataset.name,
                base_price: parseInt(this.dataset.price),
                size_id: sizeOption.value,
                size: sizeOption.text,
                extra: parseInt(sizeOption.dataset.price || 0),
                sugar: modal.querySelector('.sugar').value,
                ice: modal.querySelector('.ice').value,
                qty: parseInt(modal.querySelector('.qty').value)
            };

            cart.push(item);

            updatePreview();

            bootstrap.Modal.getInstance(modal).hide();
        }
    });

    // UPDATE PREVIEW
    function updatePreview() {
        let preview = document.getElementById('order-preview');
        let totalEl = document.getElementById('total-price');
        let itemsContainer = document.getElementById('items-container');

        let html = '';
        let inputs = '';
        let total = 0;

        cart.forEach((item, index) => {

            let price = item.base_price + item.extra;

            html += `
            <div style="border-bottom:1px solid #eee;padding:5px 0">
                <b>${item.name}</b> x${item.qty}
                <button type="button" onclick="removeItem(${index})" style="float:right">❌</button>
                <br>
                <small>${item.size} (${price.toLocaleString()}đ) | ${item.sugar} | ${item.ice}</small>
            </div>
        `;

            inputs += `
            <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
            <input type="hidden" name="items[${index}][size_id]" value="${item.size_id}">
            <input type="hidden" name="items[${index}][sugar]" value="${item.sugar}">
            <input type="hidden" name="items[${index}][ice]" value="${item.ice}">
            <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
        `;

            total += price * item.qty;
        });

        preview.innerHTML = html || '<p class="text-muted">Chưa chọn món</p>';
        itemsContainer.innerHTML = inputs;
        totalEl.innerText = total.toLocaleString() + 'đ';
    }

    // REMOVE ITEM
    function removeItem(index) {
        cart.splice(index, 1);
        updatePreview();
    }
</script>

@endsection