<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = BlogCategory::query()
            ->withCount('posts')
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('slug', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('active'), fn ($query) => $query->where('is_active', $request->active))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.blog-categories.index', [
            'categories' => $categories,
            'trashCount' => BlogCategory::onlyTrashed()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.blog-categories.form', ['category' => new BlogCategory()]);
    }

    public function store(Request $request)
    {
        BlogCategory::create($this->validated($request));

        return redirect()->route('admin.blog-categories.index')->with('success', 'تمت إضافة التصنيف بنجاح.');
    }

    public function edit(BlogCategory $blogCategory)
    {
        return view('admin.blog-categories.form', ['category' => $blogCategory]);
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $blogCategory->update($this->validated($request, $blogCategory));

        return redirect()->route('admin.blog-categories.index')->with('success', 'تم تحديث التصنيف بنجاح.');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return back()->with('success', 'تم نقل التصنيف إلى السلة.');
    }

    public function restore(int $category)
    {
        BlogCategory::onlyTrashed()->findOrFail($category)->restore();

        return back()->with('success', 'تم استرجاع التصنيف بنجاح.');
    }

    public function forceDelete(int $category)
    {
        BlogCategory::onlyTrashed()->findOrFail($category)->forceDelete();

        return back()->with('success', 'تم حذف التصنيف نهائيا.');
    }

    private function validated(Request $request, ?BlogCategory $category = null): array
    {
        $this->normalizeSlugInput($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:blog_categories,slug,'.($category?->id ?? 'NULL')],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'slug.regex' => 'الرابط يجب أن يحتوي أحرفاً إنجليزية صغيرة أو أرقاماً وشرطة (-) فقط، مثل legal-news.',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']) ?: Str::random(8);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function normalizeSlugInput(Request $request): void
    {
        if ($request->has('slug')) {
            $request->merge(['slug' => Str::lower(trim((string) $request->input('slug')))]);
        }
    }
}
