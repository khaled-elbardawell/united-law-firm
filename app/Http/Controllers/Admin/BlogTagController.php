<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogTagController extends Controller
{
    public function index(Request $request)
    {
        $tags = BlogTag::query()
            ->withCount('posts')
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('slug', 'like', '%'.$request->q.'%');
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.blog-tags.index', [
            'tags' => $tags,
            'trashCount' => BlogTag::onlyTrashed()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.blog-tags.form', ['tag' => new BlogTag()]);
    }

    public function store(Request $request)
    {
        BlogTag::create($this->validated($request));

        return redirect()->route('admin.blog-tags.index')->with('success', 'تمت إضافة الوسم بنجاح.');
    }

    public function edit(BlogTag $blogTag)
    {
        return view('admin.blog-tags.form', ['tag' => $blogTag]);
    }

    public function update(Request $request, BlogTag $blogTag)
    {
        $blogTag->update($this->validated($request, $blogTag));

        return redirect()->route('admin.blog-tags.index')->with('success', 'تم تحديث الوسم بنجاح.');
    }

    public function destroy(BlogTag $blogTag)
    {
        $blogTag->delete();

        return back()->with('success', 'تم نقل الوسم إلى السلة.');
    }

    public function restore(int $tag)
    {
        BlogTag::onlyTrashed()->findOrFail($tag)->restore();

        return back()->with('success', 'تم استرجاع الوسم بنجاح.');
    }

    public function forceDelete(int $tag)
    {
        BlogTag::onlyTrashed()->findOrFail($tag)->forceDelete();

        return back()->with('success', 'تم حذف الوسم نهائيا.');
    }

    private function validated(Request $request, ?BlogTag $tag = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_tags,slug,'.($tag?->id ?? 'NULL')],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']) ?: Str::random(8);

        return $data;
    }
}
