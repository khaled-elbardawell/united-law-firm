<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class SeoSettingController extends Controller
{
    public function index(Request $request)
    {
        $this->ensurePages();

        $settings = SeoSetting::query()
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('page_name', 'like', '%'.$request->q.'%')
                    ->orWhere('page_key', 'like', '%'.$request->q.'%')
                    ->orWhere('title', 'like', '%'.$request->q.'%')
                    ->orWhere('description', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('indexable'), fn ($query) => $query->where('is_indexable', $request->indexable))
            ->orderBy('id')
            ->get();

        return view('admin.seo.index', compact('settings'));
    }

    public function edit(SeoSetting $seo)
    {
        return view('admin.seo.form', ['setting' => $seo]);
    }

    public function update(Request $request, SeoSetting $seo)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'is_indexable' => ['nullable', 'boolean'],
        ]);

        $data['is_indexable'] = $request->boolean('is_indexable');
        $seo->update($data);

        return redirect()->route('admin.seo.index')->with('success', 'تم تحديث إعدادات SEO.');
    }

    private function ensurePages(): void
    {
        $pages = [
            'home' => 'الرئيسية',
            'about' => 'من نحن',
            'services' => 'الخدمات',
            'lawyers' => 'المحامون',
            'faq' => 'الأسئلة الشائعة',
            'contact' => 'اتصل بنا',
            'ticket' => 'الاستشارات',
        ];

        foreach ($pages as $key => $name) {
            SeoSetting::firstOrCreate(['page_key' => $key], ['page_name' => $name]);
        }
    }
}
