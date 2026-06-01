<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول - {{ config('app.name') }}</title>

    @php
        $logo = \App\Models\SiteSetting::getValue('site_logo', 'assets/logo.png');
        $logoUrl = str_starts_with($logo, 'http') ? $logo : asset($logo);
    @endphp

    <link rel="icon" href="{{ $logoUrl }}" sizes="32x32" />

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/admin.css'])
</head>

<body class="auth-body">
    <main class="auth-wrap">
        <section class="admin-card auth-card">
            <div class="admin-brand" style="background:#07131f;border-radius:8px;margin-bottom:22px;">
                <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}">
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
