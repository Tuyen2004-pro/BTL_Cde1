<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký — CaféManager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    :root {
        --espresso: #1a0e05;
        --caramel: #c47f2e;
        --gold: #e8a838;
        --cream: #fdf6ee;
        --brown: #6b3f1f;
    }

    body {
        font-family: 'DM Sans', sans-serif;
        background: var(--espresso);
        min-height: 100vh;
        display: flex;
    }

    .login-left {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(155deg, #2d1a0e, #1a0e05);
        color: #fff;
    }

    .login-right {
        width: 440px;
        background: var(--cream);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px 40px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    label {
        font-size: .75rem;
        font-weight: 700;
        color: var(--brown);
    }

    .input-wrap {
        position: relative;
    }

    .input-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
    }

    .input-wrap .toggle-password {
        left: auto;
        right: 12px;
        cursor: pointer;
    }

    input {
        width: 100%;
        padding: 12px 40px;
        border-radius: 10px;
        border: 1px solid #ccc;
    }

    .btn-login {
        width: 100%;
        background: var(--espresso);
        color: var(--gold);
        border: none;
        padding: 14px;
        border-radius: 10px;
        margin-top: 10px;
    }

    .alert-error {
        background: #fee;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
        color: red;
    }
    </style>
</head>

<body>

    <div class="login-left">
        <div>
            <h2>CaféManager ☕</h2>
            <p>Đăng ký để bắt đầu quản lý</p>
        </div>
    </div>

    <div class="login-right">
        <div style="width:100%">

            <h3>Đăng ký</h3>

            @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- NAME -->
                <div class="form-group">
                    <label>Tên</label>
                    <div class="input-wrap">
                        <i class="bi bi-person"></i>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="form-group">
                    <label>Mật khẩu</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock"></i>
                        <input type="password" id="password" name="password" required>
                        <i class="bi bi-eye toggle-password" onclick="togglePassword('password', this)"></i>
                    </div>
                </div>

                <!-- CONFIRM -->
                <div class="form-group">
                    <label>Xác nhận mật khẩu</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock"></i>
                        <input type="password" id="confirm" name="password_confirmation" required>
                        <i class="bi bi-eye toggle-password" onclick="togglePassword('confirm', this)"></i>
                    </div>
                </div>

                <button class="btn-login">Đăng ký</button>

                <div style="text-align:center;margin-top:15px">
                    Đã có tài khoản?
                    <a href="{{ route('login') }}">Đăng nhập</a>
                </div>

            </form>
        </div>
    </div>

    <script>
    function togglePassword(id, el) {
        const input = document.getElementById(id);

        if (input.type === "password") {
            input.type = "text";
            el.classList.remove("bi-eye");
            el.classList.add("bi-eye-slash");
        } else {
            input.type = "password";
            el.classList.remove("bi-eye-slash");
            el.classList.add("bi-eye");
        }
    }
    </script>

</body>

</html>