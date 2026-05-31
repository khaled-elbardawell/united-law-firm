<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\LegalLibraryItem;
use Illuminate\Http\Request;

class LegalLibraryController extends Controller
{
    public function index()
    {
        $categories = collect(LegalLibraryItem::CATEGORIES)->map(function (string $label, string $key) {
            return [
                'key' => $key,
                'label' => $label,
                'route' => route('legal-library.category', $key === LegalLibraryItem::CATEGORY_LAWS ? 'laws' : 'judicial-decisions'),
                'count' => LegalLibraryItem::published()->where('category', $key)->count(),
                'icon' => $key === LegalLibraryItem::CATEGORY_LAWS ? 'fa-solid fa-scale-balanced' : 'fa-solid fa-gavel',
            ];
        });

        return view('website.legal-library.index', compact('categories'));
    }

    public function category(string $category)
    {
        $categoryKey = $this->resolveCategory($category);
        abort_unless($categoryKey, 404);

        $items = LegalLibraryItem::published()
            ->where('category', $categoryKey)
            ->orderBy('sort_order')
            ->latest('published_at')
            ->paginate(9);

        return view('website.legal-library.category', [
            'items' => $items,
            'category' => $categoryKey,
            'categoryLabel' => LegalLibraryItem::CATEGORIES[$categoryKey],
            'categorySlug' => $category,
        ]);
    }

    public function show(string $category, LegalLibraryItem $item)
    {
        $categoryKey = $this->resolveCategory($category);

        abort_unless($categoryKey && $item->category === $categoryKey && $item->is_active, 404);

        return view('website.legal-library.show', compact('item', 'category'));
    }

    public function search(Request $request)
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
        ]);

        $term = trim((string) ($data['q'] ?? ''));

        $results = LegalLibraryItem::published()
            ->when($term !== '', fn ($query) => $query->where(function ($query) use ($term) {
                $query->where('title', 'like', '%'.$term.'%')
                    ->orWhere('short_description', 'like', '%'.$term.'%')
                    ->orWhere('content', 'like', '%'.$term.'%');
            }))
            ->orderBy('sort_order')
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('website.legal-library.search', compact('results', 'term'));
    }

    private function resolveCategory(string $category): ?string
    {
        return match ($category) {
            'laws' => LegalLibraryItem::CATEGORY_LAWS,
            'judicial-decisions' => LegalLibraryItem::CATEGORY_JUDICIAL_DECISIONS,
            default => null,
        };
    }
}
