<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:255'],
            'service' => ['required', 'string', 'max:255'],
            'preferred_date' => ['nullable', 'date'],
            'contact_method' => ['nullable', 'string', 'max:255'],
            'priority' => ['required', 'in:normal,important,urgent'],
            'details' => ['required', 'string', 'max:10000'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
        ], [
            'name.required' => 'يرجى إدخال الاسم الكامل.',
            'phone.required' => 'يرجى إدخال رقم الهاتف.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'service.required' => 'يرجى اختيار نوع الخدمة القانونية.',
            'preferred_date.date' => 'صيغة الموعد المفضل غير صحيحة.',
            'priority.required' => 'يرجى اختيار درجة الأولوية.',
            'details.required' => 'يرجى كتابة تفاصيل الاستشارة.',
            'attachments.*.mimes' => 'المرفقات يجب أن تكون PDF أو Word أو صورة.',
            'attachments.*.max' => 'حجم كل مرفق يجب ألا يتجاوز 5MB.',
        ]);

        $data['attachments'] = collect($request->file('attachments', []))
            ->map(fn ($file) => $file->store('consultations', 'public'))
            ->all();

        Consultation::create($data);

        return redirect()->route('ticket')->with('success', 'تم تسجيل طلب الاستشارة بنجاح، سنتواصل معك قريباً.');
    }
}
