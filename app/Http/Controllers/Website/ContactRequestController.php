<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    public function store(Request $request)
    {
        ContactRequest::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ], [
            'name.required' => 'يرجى إدخال الاسم الكامل.',
            'phone.required' => 'يرجى إدخال رقم الهاتف.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'message.required' => 'يرجى كتابة الرسالة أو الاستفسار.',
        ]));

        return back()->with('success', 'تم إرسال رسالتك بنجاح، سنتواصل معك قريباً.');
    }
}
