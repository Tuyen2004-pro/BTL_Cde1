@extends('layouts.master')

@section('title', 'Quản lý bàn')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Quản lý bàn</h4>

    <a href="{{ route('admin.tables.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Thêm bàn
    </a>
</div>

@if($tables->isEmpty())
<div class="text-center p-5 bg-white shadow-sm rounded">
    <i class="bi bi-layout-three-columns fs-1 text-muted"></i>
    <p class="mt-3">Chưa có bàn nào</p>
</div>
@else

<div class="row g-3">

    @foreach($tables as $table)
    @php
    $status = $table->status ?? 'empty';
    $isOccupied = in_array($status, ['using','occupied']);
    @endphp

    <div class="col-md-4 col-lg-3">

        <div class="table-card">

            <div class="table-icon">🪑</div>

            <div class="table-name">
                {{ $table->name }}
            </div>

            <div class="table-capacity">
                @if($table->capacity)
                {{ $table->capacity }} người
                @else
                Chưa rõ sức chứa
                @endif
            </div>

            <div class="mb-3">
                @if($isOccupied)
                <span class="badge bg-danger">Đang sử dụng</span>
                @else
                <span class="badge bg-success">Trống</span>
                @endif
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.tables.edit',$table->id) }}" class="btn btn-sm btn-outline-primary w-100">
                    Sửa
                </a>

                <form id="del-{{ $table->id }}" method="POST" action="{{ route('admin.tables.destroy',$table->id) }}">
                    @csrf
                    @method('DELETE')
                </form>

                <button onclick="confirmDelete('del-{{ $table->id }}')" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

        </div>

    </div>
    @endforeach

</div>

{{-- STATS --}}
<div class="mt-4 p-3 bg-white rounded shadow-sm d-flex gap-4 flex-wrap">
    <span>🟢 Trống: {{ $tables->whereIn('status',['empty',null])->count() }}</span>
    <span>🔴 Đang dùng: {{ $tables->whereIn('status',['using','occupied'])->count() }}</span>
    <span>Tổng: {{ $tables->count() }}</span>
</div>

@endif


{{-- STYLE --}}
<style>
    .table-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        text-align: center;
        transition: 0.3s;
    }

    .table-card:hover {
        transform: translateY(-5px);
    }

    .table-icon {
        font-size: 40px;
        margin-bottom: 10px;
    }

    .table-name {
        font-weight: bold;
        font-size: 18px;
    }

    .table-capacity {
        font-size: 13px;
        color: #888;
        margin-bottom: 10px;
    }
</style>

@endsection