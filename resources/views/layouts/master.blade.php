<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>☕ CaféManager — @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --espresso: #1a0e05;
            --roast: #2d1a0e;
            --brown: #6b3f1f;
            --caramel: #c47f2e;
            --gold: #e8a838;
            --cream: #fdf6ee;
            --latte: #f5e6d0;
            --milk: #fdfaf6;
            --sidebar-w: 260px;
            --radius: 14px;
            --shadow: 0 4px 24px rgba(26, 14, 5, .10);
            --shadow-lg: 0 8px 40px rgba(26, 14, 5, .16);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--espresso);
            min-height: 100vh;
            display: flex;
        }

        /* ─── Sidebar ─── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--espresso);
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            box-shadow: 4px 0 30px rgba(0, 0, 0, .25);
        }

        .sidebar-brand {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
        }

        .sidebar-brand .brand-icon {
            font-size: 2rem;
            line-height: 1;
        }

        .sidebar-brand h1 {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: .5px;
            margin-top: 6px;
        }

        .sidebar-brand p {
            color: rgba(255, 255, 255, .4);
            font-size: .72rem;
            font-weight: 300;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 0;
            overflow-y: auto;
        }

        .nav-section-label {
            color: rgba(255, 255, 255, .25);
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            padding: 16px 24px 6px;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 24px;
            color: rgba(255, 255, 255, .62);
            font-size: .875rem;
            font-weight: 400;
            border-left: 3px solid transparent;
            transition: all .2s;
            text-decoration: none;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
            opacity: .8;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            color: var(--gold);
            background: rgba(232, 168, 56, .08);
            border-left-color: var(--gold);
        }

        .sidebar-nav .nav-link.active {
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255, 255, 255, .07);
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: rgba(255, 255, 255, .05);
            border-radius: 10px;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--caramel);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .85rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .user-chip .user-name {
            font-size: .82rem;
            font-weight: 500;
            color: rgba(255, 255, 255, .8);
        }

        .user-chip .user-role {
            font-size: .68rem;
            color: rgba(255, 255, 255, .35);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-logout {
            margin-left: auto;
            color: rgba(255, 255, 255, .35);
            font-size: 1rem;
            background: none;
            border: none;
            padding: 4px;
            cursor: pointer;
            transition: color .2s;
        }

        .btn-logout:hover {
            color: #e74c3c;
        }

        /* ─── Main Content ─── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: var(--milk);
            border-bottom: 1px solid rgba(26, 14, 5, .07);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--espresso);
        }

        .topbar .breadcrumb {
            font-size: .78rem;
            color: rgba(26, 14, 5, .45);
            margin: 0;
        }

        .topbar .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(26, 14, 5, .3);
        }

        .topbar .breadcrumb-item.active {
            color: var(--caramel);
        }

        .content-area {
            flex: 1;
            padding: 28px 32px;
        }

        /* ─── Cards ─── */
        .card-cafe {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(26, 14, 5, .06);
            overflow: hidden;
        }

        .card-cafe .card-header-cafe {
            padding: 20px 24px 16px;
            border-bottom: 1px solid rgba(26, 14, 5, .06);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-cafe .card-header-cafe h5 {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--espresso);
            margin: 0;
        }

        .card-body-cafe {
            padding: 24px;
        }

        /* ─── Buttons ─── */
        .btn-espresso {
            background: var(--espresso);
            color: var(--gold);
            border: none;
            border-radius: 8px;
            padding: 9px 20px;
            font-size: .85rem;
            font-weight: 600;
            letter-spacing: .3px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all .2s;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-espresso:hover {
            background: var(--roast);
            color: var(--gold);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(26, 14, 5, .2);
        }

        .btn-gold {
            background: var(--gold);
            color: var(--espresso);
            border: none;
            border-radius: 8px;
            padding: 9px 20px;
            font-size: .85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all .2s;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-gold:hover {
            background: var(--caramel);
            color: var(--espresso);
            transform: translateY(-1px);
        }

        .btn-outline-cafe {
            background: transparent;
            color: var(--brown);
            border: 1.5px solid rgba(107, 63, 31, .25);
            border-radius: 8px;
            padding: 8px 18px;
            font-size: .85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all .2s;
            text-decoration: none;
        }

        .btn-outline-cafe:hover {
            border-color: var(--brown);
            background: var(--latte);
            color: var(--espresso);
        }

        .btn-danger-cafe {
            background: #fef2f2;
            color: #dc2626;
            border: 1.5px solid #fecaca;
            border-radius: 8px;
            padding: 7px 14px;
            font-size: .82rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all .2s;
            cursor: pointer;
        }

        .btn-danger-cafe:hover {
            background: #dc2626;
            color: #fff;
            border-color: #dc2626;
        }

        /* ─── Table ─── */
        .table-cafe {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: .875rem;
        }

        .table-cafe thead th {
            background: var(--latte);
            color: var(--brown);
            font-weight: 600;
            font-size: .75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 13px 16px;
            border-bottom: 2px solid rgba(196, 127, 46, .2);
            white-space: nowrap;
        }

        .table-cafe thead th:first-child {
            border-radius: 10px 0 0 0;
        }

        .table-cafe thead th:last-child {
            border-radius: 0 10px 0 0;
        }

        .table-cafe tbody tr {
            transition: background .15s;
            border-bottom: 1px solid rgba(26, 14, 5, .05);
        }

        .table-cafe tbody tr:hover {
            background: var(--cream);
        }

        .table-cafe tbody td {
            padding: 14px 16px;
            color: var(--espresso);
            vertical-align: middle;
        }

        /* ─── Badges ─── */
        .badge-cafe {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .5px;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-available {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-occupied {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-active {
            background: #dbeafe;
            color: #1e40af;
        }

        /* ─── Forms ─── */
        .form-label-cafe {
            font-size: .8rem;
            font-weight: 600;
            color: var(--brown);
            letter-spacing: .3px;
            text-transform: uppercase;
            margin-bottom: 6px;
            display: block;
        }

        .form-control-cafe,
        .form-select-cafe {
            background: var(--cream);
            border: 1.5px solid rgba(107, 63, 31, .18);
            border-radius: 9px;
            padding: 10px 14px;
            font-size: .875rem;
            color: var(--espresso);
            width: 100%;
            font-family: 'DM Sans', sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control-cafe:focus,
        .form-select-cafe:focus {
            outline: none;
            border-color: var(--caramel);
            box-shadow: 0 0 0 3px rgba(196, 127, 46, .12);
            background: #fff;
        }

        .form-control-cafe.is-invalid {
            border-color: #dc2626;
        }

        .invalid-feedback-cafe {
            color: #dc2626;
            font-size: .78rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ─── Alerts ─── */
        .alert-cafe-success {
            background: linear-gradient(135deg, #d1fae5, #ecfdf5);
            border: 1px solid #6ee7b7;
            border-radius: 10px;
            padding: 14px 18px;
            color: #065f46;
            font-size: .875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .alert-cafe-error {
            background: linear-gradient(135deg, #fee2e2, #fef2f2);
            border: 1px solid #fca5a5;
            border-radius: 10px;
            padding: 14px 18px;
            color: #991b1b;
            font-size: .875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        /* ─── Stats cards ─── */
        .stat-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 22px 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(26, 14, 5, .06);
            display: flex;
            align-items: center;
            gap: 18px;
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-icon.espresso-bg {
            background: rgba(26, 14, 5, .08);
        }

        .stat-icon.gold-bg {
            background: rgba(232, 168, 56, .15);
        }

        .stat-icon.green-bg {
            background: rgba(16, 185, 129, .12);
        }

        .stat-icon.blue-bg {
            background: rgba(59, 130, 246, .12);
        }

        .stat-label {
            font-size: .75rem;
            font-weight: 600;
            color: rgba(26, 14, 5, .4);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-value {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem;
            font-weight: 700;
            color: var(--espresso);
            line-height: 1;
            margin-top: 4px;
        }

        /* ─── Responsive ─── */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-wrap {
                margin-left: 0;
            }
        }

        /* ─── Scrollbar ─── */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(107, 63, 31, .25);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 63, 31, .45);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: rgba(26, 14, 5, .35);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 12px;
            display: block;
            opacity: .4;
        }

        .empty-state p {
            font-size: .95rem;
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">☕</div>
            <h1>CaféManager</h1>
            <p>Quản lý quán cafe</p>
        </div>

        <div class="sidebar-nav">
            @auth
            @if(auth()->user()->role === 'admin')
            <div class="nav-section-label">Tổng quan</div>
            <a href="/admin" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>

            <div class="nav-section-label">Quản lý</div>
            <a href="{{ route('admin.categories.index') }}"
                class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                <i class="bi bi-bookmark-fill"></i> Danh mục
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                <i class="bi bi-cup-hot-fill"></i> Sản phẩm
            </a>

            <a href="{{ route('admin.tables.index') }}"
                class="nav-link {{ request()->is('admin/tables*') ? 'active' : '' }}">
                <i class="bi bi-layout-three-columns"></i> Bàn
            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Đơn hàng
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Người dùng
            </a>

            @elseif(auth()->user()->role === 'staff')
            <a href="/staff" class="nav-link {{ request()->is('staff') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            <a href="{{ route('staff.orders.index') }}"
                class="nav-link {{ request()->is('staff/orders*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Đơn hàng
            </a>

            @elseif(auth()->user()->role === 'shipper')
            <a href="/shipper" class="nav-link {{ request()->is('shipper') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            @endif
            @endauth
        </div>

        <div class="sidebar-footer">
            @auth
            <div class="user-chip">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->role }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin-left:auto">
                    @csrf
                    <button type="submit" class="btn-logout" title="Đăng xuất">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

    <!-- Main -->
    <div class="main-wrap">
        <!-- Topbar -->
        <div class="topbar">
            <div>
                <h2>@yield('title', 'Dashboard')</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span style="font-size:.8rem;color:rgba(26,14,5,.4);">
                    <i class="bi bi-clock me-1"></i>{{ now()->format('H:i · d/m/Y') }}
                </span>
            </div>
        </div>

        <!-- Content -->
        <div class="content-area">
            @if(session('success'))
            <div class="alert-cafe-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert-cafe-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(formId) {
            if (confirm('Bạn có chắc chắn muốn xoá không? Hành động này không thể hoàn tác.')) {
                document.getElementById(formId).submit();
            }
        }
    </script>
    @stack('scripts')
</body>

</html>