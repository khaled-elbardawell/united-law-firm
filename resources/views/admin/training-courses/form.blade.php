<x-admin.layouts.app :heading="$course->exists ? 'تعديل دورة تدريبية' : 'إضافة دورة تدريبية'" description="بيانات الدورة التي تظهر في الموقع ونوافذ التسجيل.">
    @php
        $lines = fn (string $key) => old($key, implode("\n", $course->{$key} ?? []));
    @endphp

    <section class="admin-card">
        <form method="POST" action="{{ $course->exists ? route('admin.training-courses.update', $course) : route('admin.training-courses.store') }}" enctype="multipart/form-data">
            @csrf
            @if ($course->exists) @method('PUT') @endif

            <div class="form-grid">
                <div class="field"><label>عنوان الدورة</label><input name="title" value="{{ old('title', $course->title) }}" required></div>
                <div class="field">
                    <label>عنوان الرابط (Slug)</label>
                    <input name="slug" dir="ltr" value="{{ old('slug', $course->slug) }}" placeholder="legal-training">
                    <small class="admin-muted-text">يؤثر على رابط الدورة في الموقع. استخدم أحرفاً إنجليزية صغيرة وأرقاماً وشرطة فقط. إذا تركته فارغاً سيتم توليده تلقائياً من عنوان الدورة.</small>
                </div>
                <div class="field"><label>اسم المدرب</label><input name="trainer_name" value="{{ old('trainer_name', $course->trainer_name) }}"></div>
                <div class="field"><label>المكان</label><input name="location" value="{{ old('location', $course->location) }}"></div>
                <div class="field"><label>عدد المقاعد</label><input name="capacity" type="number" min="1" value="{{ old('capacity', $course->capacity) }}"></div>
                <div class="field"><label>عدد أيام الدورة</label><input name="course_days" type="number" min="1" max="120" value="{{ old('course_days', $course->course_days ?? 1) }}" required></div>
                <div class="field"><label>الرسوم</label><input name="price" type="number" min="0" step="0.01" value="{{ old('price', $course->price) }}"></div>
                <div class="field"><label>الترتيب</label><input name="sort_order" type="number" min="0" value="{{ old('sort_order', $course->sort_order ?? 0) }}"></div>
                <div class="field"><label>تاريخ بداية الدورة</label><input name="course_starts_at" type="date" value="{{ old('course_starts_at', $course->course_starts_at?->format('Y-m-d')) }}"></div>
                <div class="field"><label>تاريخ نهاية الدورة</label><input name="course_ends_at" type="date" value="{{ old('course_ends_at', $course->course_ends_at?->format('Y-m-d')) }}"></div>
                <div class="field"><label>بداية التسجيل</label><input name="registration_starts_at" type="datetime-local" value="{{ old('registration_starts_at', $course->registration_starts_at?->format('Y-m-d\TH:i')) }}"></div>
                <div class="field"><label>نهاية التسجيل</label><input name="registration_ends_at" type="datetime-local" value="{{ old('registration_ends_at', $course->registration_ends_at?->format('Y-m-d\TH:i')) }}"></div>

                <div class="field">
                    <label>صورة الدورة</label>
                    <input name="hero_image_file" type="file" accept="image/*">
                    @if ($course->hero_image)
                        <div class="settings-logo-row">
                            <img src="{{ str_starts_with($course->hero_image, 'http') ? $course->hero_image : asset($course->hero_image) }}" alt="{{ $course->title }}">
                            <small>اترك الحقل فارغاً للاحتفاظ بالصورة الحالية.</small>
                        </div>
                    @endif
                </div>

                <div class="field full"><label>ملخص قصير</label><textarea name="summary" rows="4" required>{{ old('summary', $course->summary) }}</textarea></div>
                <div class="field full"><label>وصف تفصيلي</label><textarea name="description" rows="7">{{ old('description', $course->description) }}</textarea></div>
                <div class="field full"><label>مخرجات الدورة - كل سطر عنصر</label><textarea name="outcomes" rows="5">{{ $lines('outcomes') }}</textarea></div>
                <div class="field full"><label>متطلبات التسجيل - كل سطر عنصر</label><textarea name="requirements" rows="4">{{ $lines('requirements') }}</textarea></div>
                <div class="field full"><label>ملاحظات الجدول</label><textarea name="schedule_notes" rows="3">{{ old('schedule_notes', $course->schedule_notes) }}</textarea></div>
                <label class="check-field full"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $course->is_active ?? true))> إظهار الدورة في الموقع</label>
            </div>

            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.training-courses.index') }}">رجوع</a>
            </div>
        </form>
    </section>
</x-admin.layouts.app>
