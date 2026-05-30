<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap">
    @vite(['resources/css/admin.css'])
</head>
<body class="auth-body">
<main class="auth-wrap">
    <section class="admin-card auth-card">
        <div class="admin-brand" style="background:#07131f;border-radius:8px;margin-bottom:22px;">
            <img src="{{ asset('assets/logo.png') }}" alt="{{ config('app.name') }}">
            <span><strong>دخول لوحة التحكم</strong><span>المتحدة للمحاماة</span></span>
        </div>

        @if ($errors->any())
            <div class="error-list">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="field">
                <label for="email">البريد الإلكتروني</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="field">
                <label for="password">كلمة المرور</label>
                <input id="password" name="password" type="password" required>
            </div>
            <label class="check-field" style="margin-bottom:18px;">
                <input type="checkbox" name="remember" value="1"> تذكرني
            </label>
            <button class="btn-admin btn-gold" type="submit" style="width:100%;">تسجيل الدخول</button>
        </form>
    </section>
</main>
</body>
</html>
