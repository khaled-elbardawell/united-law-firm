<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $consultations = Consultation::query()
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->priority))
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('phone', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%')
                    ->orWhere('service', 'like', '%'.$request->q.'%')
                    ->orWhere('details', 'like', '%'.$request->q.'%');
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.consultations.index', [
            'consultations' => $consultations,
            'statuses' => Consultation::STATUSES,
            'trashCount' => Consultation::onlyTrashed()->count(),
        ]);
    }

    public function show(Consultation $consultation)
    {
        return view('admin.consultations.show', [
            'consultation' => $consultation,
            'statuses' => Consultation::STATUSES,
        ]);
    }

    public function update(Request $request, Consultation $consultation)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,following,completed'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $data['handled_at'] = $data['status'] === 'completed' ? now() : null;
        $consultation->update($data);

        return back()->with('success', 'تم تحديث حالة الاستشارة.');
    }

    public function destroy(Consultation $consultation)
    {
        $consultation->delete();

        return redirect()->route('admin.consultations.index')->with('success', 'تم نقل الاستشارة إلى سلة المحذوفات.');
    }

    public function restore(int $consultation)
    {
        Consultation::onlyTrashed()->findOrFail($consultation)->restore();

        return back()->with('success', 'تم استرجاع الاستشارة بنجاح.');
    }

    public function forceDelete(int $consultation)
    {
        Consultation::onlyTrashed()->findOrFail($consultation)->forceDelete();

        return back()->with('success', 'تم حذف الاستشارة نهائياً.');
    }
}
