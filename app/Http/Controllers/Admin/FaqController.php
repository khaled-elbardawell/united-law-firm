<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $faqs = Faq::query()
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('question', 'like', '%'.$request->q.'%')
                    ->orWhere('answer', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('active'), fn ($query) => $query->where('is_active', $request->active))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.faqs.index', [
            'faqs' => $faqs,
            'trashCount' => Faq::onlyTrashed()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.faqs.form', ['faq' => new Faq()]);
    }

    public function store(Request $request)
    {
        Faq::create($this->validated($request));

        return redirect()->route('admin.faqs.index')->with('success', 'تمت إضافة السؤال.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.form', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $faq->update($this->validated($request));

        return redirect()->route('admin.faqs.index')->with('success', 'تم تحديث السؤال.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'تم نقل السؤال إلى سلة المحذوفات.');
    }

    public function restore(int $faq)
    {
        Faq::onlyTrashed()->findOrFail($faq)->restore();

        return back()->with('success', 'تم استرجاع السؤال بنجاح.');
    }

    public function forceDelete(int $faq)
    {
        Faq::onlyTrashed()->findOrFail($faq)->forceDelete();

        return back()->with('success', 'تم حذف السؤال نهائياً.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
