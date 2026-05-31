<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\TrainingCourse;
use Illuminate\Http\Request;

class TrainingCourseController extends Controller
{
    public function current()
    {
        return view('website.training-courses.index', [
            'title' => 'الدورات التدريبية الجديدة',
            'subtitle' => 'دورات مفتوحة للتسجيل يمكنك الانضمام إليها الآن.',
            'emptyText' => 'لا توجد دورات مفتوحة للتسجيل حالياً.',
            'courses' => TrainingCourse::query()
                ->withCount('activeRegistrations')
                ->openForRegistration()
                ->orderBy('sort_order')
                ->latest()
                ->get(),
        ]);
    }

    public function past()
    {
        return view('website.training-courses.index', [
            'title' => 'الدورات التدريبية السابقة',
            'subtitle' => 'أرشيف الدورات التي انتهى التسجيل عليها أو تم تنفيذها.',
            'emptyText' => 'لا توجد دورات تدريبية سابقة حالياً.',
            'courses' => TrainingCourse::query()
                ->withCount('activeRegistrations')
                ->published()
                ->where(function ($query) {
                    $query->where('registration_ends_at', '<', now())
                        ->orWhere('course_ends_at', '<', now());
                })
                ->orderByDesc('course_starts_at')
                ->latest()
                ->get(),
        ]);
    }

    public function show(TrainingCourse $course)
    {
        abort_unless($course->is_active, 404);

        $course->loadCount('activeRegistrations');

        return view('website.training-courses.show', compact('course'));
    }

    public function register(Request $request, TrainingCourse $course)
    {
        abort_unless($course->is_active, 404);
        $course->loadCount('activeRegistrations');

        abort_unless($course->isRegistrationOpen(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1500'],
        ]);

        $course->registrations()->create($data + ['status' => 'registered']);

        return back()->with('success', 'تم تسجيل طلبك في الدورة بنجاح. سيتواصل معك فريق المكتب لتأكيد التفاصيل.');
    }
}
