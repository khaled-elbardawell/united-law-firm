<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
    ];

    protected static ?array $settingsCache = null;

    protected static function booted(): void
    {
        static::saved(fn () => static::forgetCache());
        static::deleted(fn () => static::forgetCache());
    }

    public static function defaults(): array
    {
        return [
            'site_name' => ['value' => 'المتحدة للمحاماة والاستشارات القانونية', 'type' => 'text', 'group' => 'general', 'label' => 'اسم الموقع'],
            'site_short_name' => ['value' => 'المتحدة', 'type' => 'text', 'group' => 'general', 'label' => 'الاسم المختصر'],
            'site_tagline' => ['value' => 'UNITED Legal Firm', 'type' => 'text', 'group' => 'general', 'label' => 'وصف قصير بجانب الشعار'],
            'site_logo' => ['value' => 'assets/logo.png', 'type' => 'image', 'group' => 'general', 'label' => 'الشعار'],
            'footer_description' => ['value' => 'مكتب محاماة واستشارات قانونية يقدم حلولا قانونية راقية للأفراد والشركات، بخبرة عالية وسرية تامة واهتمام بأدق التفاصيل.', 'type' => 'textarea', 'group' => 'general', 'label' => 'وصف الفوتر'],
            'copyright_text' => ['value' => '© 2026 المتحدة للمحاماة والاستشارات القانونية - جميع الحقوق محفوظة', 'type' => 'text', 'group' => 'general', 'label' => 'نص حقوق النشر'],

            'contact_phone' => ['value' => '+970 59 123 4567', 'type' => 'text', 'group' => 'contact', 'label' => 'رقم التواصل'],
            'whatsapp_number' => ['value' => '+970 59 123 4567', 'type' => 'text', 'group' => 'contact', 'label' => 'رقم الواتساب'],
            'contact_email' => ['value' => 'info@united-legal.com', 'type' => 'email', 'group' => 'contact', 'label' => 'إيميل التواصل'],
            'office_address' => ['value' => 'فلسطين - غزة - الشارع العام', 'type' => 'text', 'group' => 'contact', 'label' => 'عنوان المكتب'],
            'map_address' => ['value' => 'فلسطين - قطاع غزة - وسط المدينة', 'type' => 'text', 'group' => 'contact', 'label' => 'العنوان على الخريطة'],
            'map_embed_url' => ['value' => '', 'type' => 'url', 'group' => 'contact', 'label' => 'رابط تضمين الخريطة'],
            'office_frame_label' => ['value' => 'مكتب المتحدة - غزة', 'type' => 'text', 'group' => 'contact', 'label' => 'نص صورة المكتب'],

            'social_linkedin' => ['value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'LinkedIn'],
            'social_facebook' => ['value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Facebook'],
            'social_x' => ['value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'X'],
            'social_instagram' => ['value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Instagram'],

            'hero_title' => ['value' => 'نحمي حقوقك', 'type' => 'text', 'group' => 'home', 'label' => 'عنوان الهيرو'],
            'hero_highlight' => ['value' => 'ونحقق العدالة', 'type' => 'text', 'group' => 'home', 'label' => 'النص المميز في الهيرو'],
            'hero_description' => ['value' => 'نقدم استشارات قانونية وحلولا فعالة لحماية مصالحك وتحقيق أفضل النتائج في مختلف القضايا بخبرة مهنية راسخة.', 'type' => 'textarea', 'group' => 'home', 'label' => 'وصف الهيرو'],
            'cta_title' => ['value' => 'قضيتك تستحق أفضل دفاع قانوني', 'type' => 'text', 'group' => 'home', 'label' => 'عنوان الدعوة لاتخاذ إجراء'],
            'cta_description' => ['value' => 'ابدأ الآن بحجز استشارة قانونية وسرية مع فريق المتحدة المتخصص.', 'type' => 'textarea', 'group' => 'home', 'label' => 'وصف الدعوة لاتخاذ إجراء'],

            'stat_cases_value' => ['value' => '1000+', 'type' => 'text', 'group' => 'stats', 'label' => 'عدد القضايا'],
            'stat_cases_label' => ['value' => 'قضية ناجحة', 'type' => 'text', 'group' => 'stats', 'label' => 'وصف عدد القضايا'],
            'stat_experience_value' => ['value' => '15+', 'type' => 'text', 'group' => 'stats', 'label' => 'سنوات الخبرة'],
            'stat_experience_label' => ['value' => 'سنة من الخبرة', 'type' => 'text', 'group' => 'stats', 'label' => 'وصف سنوات الخبرة'],
            'stat_lawyers_value' => ['value' => '20+', 'type' => 'text', 'group' => 'stats', 'label' => 'عدد المحامين'],
            'stat_lawyers_label' => ['value' => 'محام متخصص', 'type' => 'text', 'group' => 'stats', 'label' => 'وصف عدد المحامين'],
            'stat_success_value' => ['value' => '98%', 'type' => 'text', 'group' => 'stats', 'label' => 'نسبة النجاح'],
            'stat_success_label' => ['value' => 'نسبة النجاح', 'type' => 'text', 'group' => 'stats', 'label' => 'وصف نسبة النجاح'],

            'seo_default_description' => ['value' => 'مكتب المتحدة للمحاماة والاستشارات القانونية يقدم خدمات قانونية احترافية بسرية عالية.', 'type' => 'textarea', 'group' => 'seo', 'label' => 'وصف SEO الافتراضي'],
            'seo_default_keywords' => ['value' => 'محامي, استشارات قانونية, مكتب محاماة', 'type' => 'textarea', 'group' => 'seo', 'label' => 'كلمات SEO الافتراضية'],
            'seo_author' => ['value' => 'المتحدة للمحاماة', 'type' => 'text', 'group' => 'seo', 'label' => 'كاتب SEO'],
        ];
    }

    public static function ensureDefaults(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        foreach (static::defaults() as $key => $setting) {
            static::firstOrCreate(['key' => $key], $setting);
        }

        static::forgetCache();
    }

    public static function allSettings(): array
    {
        if (static::$settingsCache !== null) {
            return static::$settingsCache;
        }

        if (! Schema::hasTable('site_settings')) {
            return static::$settingsCache = [];
        }

        return static::$settingsCache = static::query()->pluck('value', 'key')->all();
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $fallback = static::defaults()[$key]['value'] ?? $default;

        return static::allSettings()[$key] ?? $fallback;
    }

    public static function forgetCache(): void
    {
        static::$settingsCache = null;
    }
}
