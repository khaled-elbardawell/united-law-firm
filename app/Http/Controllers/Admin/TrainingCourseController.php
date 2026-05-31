<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingAttendance;
use App\Models\TrainingCourse;
use App\Models\TrainingRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrainingCourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = TrainingCourse::query()
            ->withCount('activeRegistrations')
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('slug', 'like', '%'.$request->q.'%')
                    ->orWhere('trainer_name', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('active'), fn ($query) => $query->where('is_active', $request->active))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.training-courses.index', [
            'courses' => $courses,
            'trashCount' => TrainingCourse::onlyTrashed()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.training-courses.form', ['course' => new TrainingCourse(['course_days' => 1])]);
    }

    public function store(Request $request)
    {
        TrainingCourse::create($this->validated($request));

        return redirect()->route('admin.training-courses.index')->with('success', 'تمت إضافة الدورة التدريبية بنجاح.');
    }

    public function edit(TrainingCourse $trainingCourse)
    {
        return view('admin.training-courses.form', ['course' => $trainingCourse]);
    }

    public function update(Request $request, TrainingCourse $trainingCourse)
    {
        $trainingCourse->update($this->validated($request, $trainingCourse));

        return redirect()->route('admin.training-courses.index')->with('success', 'تم تحديث الدورة التدريبية بنجاح.');
    }

    public function show(TrainingCourse $trainingCourse)
    {
        $trainingCourse->load([
            'registrations' => fn ($query) => $query->with('attendances')->latest(),
        ])->loadCount('activeRegistrations');

        return view('admin.training-courses.show', [
            'course' => $trainingCourse,
            'registrationStatuses' => TrainingRegistration::STATUSES,
            'attendanceStatuses' => TrainingAttendance::STATUSES,
        ]);
    }

    public function updateRegistration(Request $request, TrainingCourse $trainingCourse, TrainingRegistration $registration)
    {
        abort_unless($registration->training_course_id === $trainingCourse->id, 404);

        $data = $request->validate([
            'status' => ['required', 'in:registered,confirmed,cancelled,completed'],
            'admin_notes' => ['nullable', 'string', 'max:3000'],
        ]);

        $registration->update($data);

        return back()->with('success', 'تم تحديث بيانات المسجل.');
    }

    public function updateAttendance(Request $request, TrainingCourse $trainingCourse, TrainingRegistration $registration)
    {
        abort_unless($registration->training_course_id === $trainingCourse->id, 404);

        $data = $request->validate([
            'attendance' => ['nullable', 'array'],
            'attendance.*' => ['nullable', 'in:present,absent'],
        ]);

        for ($day = 1; $day <= max(1, $trainingCourse->course_days); $day++) {
            $registration->attendances()->updateOrCreate(
                ['day_number' => $day],
                [
                    'attendance_date' => $trainingCourse->course_starts_at?->copy()->addDays($day - 1),
                    'status' => $data['attendance'][$day] ?? 'absent',
                ]
            );
        }

        return back()->with('success', 'تم حفظ الحضور والغياب.');
    }

    public function destroy(TrainingCourse $trainingCourse)
    {
        $trainingCourse->delete();

        return back()->with('success', 'تم نقل الدورة إلى سلة المحذوفات.');
    }

    public function restore(int $course)
    {
        TrainingCourse::onlyTrashed()->findOrFail($course)->restore();

        return back()->with('success', 'تم استرجاع الدورة بنجاح.');
    }

    public function forceDelete(int $course)
    {
        TrainingCourse::onlyTrashed()->findOrFail($course)->forceDelete();

        return back()->with('success', 'تم حذف الدورة نهائياً.');
    }

    private function validated(Request $request, ?TrainingCourse $course = null): array
    {
        $this->normalizeSlugInput($request);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:training_courses,slug,'.($course?->id ?? 'NULL')],
            'trainer_name' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'course_days' => ['required', 'integer', 'min:1', 'max:120'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'course_starts_at' => ['nullable', 'date'],
            'course_ends_at' => ['nullable', 'date', 'after_or_equal:course_starts_at'],
            'registration_starts_at' => ['nullable', 'date'],
            'registration_ends_at' => ['nullable', 'date', 'after_or_equal:registration_starts_at'],
            'hero_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'summary' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'outcomes' => ['nullable', 'string', 'max:6000'],
            'requirements' => ['nullable', 'string', 'max:6000'],
            'schedule_notes' => ['nullable', 'string', 'max:3000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'slug.regex' => 'الرابط يجب أن يحتوي أحرفاً إنجليزية صغيرة أو أرقاماً وشرطة (-) فقط، مثل legal-training.',
        ]);

        if ($request->hasFile('hero_image_file')) {
            $path = $request->file('hero_image_file')->store('training-courses', 'public');
            $data['hero_image'] = 'storage/'.$path;
        } elseif ($course?->hero_image) {
            $data['hero_image'] = $course->hero_image;
        }

        unset($data['hero_image_file']);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']) ?: Str::random(8);
        $data['outcomes'] = $this->linesToArray($data['outcomes'] ?? null);
        $data['requirements'] = $this->linesToArray($data['requirements'] ?? null);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function linesToArray(?string $value): array
    {
        return collect(preg_split('/\R/u', (string) $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function normalizeSlugInput(Request $request): void
    {
        if ($request->has('slug')) {
            $request->merge(['slug' => Str::lower(trim((string) $request->input('slug')))]);
        }
    }
}
