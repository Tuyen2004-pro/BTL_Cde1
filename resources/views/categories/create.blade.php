@extends('layout.master')

@section('title', 'Thêm danh mục')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('categories.index') }}" style="color:var(--caramel);text-decoration:none">Danh mục</a></li>
<li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card-cafe">
            <div class="card-header-cafe">
                <h5><i class="bi bi-plus-circle me-2" style="color:var(--caramel)"></i>Thêm danh mục mới</h5>
            </div>
            <div class="card-body-cafe">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label-cafe">Tên danh mục <span style="color:#dc2626">*</span></label>
                        <input type="text" name="name" class="form-control-cafe {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            placeholder="VD: Cà phê, Trà sữa, Bánh ngọt..."
                            value="{{ old('name') }}" autofocus>
                        @error('name')
                        <div class="invalid-feedback-cafe"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label-cafe">Mô tả</label>
                        <textarea name="description" class="form-control-cafe" rows="4"
                            placeholder="Mô tả ngắn về danh mục...">{{ old('description') }}</textarea>
                    </div>

                    <div style="display:flex;gap:12px;margin-top:8px">
                        <button type="submit" class="btn-gold">
                            <i class="bi bi-check-lg"></i> Lưu danh mục
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn-outline-cafe">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection