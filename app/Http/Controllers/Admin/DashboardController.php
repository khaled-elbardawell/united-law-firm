<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ContactRequest;
use App\Models\Faq;
use App\Models\Lawyer;
use App\Models\Service;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $contactStatus = ContactRequest::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $consultationStatus = Consultation::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard.index', [
            'stats' => [
                'services' => Service::count(),
                'lawyers' => Lawyer::count(),
                'faqs' => Faq::count(),
                'users' => User::count(),
                'pendingContacts' => (int) ($contactStatus['pending'] ?? 0),
                'pendingConsultations' => (int) ($consultationStatus['pending'] ?? 0),
                'trash' => Service::onlyTrashed()->count()
                    + Lawyer::onlyTrashed()->count()
                    + Faq::onlyTrashed()->count()
                    + ContactRequest::onlyTrashed()->count()
                    + Consultation::onlyTrashed()->count()
                    + User::onlyTrashed()->count(),
            ],
            'contactStatus' => $contactStatus,
            'consultationStatus' => $consultationStatus,
            'latestContacts' => ContactRequest::latest()->take(5)->get(),
            'latestConsultations' => Consultation::latest()->take(5)->get(),
        ]);
    }
}
