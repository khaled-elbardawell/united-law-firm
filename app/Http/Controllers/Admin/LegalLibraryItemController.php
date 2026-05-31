<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LegalLibraryItemRequest;
use App\Models\LegalLibraryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LegalLibraryItemController extends Controller
{
    public function index(Request $request)
    {
        $items = LegalLibraryItem::query()
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('slug', 'like', '%'.$request->q.'%')
                    ->orWhere('short_description', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->category))
            ->when($request->filled('active'), fn ($query) => $query->where('is_active', $request->active))
            ->orderBy('sort_order')
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.legal-library.index', [
            'items' => $items,
            'categories' => LegalLibraryItem::CATEGORIES,
            'trashCount' => LegalLibraryItem::onlyTrashed()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.legal-library.form', [
            'item' => new LegalLibraryItem(),
            'categories' => LegalLibraryItem::CATEGORIES,
        ]);
    }

    public function store(LegalLibraryItemRequest $request)
    {
        LegalLibraryItem::create($this->data($request));

        return redirect()->route('admin.legal-library.index')->with('success', 'تمت إضافة عنصر المكتبة القانونية بنجاح.');
    }

    public function edit(LegalLibraryItem $legalLibrary)
    {
        return view('admin.legal-library.form', [
            'item' => $legalLibrary,
            'categories' => LegalLibraryItem::CATEGORIES,
        ]);
    }

    public function update(LegalLibraryItemRequest $request, LegalLibraryItem $legalLibrary)
    {
        $legalLibrary->update($this->data($request, $legalLibrary));

        return redirect()->route('admin.legal-library.index')->with('success', 'تم تحديث عنصر المكتبة القانونية بنجاح.');
    }

    public function destroy(LegalLibraryItem $legalLibrary)
    {
        $legalLibrary->delete();

        return back()->with('success', 'تم نقل العنصر إلى سلة المحذوفات.');
    }

    public function restore(int $item)
    {
        LegalLibraryItem::onlyTrashed()->findOrFail($item)->restore();

        return back()->with('success', 'تم استرجاع العنصر بنجاح.');
    }

    public function forceDelete(int $item)
    {
        $item = LegalLibraryItem::onlyTrashed()->findOrFail($item);

        if ($item->pdf_path) {
            Storage::disk('public')->delete($item->pdf_path);
        }

        $item->forceDelete();

        return back()->with('success', 'تم حذف العنصر نهائياً.');
    }

    private function data(LegalLibraryItemRequest $request, ?LegalLibraryItem $item = null): array
    {
        $request->merge(['slug' => Str::lower(trim((string) $request->input('slug')))]);

        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']) ?: Str::random(8);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('pdf_file')) {
            $this->ensureStorageLink();

            if ($item?->pdf_path) {
                Storage::disk('public')->delete($item->pdf_path);
            }

            $data['pdf_path'] = $request->file('pdf_file')->store('legal-library', 'public');
        } elseif ($item?->pdf_path) {
            $data['pdf_path'] = $item->pdf_path;
        }

        unset($data['pdf_file']);

        return $data;
    }

    private function ensureStorageLink(): void
    {
        if (! is_link(public_path('storage')) && ! file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }
    }
}
