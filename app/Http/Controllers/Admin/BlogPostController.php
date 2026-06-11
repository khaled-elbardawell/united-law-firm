<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $posts = BlogPost::query()
            ->with(['category', 'author'])
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('slug', 'like', '%'.$request->q.'%')
                    ->orWhere('excerpt', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('category'), fn ($query) => $query->where('blog_category_id', $request->category))
            ->latest('published_at')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.blog-posts.index', [
            'posts' => $posts,
            'categories' => BlogCategory::orderBy('sort_order')->get(),
            'trashCount' => BlogPost::onlyTrashed()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.blog-posts.form', [
            'post' => new BlogPost(['status' => 'draft', 'published_at' => now()]),
            'categories' => BlogCategory::where('is_active', true)->orderBy('sort_order')->get(),
            'tags' => BlogTag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);
        $data['user_id'] = auth()->id();

        $post = BlogPost::create($data);
        $post->tags()->sync($tagIds);

        return redirect()->route('admin.blog-posts.index')->with('success', 'تم نشر المقال أو حفظه بنجاح.');
    }

    public function edit(BlogPost $blogPost)
    {
        return view('admin.blog-posts.form', [
            'post' => $blogPost->load('tags'),
            'categories' => BlogCategory::where('is_active', true)->orderBy('sort_order')->get(),
            'tags' => BlogTag::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $data = $this->validated($request, $blogPost);
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);

        $blogPost->update($data);
        $blogPost->tags()->sync($tagIds);

        return redirect()->route('admin.blog-posts.index')->with('success', 'تم تحديث المقال بنجاح.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();

        return back()->with('success', 'تم نقل المقال إلى السلة.');
    }

    public function restore(int $post)
    {
        BlogPost::onlyTrashed()->findOrFail($post)->restore();

        return back()->with('success', 'تم استرجاع المقال بنجاح.');
    }

    public function forceDelete(int $post)
    {
        BlogPost::onlyTrashed()->findOrFail($post)->forceDelete();

        return back()->with('success', 'تم حذف المقال نهائيا.');
    }

    private function validated(Request $request, ?BlogPost $post = null): array
    {
        $this->normalizeSlugInput($request);

        $data = $request->validate([
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:blog_posts,slug,'.($post?->id ?? 'NULL')],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:blog_tags,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'url', 'max:1000'],
            'is_indexable' => ['nullable', 'boolean'],
        ], [
            'slug.regex' => 'الرابط يجب أن يحتوي أحرفاً إنجليزية صغيرة أو أرقاماً وشرطة (-) فقط، مثل contract-disputes.',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']) ?: Str::random(8);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $data['is_indexable'] = $request->boolean('is_indexable', true);

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('blog', 'public');
            $data['featured_image'] = 'storage/'.$path;
        } elseif ($post?->featured_image) {
            $data['featured_image'] = $post->featured_image;
        }

        unset($data['featured_image_file']);

        return $data;
    }

    private function normalizeSlugInput(Request $request): void
    {
        if ($request->has('slug')) {
            $request->merge(['slug' => Str::lower(trim((string) $request->input('slug')))]);
        }
    }
}
