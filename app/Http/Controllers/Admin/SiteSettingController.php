<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function edit()
    {
        SiteSetting::ensureDefaults();

        $settings = collect(SiteSetting::defaults())->map(function (array $meta, string $key) {
            return $meta + [
                'key' => $key,
                'value' => SiteSetting::getValue($key, $meta['value'] ?? null),
            ];
        })->groupBy('group');

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        SiteSetting::ensureDefaults();

        $rules = [
            'site_logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ];

        foreach (SiteSetting::defaults() as $key => $meta) {
            $rules[$key] = match ($meta['type']) {
                'email' => ['nullable', 'email', 'max:255'],
                'url' => ['nullable', 'url', 'max:1000'],
                'textarea' => ['nullable', 'string', 'max:5000'],
                default => ['nullable', 'string', 'max:1000'],
            };
        }

        $validated = $request->validate($rules, [
            'contact_email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
            '*.url' => 'يرجى إدخال رابط صحيح يبدأ بـ https:// أو http://.',
            'site_logo_file.image' => 'ملف الشعار يجب أن يكون صورة.',
            'site_logo_file.max' => 'حجم الشعار يجب ألا يتجاوز 2MB.',
        ]);

        if ($request->hasFile('site_logo_file')) {
            $path = $request->file('site_logo_file')->store('settings/logos', 'public');
            $validated['site_logo'] = 'storage/'.$path;
        }

        foreach (SiteSetting::defaults() as $key => $meta) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $validated[$key] ?? '',
                    'type' => $meta['type'],
                    'group' => $meta['group'],
                    'label' => $meta['label'],
                ]
            );
        }

        SiteSetting::forgetCache();

        return back()->with('success', 'تم حفظ إعدادات الموقع وتحديث الواجهة بنجاح.');
    }
}
