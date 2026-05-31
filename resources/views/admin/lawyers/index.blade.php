<x-admin.layouts.app heading="المحامون" description="إدارة فريق المحامين مع البحث والسلة.">
    <x-slot name="actions"><a class="btn-admin btn-gold" href="{{ route('admin.lawyers.create') }}">إضافة محامي</a></x-slot>
    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث بالاسم أو التخصص...">
            <select name="active"><option value="">كل الحالات</option><option value="1" @selected(request('active') === '1')>فعّال</option><option value="0" @selected(request('active') === '0')>مخفي</option></select>
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.lawyers.index') }}">مسح</a>
        </form>
        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.lawyers.index', request()->except('view', 'page')) }}">النشطون</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.lawyers.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>الاسم</th><th>المنصب</th><th>الوسوم</th><th>الحالة</th><th>إجراءات</th></tr></thead>
                <tbody>
                @forelse ($lawyers as $lawyer)
                    <tr class="{{ $lawyer->trashed() ? 'is-trashed' : '' }}">
                        <td><strong>{{ $lawyer->name }}</strong><br><small>{{ \Illuminate\Support\Str::limit($lawyer->bio, 120) }}</small></td>
                        <td>{{ $lawyer->position }}<br><small>{{ $lawyer->specialty }}</small></td>
                        <td>{{ implode('، ', $lawyer->tags ?? []) }}</td>
                        <td><x-admin.partials.active :active="$lawyer->is_active" /></td>
                        <td class="actions-row">
                            @if ($lawyer->trashed())
                                <form method="POST" action="{{ route('admin.lawyers.restore', $lawyer->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                <form method="POST" action="{{ route('admin.lawyers.force-delete', $lawyer->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                            @else
                                <a class="btn-admin" href="{{ route('admin.lawyers.edit', $lawyer) }}">تعديل</a>
                                @if ($lawyer->is_active)
                                    <a class="btn-admin btn-muted" href="{{ route('lawyers.show', $lawyer) }}" target="_blank">عرض الآن</a>
                                @endif
                                <form method="POST" action="{{ route('admin.lawyers.destroy', $lawyer) }}" onsubmit="return confirm('نقل المحامي إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">لا توجد نتائج.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $lawyers->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
