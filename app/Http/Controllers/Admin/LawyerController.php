<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use Illuminate\Http\Request;

class LawyerController extends Controller
{
    public function index(Request $request)
    {
        $lawyers = Lawyer::query()
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('position', 'like', '%'.$request->q.'%')
                    ->orWhere('specialty', 'like', '%'.$request->q.'%');
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
        $lawyer->update($this->validated($request));

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

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'bio' => ['required', 'string', 'max:1500'],
            'tags' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:80'],
            'photo' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['tags'] = collect(explode(',', $data['tags'] ?? ''))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
