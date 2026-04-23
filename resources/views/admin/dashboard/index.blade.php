@extends('layouts.master')

@section('content')

<h3>Dashboard</h3>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card p-3 bg-success text-white">
            Doanh thu
            <h4>{{ number_format($totalRevenue) }} đ</h4>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 bg-primary text-white">
            Đơn hàng
            <h4>{{ $totalOrders }}</h4>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 bg-warning text-white">
            Sản phẩm
            <h4>{{ $totalProducts }}</h4>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 bg-dark text-white">
            Người dùng
            <h4>{{ $totalUsers }}</h4>
        </div>
    </div>
</div>

@endsection