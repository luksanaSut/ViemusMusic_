<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ — VIEMUS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:wght@500;600;700&family=Sarabun:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --navy-deep: #0d1626;
            --navy: #16233b;
            --navy-soft: #1f3350;
            --card-bg: rgba(24, 38, 62, .72);
            --border-soft: rgba(255, 255, 255, .1);
            --ink-light: #eef1f7;
            --muted: #9aa4b8;
            --gold: #f0c96a;
            --gold-dark: #d9a83e;
            --teal: #6fc6c6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Sarabun', sans-serif;
            color: var(--ink-light);
            background: radial-gradient(circle at 20% 15%, #22365a 0%, transparent 45%),
                radial-gradient(circle at 85% 80%, #1c2c48 0%, transparent 50%),
                linear-gradient(160deg, var(--navy-deep), var(--navy) 55%, var(--navy-deep));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 2rem 1rem;
        }

        body::before {
            content: '';
            position: absolute;
            inset: -10%;
            background-image: repeating-linear-gradient(-4deg,
                    rgba(255, 255, 255, .05) 0,
                    rgba(255, 255, 255, .05) 1px,
                    transparent 1px,
                    transparent 46px);
            pointer-events: none;
        }

        .deco {
            position: absolute;
            pointer-events: none;
            user-select: none;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .deco-note1 {
            top: 16%;
            left: 12%;
            font-size: 2.2rem;
            color: var(--gold);
            opacity: .55;
        }

        .deco-note2 {
            top: 30%;
            right: 14%;
            font-size: 2.6rem;
            color: var(--teal);
            opacity: .55;
            animation-delay: 1.2s;
        }

        .deco-note3 {
            bottom: 14%;
            left: 8%;
            font-size: 1.6rem;
            color: var(--gold);
            opacity: .4;
            animation-delay: 2.1s;
        }

        .deco-clef {
            top: 55%;
            left: 5%;
            font-size: 3.4rem;
            color: rgba(240, 201, 106, .35);
            font-family: 'Times New Roman', serif;
            animation-delay: .6s;
        }

        .deco-eq {
            top: 12%;
            right: 10%;
            display: flex;
            align-items: flex-end;
            gap: 4px;
            height: 30px;
            opacity: .6;
        }

        .deco-eq span {
            width: 5px;
            border-radius: 3px;
            background: linear-gradient(180deg, var(--teal), var(--navy-soft));
        }

        .deco-eq span:nth-child(1) {
            height: 10px;
        }

        .deco-eq span:nth-child(2) {
            height: 20px;
        }

        .deco-eq span:nth-child(3) {
            height: 30px;
        }

        .deco-eq span:nth-child(4) {
            height: 16px;
        }

        .deco-eq span:nth-child(5) {
            height: 24px;
        }

        .deco-piano {
            bottom: 10%;
            right: 8%;
            width: 88px;
            height: 46px;
            background: #f4f0e6;
            border-radius: 6px;
            display: flex;
            overflow: hidden;
            border: 1px solid rgba(240, 201, 106, .5);
            transform: rotate(-6deg);
            opacity: .8;
            animation-delay: 1.8s;
        }

        .deco-piano span {
            flex: 1;
            position: relative;
            border-right: 1px solid #d8d2c2;
        }

        .deco-piano span::before {
            content: '';
            position: absolute;
            top: 0;
            left: 15%;
            width: 70%;
            height: 60%;
            background: #1c1a17;
            border-radius: 0 0 2px 2px;
        }

        .deco-piano span:nth-child(3n)::before {
            display: none;
        }

        .login-card {
            position: relative;
            z-index: 2;
            background: var(--card-bg);
            backdrop-filter: blur(14px);
            border: 1px solid var(--border-soft);
            border-radius: 22px;
            padding: 2.4rem 2.2rem;
            max-width: 430px;
            width: 100%;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .45);
        }

        .brand-mark {
            width: 66px;
            height: 66px;
            margin: 0 auto 1.1rem;
            border-radius: 16px;
            background: linear-gradient(145deg, var(--navy-soft), var(--navy-deep));
            border: 2px solid rgba(255, 255, 255, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: 1.6rem;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(111, 198, 198, .12);
        }

        h1.brand-title {
            font-family: 'Prompt', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin: 0;
            background: linear-gradient(90deg, var(--gold-dark), var(--gold));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .brand-sub {
            text-align: center;
            font-size: .68rem;
            letter-spacing: 2.5px;
            color: var(--teal);
            font-family: 'Prompt', sans-serif;
            margin: .3rem 0 .6rem;
        }

        .tagline {
            text-align: center;
            color: var(--muted);
            font-size: .85rem;
            margin-bottom: 1.8rem;
        }

        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: var(--muted);
        }

        .input-group-text,
        .form-control {
            background: rgba(255, 255, 255, .06);
            border: 1px solid var(--border-soft);
            color: var(--ink-light);
        }

        .form-control::placeholder {
            color: #6d7690;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, .09);
            border-color: var(--teal);
            color: var(--ink-light);
            box-shadow: 0 0 0 .2rem rgba(111, 198, 198, .18);
        }

        .input-group .form-control {
            border-left: 0;
        }

        .input-group-text {
            border-right: 0;
        }

        #togglePassword {
            cursor: pointer;
            border-left: 0;
        }

        .form-check-input {
            background-color: rgba(255, 255, 255, .08);
            border-color: var(--border-soft);
        }

        .form-check-input:checked {
            background-color: var(--gold);
            border-color: var(--gold);
        }

        .form-check-label {
            color: var(--muted);
            font-size: .85rem;
        }

        .btn-login {
            background: linear-gradient(90deg, var(--gold-dark), var(--gold));
            border: none;
            color: #241b06;
            font-weight: 700;
            font-family: 'Prompt', sans-serif;
            padding: .65rem;
            border-radius: 10px;
            transition: .15s ease;
        }

        .btn-login:hover {
            filter: brightness(1.06);
            color: #241b06;
        }

        .alert {
            font-size: .85rem;
            border: none;
        }

        .alert-danger {
            background: rgba(220, 76, 76, .18);
            color: #ffb4b4;
        }

        .alert-success {
            background: rgba(76, 175, 120, .18);
            color: #a9e8c4;
        }
    </style>
</head>

<body>
    <i class="bi bi-music-note-beamed deco deco-note1"></i>
    <i class="bi bi-music-note deco deco-note2"></i>
    <i class="bi bi-music-note deco deco-note3"></i>
    <span class="deco deco-clef">𝄞</span>
    <div class="deco deco-eq"><span></span><span></span><span></span><span></span><span></span></div>
    <div class="deco deco-piano"><span></span><span></span><span></span><span></span><span></span><span></span></div>

    <div class="login-card">
        <div class="brand-mark">V</div>
        <h1 class="brand-title">โรงเรียนดนตรีวีมุส</h1>
        <div class="brand-sub">VIEMUS MUSIC SCHOOL</div>
        <div class="tagline">Creative Music Learning Management</div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                        placeholder="you@example.com" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">รหัสผ่าน</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="••••••••••" required>
                    <span class="input-group-text" id="togglePassword"><i class="bi bi-eye" id="toggleIcon"></i></span>
                </div>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                <label class="form-check-label" for="remember">จดจำการเข้าสู่ระบบ</label>
            </div>
            <button class="btn btn-login w-100"><i class="bi bi-music-note-beamed"></i> เข้าสู่ระบบ</button>
        </form>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.classList.toggle('bi-eye', !show);
            icon.classList.toggle('bi-eye-slash', show);
        });
    </script>
</body>

</html>
