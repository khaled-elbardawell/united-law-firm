<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Lawyer;
use App\Models\SeoSetting;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'admin@united-law.test',
        ], [
            'name' => 'مدير الموقع',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        foreach ([
            ['title' => 'القضايا التجارية', 'summary' => 'حلول قانونية للشركات والعقود والنزاعات التجارية.', 'icon' => 'fa-solid fa-briefcase', 'sort_order' => 1],
            ['title' => 'القضايا المدنية', 'summary' => 'تمثيل قانوني في التعويضات والعقارات والمطالبات المالية.', 'icon' => 'fa-solid fa-scale-balanced', 'sort_order' => 2],
            ['title' => 'الأحوال الشخصية', 'summary' => 'رعاية دقيقة لقضايا الأسرة والطلاق والنفقة والحضانة والميراث.', 'icon' => 'fa-solid fa-people-roof', 'sort_order' => 3],
        ] as $service) {
            Service::firstOrCreate(
                ['title' => $service['title']],
                $service + ['slug' => str()->slug($service['title']) ?: uniqid('service-')]
            );
        }

        Lawyer::firstOrCreate(['name' => 'أ. أحمد العدلي'], [
            'position' => 'محام ومستشار قانوني',
            'specialty' => 'قضايا الشركات',
            'bio' => 'متخصص في قضايا الشركات والنزاعات التجارية وصياغة العقود.',
            'tags' => ['استشارات', 'ترافع', 'عقود تجارية'],
            'sort_order' => 1,
        ]);

        Faq::firstOrCreate(['question' => 'كيف يمكنني حجز استشارة قانونية؟'], [
            'answer' => 'يمكنك حجز الاستشارة من صفحة احجز استشارة أو عبر التواصل المباشر مع المكتب.',
            'sort_order' => 1,
        ]);

        foreach ([
            'home' => 'الرئيسية',
            'about' => 'من نحن',
            'services' => 'الخدمات',
            'lawyers' => 'المحامون',
            'faq' => 'الأسئلة الشائعة',
            'contact' => 'اتصل بنا',
            'ticket' => 'الاستشارات',
        ] as $key => $name) {
            SeoSetting::firstOrCreate(['page_key' => $key], [
                'page_name' => $name,
                'title' => $name.' - '.config('app.name'),
                'description' => 'مكتب المتحدة للمحاماة والاستشارات القانونية يقدم خدمات قانونية احترافية بسرية عالية.',
                'keywords' => 'محامي, استشارات قانونية, مكتب محاماة',
            ]);
        }
        SiteSetting::ensureDefaults();
    }
}
