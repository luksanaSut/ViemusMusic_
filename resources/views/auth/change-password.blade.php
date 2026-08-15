<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เปลี่ยนรหัสผ่าน — VIEMUS</title>
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

        .card-box {
            background: #fff;
            border-radius: 18px;
            padding: 2.4rem;
            max-width: 420px;
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
            font-size: 1.2rem;
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

        .btn-save {
            background: #1f3350;
            border-color: #1f3350;
        }

        .btn-save:hover {
            background: #13233a;
            border-color: #13233a;
        }
    </style>
</head>

<body>
    <div class="card-box">
        <div class="brand-mark">V</div>
        <h1>เปลี่ยนรหัสผ่าน</h1>
        <div class="sub">
            @if ($isForced)
                <i class="bi bi-shield-lock text-warning"></i> บัญชีนี้ต้องเปลี่ยนรหัสผ่านก่อนใช้งานระบบ
            @else
                ตั้งรหัสผ่านใหม่สำหรับบัญชีของคุณ
            @endif
        </div>

        @if (session('error'))
            <div class="alert alert-danger small">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">รหัสผ่านปัจจุบัน</label>
                <input type="password" name="current_password" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">รหัสผ่านใหม่</label>
                <input type="password" name="password" class="form-control" required minlength="8">
                <small class="text-muted">อย่างน้อย 8 ตัวอักษร</small>
            </div>
            <div class="mb-3">
                <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button class="btn btn-save btn-primary w-100 text-white"><i class="bi bi-check-lg"></i>
                บันทึกรหัสผ่านใหม่</button>
        </form>

        @unless ($isForced)
            <div class="text-center mt-3">
                <a href="{{ route('dashboard') }}" class="small text-muted">กลับไปหน้าหลัก</a>
            </div>
        @endunless
    </div>
</body>

</html>
