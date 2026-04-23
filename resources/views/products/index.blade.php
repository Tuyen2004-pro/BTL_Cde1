@extends('layout.master')

@section('title', 'Sản phẩm')

@section('breadcrumb')
<li class="breadcrumb-item active">Sản phẩm</li>
@endsection

@section('content')
<div class="card-cafe">
    <div class="card-header-cafe">
        <h5><i class="bi bi-cup-hot-fill me-2" style="color:var(--caramel)"></i>Danh sách sản phẩm</h5>
        <a href="{{ route('products.create') }}" class="btn-espresso">
            <i class="bi bi-plus-lg"></i> Thêm sản phẩm
        </a>
    </div>

    <div class="card-body-cafe" style="padding:0">
        @if($products->isEmpty())
        <div class="empty-state">
            <i class="bi bi-cup-hot"></i>
            <p>Chưa có sản phẩm nào. <a href="{{ route('products.create') }}" style="color:var(--caramel)">Thêm ngay</a></p>
        </div>
        @else
        <div style="overflow-x:auto">
            <table class="table-cafe">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th>Trạng thái</th>
                        <th style="width:180px;text-align:center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                    <tr>
                        <td style="color:rgba(26,14,5,.35);font-size:.8rem">{{ $index + 1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:12px">
                                @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    style="width:44px;height:44px;border-radius:10px;object-fit:cover;border:1px solid rgba(26,14,5,.1)">
                                @else
                                <div style="width:44px;height:44px;border-radius:10px;background:var(--latte);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0">☕</div>
                                @endif
                                <div>
                                    <div style="font-weight:600;color:var(--espresso)">{{ $product->name }}</div>
                                    @if($product->description)
                                    <div style="font-size:.75rem;color:rgba(26,14,5,.4);margin-top:2px">{{ Str::limit($product->description, 40) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($product->category)
                            <span class="badge-cafe" style="background:var(--latte);color:var(--brown)">
                                {{ $product->category->name }}
                            </span>
                            @else
                            <span style="color:rgba(26,14,5,.3);font-size:.82rem">—</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-weight:700;color:var(--caramel);font-size:.95rem">
                                {{ number_format($product->price, 0, ',', '.') }}₫
                            </span>
                        </td>
                        <td>
                            @php $status = $product->status ?? 'active'; @endphp
                            @if($status === 'active')
                            <span class="badge-cafe badge-available">Đang bán</span>
                            @else
                            <span class="badge-cafe badge-cancelled">Ngừng bán</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <div style="display:flex;align-items:center;justify-content:center;gap:8px">
                                <a href="{{ route('products.edit', $product->id) }}" class="btn-outline-cafe" style="padding:6px 12px;font-size:.8rem">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <form id="del-prd-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                </form>
                                <button onclick="confirmDelete('del-prd-{{ $product->id }}')" class="btn-danger-cafe" style="font-size:.8rem;padding:6px 12px">
                                    <i class="bi bi-trash"></i> Xoá
                                </button>
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