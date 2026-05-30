<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = ContactRequest::query()
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('phone', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%')
                    ->orWhere('message', 'like', '%'.$request->q.'%');
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.contact-requests.index', [
            'requests' => $requests,
            'statuses' => ContactRequest::STATUSES,
            'trashCount' => ContactRequest::onlyTrashed()->count(),
        ]);
    }

    public function show(ContactRequest $contactRequest)
    {
        return view('admin.contact-requests.show', [
            'requestItem' => $contactRequest,
            'statuses' => ContactRequest::STATUSES,
        ]);
    }

    public function update(Request $request, ContactRequest $contactRequest)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,following,completed'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $data['handled_at'] = $data['status'] === 'completed' ? now() : null;
        $contactRequest->update($data);

        return back()->with('success', 'تم تحديث حالة الطلب.');
    }

    public function destroy(ContactRequest $contactRequest)
    {
        $contactRequest->delete();

        return redirect()->route('admin.contact-requests.index')->with('success', 'تم نقل الطلب إلى سلة المحذوفات.');
    }

    public function restore(int $contactRequest)
    {
        ContactRequest::onlyTrashed()->findOrFail($contactRequest)->restore();

        return back()->with('success', 'تم استرجاع الطلب بنجاح.');
    }

    public function forceDelete(int $contactRequest)
    {
        ContactRequest::onlyTrashed()->findOrFail($contactRequest)->forceDelete();

        return back()->with('success', 'تم حذف الطلب نهائياً.');
    }
}
