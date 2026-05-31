<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::query()
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('description', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('active'), fn ($query) => $query->where('is_active', $request->active))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.clients.index', [
            'clients' => $clients,
            'trashCount' => Client::onlyTrashed()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.clients.form', ['client' => new Client()]);
    }

    public function store(Request $request)
    {
        Client::create($this->validated($request));

        return redirect()->route('admin.clients.index')->with('success', 'تمت إضافة الجهة بنجاح.');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.form', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $client->update($this->validated($request, $client));

        return redirect()->route('admin.clients.index')->with('success', 'تم تحديث بيانات الجهة بنجاح.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return back()->with('success', 'تم نقل الجهة إلى السلة.');
    }

    public function restore(int $client)
    {
        Client::onlyTrashed()->findOrFail($client)->restore();

        return back()->with('success', 'تم استرجاع الجهة بنجاح.');
    }

    public function forceDelete(int $client)
    {
        Client::onlyTrashed()->findOrFail($client)->forceDelete();

        return back()->with('success', 'تم حذف الجهة نهائيا.');
    }

    private function validated(Request $request, ?Client $client = null): array
    {
        if ($request->filled('website_url') && ! preg_match('/^https?:\/\//i', $request->website_url)) {
            $request->merge(['website_url' => 'https://'.ltrim($request->website_url, '/')]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'website_url' => ['nullable', 'url', 'max:1000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'logo_file.image' => 'ملف الشعار يجب أن يكون صورة.',
            'logo_file.max' => 'حجم الشعار يجب ألا يتجاوز 2MB.',
            'website_url.url' => 'يرجى إدخال رابط صحيح يبدأ بـ https:// أو http://.',
        ]);

        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('clients', 'public');
            $data['logo'] = 'storage/'.$path;
        } elseif ($client?->logo) {
            $data['logo'] = $client->logo;
        }

        unset($data['logo_file']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
