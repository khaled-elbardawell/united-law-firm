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
            return array_merge($meta, [
                'key' => $key,
                'value' => SiteSetting::getValue($key, $meta['value'] ?? null),
            ]);
        })->groupBy('group');

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        SiteSetting::ensureDefaults();
        $this->normalizeUrlInputs($request);

        $rules = [
            'site_logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ];

        foreach (SiteSetting::defaults() as $key => $meta) {
            if ($key === 'site_logo') {
                continue;
            }

            $rules[$key] = match ($meta['type']) {
                'email' => ['nullable', 'email', 'max:255'],
                'url' => ['nullable', 'url', 'max:1000'],
                'textarea' => ['nullable', 'string', 'max:5000'],
                default => ['nullable', 'string', 'max:1000'],
            };
        }

        $validated = $request->validate($rules, [
            'contact_email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
            '*.url' => 'يرجى إدخال رابط صحيح. يمكنك كتابته مثل example.com وسنضيف https تلقائيا.',
            'site_logo_file.image' => 'ملف الشعار يجب أن يكون صورة.',
            'site_logo_file.max' => 'حجم الشعار يجب ألا يتجاوز 2MB.',
        ]);

        if ($request->hasFile('site_logo_file')) {
            $path = $request->file('site_logo_file')->store('settings/logos', 'public');
            $validated['site_logo'] = 'storage/'.$path;
        }

        foreach (SiteSetting::defaults() as $key => $meta) {
            $value = $validated[$key] ?? '';

            if ($key === 'site_logo') {
                $value = $validated['site_logo'] ?? SiteSetting::getValue('site_logo', $meta['value']);
            }

            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $meta['type'],
                    'group' => $meta['group'],
                    'label' => $meta['label'],
                ]
            );
        }

        SiteSetting::forgetCache();

        return back()->with('success', 'تم حفظ إعدادات الموقع وتحديث الواجهة بنجاح.');
    }

    private function normalizeUrlInputs(Request $request): void
    {
        $updates = [];

        foreach (SiteSetting::defaults() as $key => $meta) {
            if (($meta['type'] ?? null) !== 'url' || ! $request->filled($key)) {
                continue;
            }

            $value = trim((string) $request->input($key));

            if ($key === 'map_embed_url' && str_contains($value, '<iframe')) {
                preg_match('/src=["\']([^"\']+)["\']/', $value, $matches);
                $value = $matches[1] ?? $value;
            }

            if (! preg_match('/^https?:\/\//i', $value)) {
                $value = 'https://'.ltrim($value, '/');
            }

            $updates[$key] = $value;
        }

        if ($updates !== []) {
            $request->merge($updates);
        }
    }
}
