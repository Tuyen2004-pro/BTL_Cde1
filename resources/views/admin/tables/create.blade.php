@extends('layouts.master')

@section('title', 'Thêm bàn')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.tables.index') }}"
        style="color:var(--caramel);text-decoration:none">Bàn</a></li>
<li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card-cafe">
            <div class="card-header-cafe">
                <h5><i class="bi bi-plus-circle me-2" style="color:var(--caramel)"></i>Thêm bàn mới</h5>
            </div>
            <div class="card-body-cafe">
                <form action="{{ route('admin.tables.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label-cafe">Tên bàn <span style="color:#dc2626">*</span></label>
                        <input type="text" name="name"
                            class="form-control-cafe {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            placeholder="VD: Bàn 01, Bàn VIP, Bàn Sân Vườn..." value="{{ old('name') }}" autofocus>
                        @error('name')
                        <div class="invalid-feedback-cafe"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label-cafe">Sức chứa (người)</label>
                        <input type="number" name="capacity" class="form-control-cafe" placeholder="VD: 4"
                            value="{{ old('capacity') }}" min="1" max="50">
                    </div>

                    <div class="mb-4">
                        <label class="form-label-cafe">Trạng thái</label>
                        <select name="status" class="form-select-cafe">
                            <option value="available">🟢 Trống</option>
                            <option value="occupied">🔴 Đang sử dụng</option>
                        </select>
                    </div>

                    <div style="display:flex;gap:12px;margin-top:8px">
                        <button type="submit" class="btn-gold">
                            <i class="bi bi-check-lg"></i> Lưu bàn
                        </button>
                        <a href="{{ route('admin.tables.index') }}" class="btn-outline-cafe">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection