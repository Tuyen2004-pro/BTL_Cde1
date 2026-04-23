<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin - Cafe</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    body {
        background: #f4f6f9;
    }

    .sidebar {
        height: 100vh;
        background: #1f2937;
        color: #fff;
    }

    .sidebar a {
        color: #cbd5e1;
        text-decoration: none;
        display: block;
        padding: 12px;
    }

    .sidebar a:hover {
        background: #374151;
        color: #fff;
    }

    .card {
        border-radius: 12px;
    }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-2 sidebar p-3">
                <h4>☕ Admin</h4>
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.categories.index') }}">Danh mục</a>
                <a href="{{ route('admin.products.index') }}">Sản phẩm</a>
                <a href="{{ route('admin.tables.index') }}">QL Bàn</a>
                <a href="{{ route('admin.orders.index') }}">Đơn hàng</a>
                <a href="{{ route('admin.users.index') }}">Người dùng</a>
            </div>

            <!-- CONTENT -->
            <div class="col-10 p-4">
                @yield('content')
            </div>

        </div>
    </div>

</body>

</html>