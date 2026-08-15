<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ — VIEMUS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@600;700&family=Sarabun:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(135deg, #1c1a17, #1f3350);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #fff;
            border-radius: 18px;
            padding: 2.4rem;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .3);
        }

        .brand-mark {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1f3350, #4b3d2f);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            margin: 0 auto 1rem;
        }

        h1 {
            font-family: 'Prompt', sans-serif;
            font-size: 1.3rem;
            text-align: center;
            margin-bottom: .3rem;
        }

        .sub {
            text-align: center;
            color: #6b655e;
            font-size: .85rem;
            margin-bottom: 1.6rem;
        }

        .form-label {
            font-size: .82rem;
            font-weight: 600;
        }

        .btn-login {
            background: #1f3350;
            border-color: #1f3350;
        }

        .btn-login:hover {
            background: #13233a;
            border-color: #13233a;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="brand-mark">V</div>
        <h1>VIEMUS International School of Music</h1>
        <div class="sub">เข้าสู่ระบบเพื่อใช้งาน</div>

        @if (session('error'))
            <div class="alert alert-danger small">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success small">{{ session('success') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required
                    autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small" for="remember">จดจำการเข้าสู่ระบบ</label>
            </div>
            <button class="btn btn-login btn-primary w-100 text-white"><i class="bi bi-box-arrow-in-right"></i>
                เข้าสู่ระบบ</button>
        </form>
    </div>
</body>

</html>
