<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $posts = BlogPost::published()
            ->with(['category', 'author', 'tags'])
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('excerpt', 'like', '%'.$request->q.'%')
                    ->orWhere('content', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($q) => $q->where('slug', $request->category)))
            ->when($request->filled('tag'), fn ($query) => $query->whereHas('tags', fn ($q) => $q->where('slug', $request->tag)))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('website.blog.index', [
            'posts' => $posts,
            'categories' => BlogCategory::where('is_active', true)->orderBy('sort_order')->get(),
            'tags' => BlogTag::whereHas('posts', fn ($query) => $query->published())->orderBy('name')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()
            ->with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();

        $post->increment('views_count');
        $post->refresh()->load(['category', 'author', 'tags']);

        $relatedPosts = BlogPost::published()
            ->whereKeyNot($post->id)
            ->when($post->blog_category_id, fn ($query) => $query->where('blog_category_id', $post->blog_category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('website.blog.show', compact('post', 'relatedPosts'));
    }
}
