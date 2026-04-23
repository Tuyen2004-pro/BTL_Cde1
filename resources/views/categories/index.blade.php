@extends('layout.master')

@section('title', 'Danh mục')

@section('breadcrumb')
<li class="breadcrumb-item active">Danh mục</li>
@endsection

@section('content')
<div class="card-cafe">
    <div class="card-header-cafe">
        <h5><i class="bi bi-bookmark-fill me-2" style="color:var(--caramel)"></i>Danh sách danh mục</h5>
        <a href="{{ route('categories.create') }}" class="btn-espresso">
            <i class="bi bi-plus-lg"></i> Thêm danh mục
        </a>
    </div>

    <div class="card-body-cafe" style="padding:0">
        @if($categories->isEmpty())
        <div class="empty-state">
            <i class="bi bi-bookmark"></i>
            <p>Chưa có danh mục nào. <a href="{{ route('categories.create') }}" style="color:var(--caramel)">Thêm ngay</a></p>
        </div>
        @else
        <div style="overflow-x:auto">
            <table class="table-cafe">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Số sản phẩm</th>
                        <th style="width:160px;text-align:center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $index => $category)
                    <tr>
                        <td style="color:rgba(26,14,5,.35);font-size:.8rem">{{ $index + 1 }}</td>
                        <td>
                            <span style="font-weight:600;color:var(--espresso)">{{ $category->name }}</span>
                        </td>
                        <td style="color:rgba(26,14,5,.55);font-size:.85rem">
                            {{ $category->description ?? '—' }}
                        </td>
                        <td>
                            <span class="badge-cafe badge-active">
                                {{ $category->products->count() }} sản phẩm
                            </span>
                        </td>
                        <td style="text-align:center">
                            <div style="display:flex;align-items:center;justify-content:center;gap:8px">
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn-outline-cafe" style="padding:6px 12px;font-size:.8rem">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <form id="del-cat-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                </form>
                                <button onclick="confirmDelete('del-cat-{{ $category->id }}')" class="btn-danger-cafe" style="font-size:.8rem;padding:6px 12px">
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