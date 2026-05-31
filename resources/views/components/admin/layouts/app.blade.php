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
        $adminUser = auth()->user();
        $navItems = [
            ['permission' => 'dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'label' => 'الرئيسية', 'icon' => 'fa-solid fa-house'],
            ['permission' => 'services', 'route' => 'admin.services.index', 'active' => 'admin.services.*', 'label' => 'الخدمات', 'icon' => 'fa-solid fa-scale-balanced'],
            ['permission' => 'lawyers', 'route' => 'admin.lawyers.index', 'active' => 'admin.lawyers.*', 'label' => 'المحامون', 'icon' => 'fa-solid fa-user-tie'],
            ['permission' => 'faqs', 'route' => 'admin.faqs.index', 'active' => 'admin.faqs.*', 'label' => 'الأسئلة الشائعة', 'icon' => 'fa-solid fa-circle-question'],
            ['permission' => 'contact_requests', 'route' => 'admin.contact-requests.index', 'active' => 'admin.contact-requests.*', 'label' => 'طلبات التواصل', 'icon' => 'fa-solid fa-envelope-open-text'],
            ['permission' => 'consultations', 'route' => 'admin.consultations.index', 'active' => 'admin.consultations.*', 'label' => 'الاستشارات', 'icon' => 'fa-solid fa-calendar-check'],
            ['permission' => 'blog_posts', 'route' => 'admin.blog-posts.index', 'active' => 'admin.blog-posts.*', 'label' => 'المدونة', 'icon' => 'fa-solid fa-newspaper'],
            ['permission' => 'blog_categories', 'route' => 'admin.blog-categories.index', 'active' => 'admin.blog-categories.*', 'label' => 'تصنيفات المدونة', 'icon' => 'fa-solid fa-folder-tree'],
            ['permission' => 'blog_tags', 'route' => 'admin.blog-tags.index', 'active' => 'admin.blog-tags.*', 'label' => 'وسوم المدونة', 'icon' => 'fa-solid fa-tags'],
            ['permission' => 'clients', 'route' => 'admin.clients.index', 'active' => 'admin.clients.*', 'label' => 'الجهات الموثوقة', 'icon' => 'fa-solid fa-handshake'],
            ['permission' => 'training_courses', 'route' => 'admin.training-courses.index', 'active' => 'admin.training-courses.*', 'label' => 'الدورات التدريبية', 'icon' => 'fa-solid fa-chalkboard-user'],
            ['permission' => 'legal_library', 'route' => 'admin.legal-library.index', 'active' => 'admin.legal-library.*', 'label' => 'المكتبة القانونية', 'icon' => 'fa-solid fa-book-open'],
            ['permission' => 'users', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'label' => 'المستخدمون', 'icon' => 'fa-solid fa-users-gear'],
            ['permission' => 'roles', 'route' => 'admin.roles.index', 'active' => 'admin.roles.*', 'label' => 'الأدوار والصلاحيات', 'icon' => 'fa-solid fa-user-shield'],
            ['permission' => 'profile', 'route' => 'admin.profile.edit', 'active' => 'admin.profile.*', 'label' => 'البروفايل', 'icon' => 'fa-solid fa-id-card'],
            ['permission' => 'seo', 'route' => 'admin.seo.index', 'active' => 'admin.seo.*', 'label' => 'SEO', 'icon' => 'fa-solid fa-magnifying-glass-chart'],
            ['permission' => 'settings', 'route' => 'admin.settings.edit', 'active' => 'admin.settings.*', 'label' => 'إعدادات الموقع', 'icon' => 'fa-solid fa-gear'],
        ];
        $firstAllowedRoute = collect($navItems)->first(fn ($item) => $adminUser?->canAccessAdmin($item['permission']))['route'] ?? 'home';
    @endphp
    <div class="admin-shell">
        <header class="admin-mobile-header">
            <a class="admin-mobile-brand" href="{{ route($firstAllowedRoute) }}">
                <img src="{{ $adminLogoUrl }}" alt="{{ $adminSiteName }}">
                <span><strong>لوحة التحكم</strong><small>المتحدة للمحاماة</small></span>
            </a>
            <button class="admin-menu-btn" type="button" aria-label="القائمة" aria-expanded="false" aria-controls="adminNav">
                <i class="fa-solid fa-bars"></i>
            </button>
        </header>

        <aside class="admin-sidebar" id="adminNav">
            <a class="admin-brand" href="{{ route($firstAllowedRoute) }}">
                <img src="{{ $adminLogoUrl }}" alt="{{ $adminSiteName }}">
                <span><strong>لوحة التحكم</strong><span>المتحدة للمحاماة</span></span>
            </a>

            <nav class="admin-nav">
                @foreach ($navItems as $item)
                    @if ($adminUser?->canAccessAdmin($item['permission']))
                        <a class="{{ request()->routeIs($item['active']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                            {{ $item['label'] }} <span><i class="{{ $item['icon'] }}"></i></span>
                        </a>
                    @endif
                @endforeach
                <a href="{{ route('home') }}" target="_blank">عرض الموقع <span><i class="fa-solid fa-arrow-up-right-from-square"></i></span></a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-button" type="submit">تسجيل الخروج <span><i class="fa-solid fa-right-from-bracket"></i></span></button>
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
                adminMenuButton.innerHTML = open ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
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
