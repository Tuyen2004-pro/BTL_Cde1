@extends('layouts.master')

@section('title', 'Quản lý bàn')

@section('breadcrumb')
<li class="breadcrumb-item active">Bàn</li>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
    <div></div>
    <a href="{{ route('admin.tables.create') }}" class="btn-espresso">
        <i class="bi bi-plus-lg"></i> Thêm bàn mới
    </a>
</div>

@if($tables->isEmpty())
<div class="card-cafe">
    <div class="empty-state">
        <i class="bi bi-layout-three-columns"></i>
        <p>Chưa có bàn nào. <a href="{{ route('admin.tables.create') }}" style="color:var(--caramel)">Thêm ngay</a></p>
    </div>
</div>
@else
<div class="row g-3">
    @foreach($tables as $table)
    @php
    $status = $table->status ?? 'available';
    $isOccupied = $status === 'occupied';
    @endphp
    <div class="col-md-4 col-lg-3">
        <div <div @style([ 'background'=> '#fff',
            'border-radius' => '14px',
            'padding' => '22px',
            'box-shadow' => 'var(--shadow)',
            'border' => $isOccupied ? '2px solid #fcd34d' : '2px solid rgba(26,14,5,.06)',
            'transition' => 'all .2s',
            'position' => 'relative',
            'overflow' => 'hidden'
            ])>
            <div @style([ 'position'=> 'absolute',
                'top' => '0',
                'right' => '0',
                'width' => '60px',
                'height' => '60px',
                'border-radius' => '0 14px 0 60px',
                'background' => $isOccupied ? 'rgba(252,211,77,.2)' : 'rgba(16,185,129,.1)'
                ])>
            </div>
        </div>

        <div style="font-size:2.5rem;line-height:1;margin-bottom:12px">🪑</div>
        <div
            style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--espresso);margin-bottom:4px">
            {{ $table->name }}
        </div>

        <div style="font-size:.8rem;color:rgba(26,14,5,.45);margin-bottom:12px">
            @if($table->capacity)
            <i class="bi bi-people me-1"></i>{{ $table->capacity }} người
            @else
            <i class="bi bi-dash-circle me-1"></i>Chưa rõ sức chứa
            @endif
        </div>

        <div style="margin-bottom:16px">
            @if($isOccupied)
            <span class="badge-cafe badge-occupied">🔴 Đang sử dụng</span>
            @else
            <span class="badge-cafe badge-available">🟢 Trống</span>
            @endif
        </div>

        <div style="display:flex;gap:8px">
            <a href="{{ route('admin.tables.edit', $table->id) }}" class="btn-outline-cafe"
                style="flex:1;justify-content:center;padding:7px 10px;font-size:.8rem">
                <i class="bi bi-pencil"></i> Sửa
            </a>
            <form id="del-tbl-{{ $table->id }}" action="{{ route('admin.tables.destroy', $table->id) }}" method="POST">
                @csrf @method('DELETE')
            </form>
            <button onclick="confirmDelete('del-tbl-{{ $table->id }}')" class="btn-danger-cafe"
                style="font-size:.8rem;padding:7px 12px">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</div>
@endforeach
</div>
@endif

<div class="card-cafe" style="margin-top:24px">
    <div class="card-body-cafe" style="display:flex;gap:24px;flex-wrap:wrap;align-items:center">
        <span style="font-size:.82rem;font-weight:600;color:rgba(26,14,5,.5)">THỐNG KÊ:</span>
        <span class="badge-cafe badge-available" style="font-size:.8rem">
            🟢 Trống: {{ $tables->where('status', 'available')->count() + $tables->whereNull('status')->count() }}
        </span>
        <span class="badge-cafe badge-occupied" style="font-size:.8rem">
            🔴 Đang dùng: {{ $tables->where('status', 'occupied')->count() }}
        </span>
        <span style="font-size:.82rem;color:rgba(26,14,5,.4)">
            Tổng: {{ $tables->count() }} bàn
        </span>
    </div>
</div>
@endsection