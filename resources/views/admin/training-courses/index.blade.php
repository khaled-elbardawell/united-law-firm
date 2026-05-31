<x-admin.layouts.app heading="الدورات التدريبية" description="إدارة الدورات ومتابعة التسجيلات والحضور والغياب.">
    <x-slot name="actions"><a class="btn-admin btn-gold" href="{{ route('admin.training-courses.create') }}">إضافة دورة</a></x-slot>

    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث باسم الدورة أو الرابط أو المدرب...">
            <select name="active"><option value="">كل الحالات</option><option value="1" @selected(request('active') === '1')>ظاهرة</option><option value="0" @selected(request('active') === '0')>مخفية</option></select>
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.training-courses.index') }}">مسح</a>
        </form>

        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.training-courses.index', request()->except('view', 'page')) }}">النشطة</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.training-courses.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>الدورة</th><th>التسجيل</th><th>الأيام</th><th>المسجلون</th><th>الحالة</th><th>إجراءات</th></tr></thead>
                <tbody>
                @forelse ($courses as $course)
                    <tr class="{{ $course->trashed() ? 'is-trashed' : '' }}">
                        <td><strong>{{ $course->title }}</strong><br><small dir="ltr">{{ $course->slug }}</small></td>
                        <td>{{ $course->registration_starts_at?->format('Y-m-d') ?: '-' }}<br><small>{{ $course->registration_ends_at?->format('Y-m-d') ?: '-' }}</small></td>
                        <td>{{ $course->course_days }}</td>
                        <td>{{ $course->active_registrations_count }}{{ $course->capacity ? ' / '.$course->capacity : '' }}</td>
                        <td><x-admin.partials.active :active="$course->is_active" /></td>
                        <td class="actions-row">
                            @if ($course->trashed())
                                <form method="POST" action="{{ route('admin.training-courses.restore', $course->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                <form method="POST" action="{{ route('admin.training-courses.force-delete', $course->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                            @else
                                <a class="btn-admin btn-gold" href="{{ route('admin.training-courses.show', $course) }}">المسجلون</a>
                                <a class="btn-admin" href="{{ route('admin.training-courses.edit', $course) }}">تعديل</a>
                                @if ($course->is_active)
                                    <a class="btn-admin btn-muted" href="{{ route('training-courses.show', $course) }}" target="_blank">عرض الآن</a>
                                @endif
                                <form method="POST" action="{{ route('admin.training-courses.destroy', $course) }}" onsubmit="return confirm('نقل الدورة إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">لا توجد دورات حالياً.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $courses->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
