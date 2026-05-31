<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'لوحة التحكم' }} - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('assets/logo.png') }}" sizes="32x32" />

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/admin.css'])
</head>

<body class="admin-body">
    @php
        $adminLogo = \App\Models\SiteSetting::getValue('site_logo', 'assets/logo.png');
        $adminLogoUrl = str_starts_with($adminLogo, 'http') ? $adminLogo : asset($adminLogo);
        $adminSiteName = \App\Models\SiteSetting::getValue('site_short_name', config('app.name'));
    @endphp
    <div class="admin-shell">
        <header class="admin-mobile-header">
            <a class="admin-mobile-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ $adminLogoUrl }}" alt="{{ $adminSiteName }}">
                <span>
                    <strong>لوحة التحكم</strong>
                    <small>المتحدة للمحاماة</small>
                </span>
            </a>
            <button class="admin-menu-btn" type="button" aria-label="القائمة" aria-expanded="false"
                aria-controls="adminNav">
                <i class="fa-solid fa-bars"></i>
            </button>
        </header>

        <aside class="admin-sidebar" id="adminNav">
            <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ $adminLogoUrl }}" alt="{{ $adminSiteName }}">
                <span><strong>لوحة التحكم</strong><span>المتحدة للمحاماة</span></span>
            </a>

            <nav class="admin-nav">
                <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">الرئيسية <span><i class="fa-solid fa-house"></i></span></a>
                <a class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
                    href="{{ route('admin.services.index') }}">الخدمات <span><i
                            class="fa-solid fa-scale-balanced"></i></span></a>
                <a class="{{ request()->routeIs('admin.lawyers.*') ? 'active' : '' }}"
                    href="{{ route('admin.lawyers.index') }}">المحامون <span><i
                            class="fa-solid fa-user-tie"></i></span></a>
                <a class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}"
                    href="{{ route('admin.faqs.index') }}">الأسئلة الشائعة <span><i
                            class="fa-solid fa-circle-question"></i></span></a>
                <a class="{{ request()->routeIs('admin.contact-requests.*') ? 'active' : '' }}"
                    href="{{ route('admin.contact-requests.index') }}">طلبات التواصل <span><i
                            class="fa-solid fa-envelope-open-text"></i></span></a>
                <a class="{{ request()->routeIs('admin.consultations.*') ? 'active' : '' }}"
                    href="{{ route('admin.consultations.index') }}">الاستشارات <span><i
                            class="fa-solid fa-calendar-check"></i></span></a>
                <a class="{{ request()->routeIs('admin.blog-posts.*') ? 'active' : '' }}"
                    href="{{ route('admin.blog-posts.index') }}">المدونة <span><i
                            class="fa-solid fa-newspaper"></i></span></a>
                <a class="{{ request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}"
                    href="{{ route('admin.blog-categories.index') }}">تصنيفات المدونة <span><i
                            class="fa-solid fa-folder-tree"></i></span></a>
                <a class="{{ request()->routeIs('admin.blog-tags.*') ? 'active' : '' }}"
                    href="{{ route('admin.blog-tags.index') }}">وسوم المدونة <span><i
                            class="fa-solid fa-tags"></i></span></a>
                <a class="{{ request()->routeIs('admin.clients.*') ? 'active' : '' }}"
                    href="{{ route('admin.clients.index') }}">الجهات الموثوقة <span><i
                            class="fa-solid fa-handshake"></i></span></a>
                <a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}">المستخدمون <span><i
                            class="fa-solid fa-users-gear"></i></span></a>
                <a class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"
                    href="{{ route('admin.profile.edit') }}">البروفايل <span><i
                            class="fa-solid fa-id-card"></i></span></a>
                <a class="{{ request()->routeIs('admin.seo.*') ? 'active' : '' }}"
                    href="{{ route('admin.seo.index') }}">SEO <span><i
                            class="fa-solid fa-magnifying-glass-chart"></i></span></a>
                <a class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                    href="{{ route('admin.settings.edit') }}">إعدادات الموقع <span><i
                            class="fa-solid fa-gear"></i></span></a>
                <a href="{{ route('home') }}" target="_blank">عرض الموقع <span><i
                            class="fa-solid fa-arrow-up-right-from-square"></i></span></a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-button" type="submit">تسجيل الخروج <span><i
                                class="fa-solid fa-right-from-bracket"></i></span></button>
                </form>
            </nav>
        </aside>

        <main class="admin-main">
            <header class="admin-topbar">
                <div class="admin-page-title">
                    <h1>{{ $heading ?? 'لوحة التحكم' }}</h1>
                    @isset($description)
                        <p>{{ $description }}</p>
                    @endisset
                </div>
                <div class="actions-row">{{ $actions ?? '' }}</div>
            </header>

            @if (session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="error-list">
                    <strong>يرجى مراجعة الحقول التالية:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </main>
        <div class="admin-mobile-backdrop" hidden></div>
    </div>
    <script>
        const adminMenuButton = document.querySelector('.admin-menu-btn');
        const adminSidebar = document.querySelector('.admin-sidebar');
        const adminBackdrop = document.querySelector('.admin-mobile-backdrop');

        if (adminMenuButton && adminSidebar && adminBackdrop) {
            const setAdminMenu = (open) => {
                adminSidebar.classList.toggle('is-open', open);
                adminMenuButton.setAttribute('aria-expanded', open ? 'true' : 'false');
                adminMenuButton.innerHTML = open ? '<i class="fa-solid fa-xmark"></i>' :
                    '<i class="fa-solid fa-bars"></i>';
                adminBackdrop.hidden = !open;
                document.body.classList.toggle('admin-menu-open', open);
            };

            adminMenuButton.addEventListener('click', () => setAdminMenu(!adminSidebar.classList.contains('is-open')));
            adminBackdrop.addEventListener('click', () => setAdminMenu(false));
            adminSidebar.querySelectorAll('a, button').forEach((item) => item.addEventListener('click', () => {
                if (window.innerWidth <= 980) setAdminMenu(false);
            }));
        }
    </script>
</body>

</html>
