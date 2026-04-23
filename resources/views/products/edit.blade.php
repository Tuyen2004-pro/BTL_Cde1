@extends('layout.master')

@section('title', 'Sửa sản phẩm')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('products.index') }}"
        style="color:var(--caramel);text-decoration:none">Sản phẩm</a></li>
<li class="breadcrumb-item active">Sửa: {{ $product->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card-cafe">
            <div class="card-header-cafe">
                <h5><i class="bi bi-pencil-square me-2" style="color:var(--caramel)"></i>Sửa sản phẩm</h5>
            </div>
            <div class="card-body-cafe">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label-cafe">Tên sản phẩm <span style="color:#dc2626">*</span></label>
                            <input type="text" name="name"
                                class="form-control-cafe {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                value="{{ old('name', $product->name) }}" autofocus>
                            @error('name')
                            <div class="invalid-feedback-cafe"><i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-cafe">Giá bán (₫) <span style="color:#dc2626">*</span></label>
                            <input type="number" name="price"
                                class="form-control-cafe {{ $errors->has('price') ? 'is-invalid' : '' }}"
                                value="{{ old('price', $product->price) }}" min="0" step="500">
                            @error('price')
                            <div class="invalid-feedback-cafe"><i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-cafe">Danh mục <span style="color:#dc2626">*</span></label>
                            <select name="category_id" class="form-select-cafe">
                                <option value="">— Chọn danh mục —</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-cafe">Trạng thái</label>
                            <select name="status" class="form-select-cafe">
                                <option value="active"
                                    {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>✅ Đang bán
                                </option>
                                <option value="inactive"
                                    {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>⏸ Ngừng bán
                                </option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label-cafe">Ảnh sản phẩm</label>
                            @if($product->image)
                            <div style="margin-bottom:10px">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    id="preview-img"
                                    style="width:80px;height:80px;border-radius:10px;object-fit:cover;border:2px solid var(--latte)">
                                <div style="font-size:.75rem;color:rgba(26,14,5,.4);margin-top:4px">Ảnh hiện tại</div>
                            </div>
                            @else
                            <img id="preview-img"
                                style="display:none;width:80px;height:80px;border-radius:10px;object-fit:cover;border:2px solid var(--latte);margin-bottom:10px">
                            @endif
                            <input type="file" name="image" class="form-control-cafe" accept="image/*" id="imageInput">
                        </div>

                        <div class="col-12">
                            <label class="form-label-cafe">Mô tả sản phẩm</label>
                            <textarea name="description" class="form-control-cafe"
                                rows="3">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>

                    <div
                        style="display:flex;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid rgba(26,14,5,.07)">
                        <button type="submit" class="btn-gold">
                            <i class="bi bi-check-lg"></i> Cập nhật
                        </button>
                        <a href="{{ route('products.index') }}" class="btn-outline-cafe">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-cafe">
            <div class="card-header-cafe">
                <h5 style="font-size:.95rem"><i class="bi bi-bar-chart me-2" style="color:var(--caramel)"></i>Thống kê
                </h5>
            </div>
            <div class="card-body-cafe">
                <div style="font-size:.85rem;line-height:2">
                    <div
                        style="display:flex;justify-content:space-between;border-bottom:1px dashed rgba(26,14,5,.08);padding-bottom:8px;margin-bottom:8px">
                        <span style="color:rgba(26,14,5,.5)">ID sản phẩm</span>
                        <strong>#{{ $product->id }}</strong>
                    </div>
                    <div
                        style="display:flex;justify-content:space-between;border-bottom:1px dashed rgba(26,14,5,.08);padding-bottom:8px;margin-bottom:8px">
                        <span style="color:rgba(26,14,5,.5)">Ngày tạo</span>
                        <strong>{{ $product->created_at->format('d/m/Y') }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between">
                        <span style="color:rgba(26,14,5,.5)">Đã bán</span>
                        <strong>{{ $product->orderItems->count() }} lần</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            const img = document.getElementById('preview-img');
            img.src = ev.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush