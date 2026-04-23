<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập — CaféManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --espresso: #1a0e05;
            --caramel: #c47f2e;
            --gold: #e8a838;
            --cream: #fdf6ee;
            --latte: #f5e6d0;
            --brown: #6b3f1f;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--espresso);
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        .login-left {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(155deg, #2d1a0e 0%, #1a0e05 60%, #0d0702 100%);
        }

        .login-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 30% 20%, rgba(232, 168, 56, .18) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 80%, rgba(196, 127, 46, .12) 0%, transparent 50%);
        }

        .login-left-content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 48px;
        }

        .brand-logo {
            font-size: 5rem;
            line-height: 1;
            margin-bottom: 24px;
            display: block;
            filter: drop-shadow(0 4px 24px rgba(232, 168, 56, .4));
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            font-size: 2.8rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .brand-tagline {
            color: rgba(255, 255, 255, .4);
            font-size: .9rem;
            font-weight: 300;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 8px;
        }

        .decorative-line {
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            margin: 28px auto;
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            color: rgba(255, 255, 255, .5);
            font-size: .85rem;
            padding: 7px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feature-list li i {
            color: var(--caramel);
            font-size: .8rem;
        }

        /* Right panel */
        .login-right {
            width: 440px;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
        }

        .login-form-wrap {
            width: 100%;
        }

        .login-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: var(--espresso);
            font-weight: 700;
            margin-bottom: 6px;
        }

        .login-subtitle {
            color: rgba(26, 14, 5, .45);
            font-size: .875rem;
            margin-bottom: 36px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: .75rem;
            font-weight: 700;
            color: var(--brown);
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(107, 63, 31, .45);
            font-size: .9rem;
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            background: #fff;
            border: 1.5px solid rgba(107, 63, 31, .18);
            border-radius: 10px;
            padding: 12px 14px 12px 40px;
            font-size: .9rem;
            color: var(--espresso);
            font-family: 'DM Sans', sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }

        .input-wrap input:focus {
            outline: none;
            border-color: var(--caramel);
            box-shadow: 0 0 0 3px rgba(196, 127, 46, .12);
        }

        .input-wrap input.is-invalid {
            border-color: #dc2626;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            color: #dc2626;
            font-size: .84rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
        }

        .btn-login {
            width: 100%;
            background: var(--espresso);
            color: var(--gold);
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: .95rem;
            font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            letter-spacing: .5px;
            transition: all .2s;
            margin-top: 8px;
        }

        .btn-login:hover {
            background: #2d1a0e;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26, 14, 5, .25);
        }

        .footer-note {
            text-align: center;
            color: rgba(26, 14, 5, .35);
            font-size: .75rem;
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid rgba(26, 14, 5, .08);
        }

        @media (max-width: 768px) {
            .login-left {
                display: none;
            }

            .login-right {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="login-left">
        <div class="login-left-content">
            <span class="brand-logo">☕</span>
            <div class="brand-name">CaféManager</div>
            <div class="brand-tagline">Hệ thống quản lý quán cafe</div>
            <div class="decorative-line"></div>
            <ul class="feature-list">
                <li><i class="bi bi-check-circle-fill"></i> Quản lý đơn hàng thời gian thực</li>
                <li><i class="bi bi-check-circle-fill"></i> Theo dõi bàn & đặt chỗ</li>
                <li><i class="bi bi-check-circle-fill"></i> Quản lý thực đơn & danh mục</li>
                <li><i class="bi bi-check-circle-fill"></i> Phân quyền admin / staff / shipper</li>
                <li><i class="bi bi-check-circle-fill"></i> Báo cáo doanh thu chi tiết</li>
            </ul>
        </div>
    </div>

    <div class="login-right">
        <div class="login-form-wrap">
            <div class="login-title">Chào mừng trở lại 👋</div>
            <div class="login-subtitle">Đăng nhập để tiếp tục quản lý quán cafe</div>

            @if(session('error'))
            <div class="alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope"></i>
                        <input type="email" name="email" placeholder="your@email.com" value="{{ old('email') }}"
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label>Mật khẩu</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" placeholder="••••••••"
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-arrow-right-circle me-2"></i>Đăng nhập
                </button>
                <div style="text-align:center;margin-top:16px;font-size:.85rem;color:rgba(26,14,5,.5)">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" style="color:var(--caramel);font-weight:600;text-decoration:none">
                        Đăng ký
                    </a>
                </div>
            </form>

            <div class="footer-note">
                © {{ date('Y') }} CaféManager · Phiên bản 1.0
            </div>
        </div>
    </div>
</body>

</html>