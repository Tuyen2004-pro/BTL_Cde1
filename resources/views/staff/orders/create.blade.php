@extends('layouts.master')

@section('title', 'Tạo đơn hàng')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('staff.orders.index') }}" style="color:var(--caramel);text-decoration:none">
        Đơn hàng
    </a>
</li>
<li class="breadcrumb-item active">Tạo mới</li>
@endsection

@section('content')
<form action="{{ route('staff.orders.store') }}" method="POST" id="orderForm">
    @csrf

    <div class="row g-4">

        <!-- LEFT -->
        <div class="col-lg-8">
            <div class="card-cafe">
                <div class="card-header-cafe">
                    <h5>Chọn sản phẩm</h5>
                </div>

                <div class="card-body-cafe">
                    <div class="row g-3">

                        @foreach($products as $product)
                        <div class="col-md-6">
                            <div class="product-item">

                                <div>{{ $product->name }}</div>
                                <div>{{ number_format($product->price) }}₫</div>

                                <input type="number" name="products[{{ $product->id }}]" value="0" min="0"
                                    class="form-control">

                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-lg-4">
            <div class="card-cafe">
                <div class="card-header-cafe">
                    <h5>Chọn bàn</h5>
                </div>

                <div class="card-body-cafe">

                    <select name="table_id" class="form-select-cafe" required>
                        <option value="">-- Chọn bàn --</option>

                        @foreach($tables as $table)
                        <option value="{{ $table->id }}">
                            {{ $table->name }}
                        </option>
                        @endforeach

                    </select>

                    <button type="submit" class="btn-gold" style="margin-top:15px;width:100%">
                        Tạo đơn
                    </button>

                    <a href="{{ route('staff.orders.index') }}" class="btn-outline-cafe"
                        style="margin-top:10px;width:100%">
                        Huỷ
                    </a>

                </div>
            </div>
        </div>

    </div>
</form>
@endsection