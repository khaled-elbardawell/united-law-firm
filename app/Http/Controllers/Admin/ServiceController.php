<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::query()
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('summary', 'like', '%'.$request->q.'%')
                    ->orWhere('slug', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('active'), fn ($query) => $query->where('is_active', $request->active))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.services.index', [
            'services' => $services,
            'trashCount' => Service::onlyTrashed()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.services.form', ['service' => new Service()]);
    }

    public function store(Request $request)
    {
        Service::create($this->validated($request));

        return redirect()->route('admin.services.index')->with('success', 'تمت إضافة الخدمة بنجاح.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $service->update($this->validated($request, $service));

        return redirect()->route('admin.services.index')->with('success', 'تم تحديث الخدمة بنجاح.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('success', 'تم نقل الخدمة إلى سلة المحذوفات.');
    }

    public function restore(int $service)
    {
        Service::onlyTrashed()->findOrFail($service)->restore();

        return back()->with('success', 'تم استرجاع الخدمة بنجاح.');
    }

    public function forceDelete(int $service)
    {
        Service::onlyTrashed()->findOrFail($service)->forceDelete();

        return back()->with('success', 'تم حذف الخدمة نهائياً.');
    }

    private function validated(Request $request, ?Service $service = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:services,slug,'.($service?->id ?? 'NULL')],
            'icon' => ['nullable', 'string', 'max:80'],
            'summary' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']) ?: Str::random(8);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
