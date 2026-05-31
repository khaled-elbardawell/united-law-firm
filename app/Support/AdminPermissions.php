<?php

namespace App\Support;

class AdminPermissions
{
    public const GROUPS = [
        'general' => [
            'label' => 'عام',
            'permissions' => [
                'dashboard' => 'لوحة القيادة',
                'profile' => 'الملف الشخصي',
            ],
        ],
        'content' => [
            'label' => 'محتوى الموقع',
            'permissions' => [
                'services' => 'الخدمات',
                'lawyers' => 'المحامون',
                'faqs' => 'الأسئلة الشائعة',
                'clients' => 'الجهات الموثوقة',
                'legal_library' => 'المكتبة القانونية',
                'training_courses' => 'الدورات التدريبية',
                'blog_posts' => 'المدونة',
                'blog_categories' => 'تصنيفات المدونة',
                'blog_tags' => 'وسوم المدونة',
            ],
        ],
        'requests' => [
            'label' => 'الطلبات',
            'permissions' => [
                'contact_requests' => 'طلبات التواصل',
                'consultations' => 'الاستشارات',
            ],
        ],
        'system' => [
            'label' => 'النظام',
            'permissions' => [
                'users' => 'المستخدمون',
                'roles' => 'الأدوار والصلاحيات',
                'seo' => 'إعدادات SEO',
                'settings' => 'إعدادات الموقع',
            ],
        ],
    ];

    public const ROLE_PRESETS = [
        'admin' => ['*'],
        'manager' => [
            'dashboard',
            'profile',
            'services',
            'lawyers',
            'faqs',
            'clients',
            'legal_library',
            'training_courses',
            'blog_posts',
            'blog_categories',
            'blog_tags',
            'contact_requests',
            'consultations',
            'seo',
        ],
        'editor' => [
            'dashboard',
            'profile',
            'services',
            'lawyers',
            'faqs',
            'clients',
            'legal_library',
            'training_courses',
            'blog_posts',
            'blog_categories',
            'blog_tags',
        ],
    ];

    public static function all(): array
    {
        return collect(self::GROUPS)
            ->flatMap(fn (array $group) => $group['permissions'])
            ->keys()
            ->all();
    }

    public static function groups(): array
    {
        return self::GROUPS;
    }

    public static function presetForRole(?string $role): array
    {
        return self::ROLE_PRESETS[$role ?: 'editor'] ?? self::ROLE_PRESETS['editor'];
    }

    public static function sanitize(array $permissions): array
    {
        if (in_array('*', $permissions, true)) {
            return ['*'];
        }

        return collect($permissions)
            ->intersect(self::all())
            ->values()
            ->all();
    }
}
