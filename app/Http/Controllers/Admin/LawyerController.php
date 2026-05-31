<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LawyerController extends Controller
{
    public function index(Request $request)
    {
        $lawyers = Lawyer::query()
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('position', 'like', '%'.$request->q.'%')
                    ->orWhere('specialty', 'like', '%'.$request->q.'%')
                    ->orWhere('bar_number', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('active'), fn ($query) => $query->where('is_active', $request->active))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.lawyers.index', [
            'lawyers' => $lawyers,
            'trashCount' => Lawyer::onlyTrashed()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.lawyers.form', ['lawyer' => new Lawyer()]);
    }

    public function store(Request $request)
    {
        Lawyer::create($this->validated($request));

        return redirect()->route('admin.lawyers.index')->with('success', 'تمت إضافة المحامي بنجاح.');
    }

    public function edit(Lawyer $lawyer)
    {
        return view('admin.lawyers.form', compact('lawyer'));
    }

    public function update(Request $request, Lawyer $lawyer)
    {
        $lawyer->update($this->validated($request, $lawyer));

        return redirect()->route('admin.lawyers.index')->with('success', 'تم تحديث بيانات المحامي.');
    }

    public function destroy(Lawyer $lawyer)
    {
        $lawyer->delete();

        return back()->with('success', 'تم نقل المحامي إلى سلة المحذوفات.');
    }

    public function restore(int $lawyer)
    {
        Lawyer::onlyTrashed()->findOrFail($lawyer)->restore();

        return back()->with('success', 'تم استرجاع المحامي بنجاح.');
    }

    public function forceDelete(int $lawyer)
    {
        Lawyer::onlyTrashed()->findOrFail($lawyer)->forceDelete();

        return back()->with('success', 'تم حذف المحامي نهائياً.');
    }

    private function validated(Request $request, ?Lawyer $lawyer = null): array
    {
        $this->normalizeUrlInputs($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'bio' => ['required', 'string', 'max:1500'],
            'professional_summary' => ['nullable', 'string', 'max:5000'],
            'bar_number' => ['nullable', 'string', 'max:255'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:80'],
            'education' => ['nullable', 'string', 'max:5000'],
            'experience' => ['nullable', 'string', 'max:8000'],
            'certifications' => ['nullable', 'string', 'max:5000'],
            'languages' => ['nullable', 'string', 'max:3000'],
            'memberships' => ['nullable', 'string', 'max:5000'],
            'awards' => ['nullable', 'string', 'max:5000'],
            'court_admissions' => ['nullable', 'string', 'max:5000'],
            'tags' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:80'],
            'linkedin_url' => ['nullable', 'url', 'max:1000'],
            'website_url' => ['nullable', 'url', 'max:1000'],
            'photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cv_file_upload' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'photo_file.image' => 'ملف صورة المحامي يجب أن يكون صورة.',
            'photo_file.mimes' => 'صيغة الصورة يجب أن تكون JPG أو PNG أو WEBP.',
            'photo_file.max' => 'حجم صورة المحامي يجب ألا يتجاوز 2MB.',
            'cv_file_upload.mimes' => 'ملف السيرة الذاتية يجب أن يكون PDF أو Word.',
            'cv_file_upload.max' => 'حجم ملف السيرة الذاتية يجب ألا يتجاوز 5MB.',
            '*.url' => 'يرجى إدخال رابط صحيح. يمكنك كتابة example.com وسنضيف https تلقائياً.',
        ]);

        if ($request->hasFile('photo_file')) {
            $path = $request->file('photo_file')->store('lawyers', 'public');
            $data['photo'] = 'storage/'.$path;
        } elseif ($lawyer?->photo) {
            $data['photo'] = $lawyer->photo;
        }

        if ($request->hasFile('cv_file_upload')) {
            $path = $request->file('cv_file_upload')->store('lawyers/cv', 'public');
            $data['cv_file'] = 'storage/'.$path;
        } elseif ($lawyer?->cv_file) {
            $data['cv_file'] = $lawyer->cv_file;
        }

        unset($data['photo_file'], $data['cv_file_upload']);

        $data['tags'] = $this->commaListToArray($data['tags'] ?? null);

        foreach (['education', 'experience', 'certifications', 'languages', 'memberships', 'awards', 'court_admissions'] as $field) {
            $data[$field] = $this->linesToArray($data[$field] ?? null);
        }

        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $lawyer);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function commaListToArray(?string $value): array
    {
        return collect(explode(',', (string) $value))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();
    }

    private function linesToArray(?string $value): array
    {
        return collect(preg_split('/\R/u', (string) $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function uniqueSlug(string $value, ?Lawyer $lawyer = null): string
    {
        $base = Str::slug($value) ?: 'lawyer';
        $slug = $base;
        $counter = 2;

        while (Lawyer::withTrashed()
            ->where('slug', $slug)
            ->when($lawyer?->exists, fn ($query) => $query->whereKeyNot($lawyer->getKey()))
            ->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    private function normalizeUrlInputs(Request $request): void
    {
        $updates = [];

        foreach (['linkedin_url', 'website_url'] as $key) {
            if (! $request->filled($key)) {
                continue;
            }

            $value = trim((string) $request->input($key));

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
