<x-admin.layouts.app heading="مسجلو الدورة" :description="$course->title">
    <x-slot name="actions">
        <a class="btn-admin" href="{{ route('admin.training-courses.edit', $course) }}">تعديل الدورة</a>
        <a class="btn-admin btn-muted" href="{{ route('admin.training-courses.index') }}">رجوع</a>
    </x-slot>

    <section class="grid-2">
        <article class="admin-card">
            <h2>{{ $course->title }}</h2>
            <p><strong>مدة الدورة:</strong> {{ $course->course_days }} أيام</p>
            <p><strong>المقاعد:</strong> {{ $course->active_registrations_count }}{{ $course->capacity ? ' / '.$course->capacity : '' }}</p>
            <p><strong>التسجيل:</strong> {{ $course->registration_starts_at?->format('Y-m-d H:i') ?: '-' }} إلى {{ $course->registration_ends_at?->format('Y-m-d H:i') ?: '-' }}</p>
        </article>
        <article class="admin-card">
            <h2>حالة التسجيل</h2>
            <p>{{ $course->isRegistrationOpen() ? 'التسجيل مفتوح حالياً.' : 'التسجيل مغلق حالياً.' }}</p>
            <a class="btn-admin btn-gold" href="{{ route('training-courses.show', $course) }}" target="_blank">عرض صفحة الدورة</a>
        </article>
    </section>

    <section class="admin-card" style="margin-top:18px;">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>المسجل</th>
                        <th>الحالة</th>
                        <th>الحضور</th>
                        <th>الأيام</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($course->registrations as $registration)
                        @php
                            $attendanceByDay = $registration->attendances->keyBy('day_number');
                            $progress = $registration->attendanceProgress();
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $registration->name }}</strong><br>
                                <small>{{ $registration->phone }}{{ $registration->email ? ' - '.$registration->email : '' }}</small>
                                @if ($registration->profession)<br><small>{{ $registration->profession }}</small>@endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.training-courses.registrations.update', [$course, $registration]) }}">
                                    @csrf @method('PUT')
                                    <select name="status">
                                        @foreach ($registrationStatuses as $key => $label)
                                            <option value="{{ $key }}" @selected($registration->status === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <textarea name="admin_notes" rows="2" placeholder="ملاحظات داخلية">{{ $registration->admin_notes }}</textarea>
                                    <button class="btn-admin" type="submit">حفظ</button>
                                </form>
                            </td>
                            <td>
                                <div class="admin-progress"><span style="width: {{ $progress }}%"></span></div>
                                <strong>{{ $progress }}%</strong>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.training-courses.registrations.attendance', [$course, $registration]) }}">
                                    @csrf @method('PUT')
                                    <div class="attendance-days">
                                        @for ($day = 1; $day <= $course->course_days; $day++)
                                            @php $status = $attendanceByDay[$day]->status ?? 'absent'; @endphp
                                            <label class="attendance-day {{ $status === 'present' ? 'is-present' : 'is-absent' }}">
                                                <span>اليوم {{ $day }}</span>
                                                <select name="attendance[{{ $day }}]">
                                                    @foreach ($attendanceStatuses as $key => $label)
                                                        <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        @endfor
                                    </div>
                                    <button class="btn-admin btn-gold" type="submit">حفظ الحضور</button>
                                </form>
                            </td>
                            <td>
                                <p>{{ $registration->notes ?: 'لا توجد ملاحظات.' }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">لا يوجد مسجلون في هذه الدورة بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-admin.layouts.app>
