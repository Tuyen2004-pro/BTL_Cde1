@extends('layout.master')

@section('title', 'Thêm sản phẩm')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('products.index') }}" style="color:var(--caramel);text-decoration:none">Sản phẩm</a></li>
<li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card-cafe">
            <div class="card-header-cafe">
                <h5><i class="bi bi-plus-circle me-2" style="color:var(--caramel)"></i>Thêm sản phẩm mới</h5>
            </div>
            <div class="card-body-cafe">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label-cafe">Tên sản phẩm <span style="color:#dc2626">*</span></label>
                            <input type="text" name="name" class="form-control-cafe {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                placeholder="VD: Cà phê sữa đá, Matcha latte..."
                                value="{{ old('name') }}" autofocus>
                            @error('name')
                            <div class="invalid-feedback-cafe"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-cafe">Giá bán (₫) <span style="color:#dc2626">*</span></label>
                            <input type="number" name="price" class="form-control-cafe {{ $errors->has('price') ? 'is-invalid' : '' }}"
                                placeholder="VD: 35000"
                                value="{{ old('price') }}" min="0" step="500">
                            @error('price')
                            <div class="invalid-feedback-cafe"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-cafe">Danh mục <span style="color:#dc2626">*</span></label>
                            <select name="category_id" class="form-select-cafe {{ $errors->has('category_id') ? 'is-invalid' : '' }}">
                                <option value="">— Chọn danh mục —</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="invalid-feedback-cafe"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-cafe">Trạng thái</label>
                            <select name="status" class="form-select-cafe">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>✅ Đang bán</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>⏸ Ngừng bán</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label-cafe">Ảnh sản phẩm</label>
                            <input type="file" name="image" class="form-control-cafe" accept="image/*" id="imageInput">
                            <div id="preview-wrap" style="margin-top:12px;display:none">
                                <img id="preview-img" style="width:80px;height:80px;border-radius:10px;object-fit:cover;border:2px solid var(--latte)">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label-cafe">Mô tả sản phẩm</label>
                            <textarea name="description" class="form-control-cafe" rows="3"
                                placeholder="Mô tả ngắn về sản phẩm...">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div style="display:flex;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid rgba(26,14,5,.07)">
                        <button type="submit" class="btn-gold">
                            <i class="bi bi-check-lg"></i> Lưu sản phẩm
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
                <h5><i class="bi bi-info-circle me-2" style="color:var(--caramel)"></i>Hướng dẫn</h5>
            </div>
            <div class="card-body-cafe">
                <div style="font-size:.84rem;color:rgba(26,14,5,.6);line-height:1.7">
                    <p><i class="bi bi-dot" style="color:var(--caramel)"></i><strong>Tên sản phẩm</strong>: Nhập rõ ràng, ngắn gọn.</p>
                    <p><i class="bi bi-dot" style="color:var(--caramel)"></i><strong>Giá bán</strong>: Nhập số nguyên (VNĐ).</p>
                    <p><i class="bi bi-dot" style="color:var(--caramel)"></i><strong>Danh mục</strong>: Chọn đúng nhóm sản phẩm.</p>
                    <p><i class="bi bi-dot" style="color:var(--caramel)"></i><strong>Ảnh</strong>: Khuyến nghị tỉ lệ 1:1, tối đa 2MB.</p>
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
                document.getElementById('preview-img').src = ev.target.result;
                document.getElementById('preview-wrap').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush