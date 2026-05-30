<x-admin.layouts.app heading="الاستشارات" description="طلبات حجز الاستشارة مع بحث وفلاتر وسلة محذوفات.">
    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث بالعميل، الهاتف، الخدمة أو التفاصيل...">
            <select name="status">
                <option value="">كل الحالات</option>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="priority">
                <option value="">كل الأولويات</option>
                <option value="normal" @selected(request('priority') === 'normal')>عادية</option>
                <option value="important" @selected(request('priority') === 'important')>مهمة</option>
                <option value="urgent" @selected(request('priority') === 'urgent')>عاجلة</option>
            </select>
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.consultations.index') }}">مسح</a>
        </form>
        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.consultations.index', request()->except('view', 'page')) }}">الواردة</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.consultations.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>العميل</th><th>الخدمة</th><th>الأولوية</th><th>التاريخ</th><th>الحالة</th><th>إجراءات</th></tr></thead>
                <tbody>
                @forelse ($consultations as $item)
                    <tr class="{{ $item->trashed() ? 'is-trashed' : '' }}">
                        <td><strong>{{ $item->name }}</strong><br><small dir="ltr">{{ $item->phone }}</small></td>
                        <td>{{ $item->service }}</td>
                        <td>{{ $item->priority }}</td>
                        <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                        <td><x-admin.partials.status :status="$item->status" :labels="$statuses" /></td>
                        <td class="actions-row">
                            @if ($item->trashed())
                                <form method="POST" action="{{ route('admin.consultations.restore', $item->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                <form method="POST" action="{{ route('admin.consultations.force-delete', $item->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                            @else
                                <a class="btn-admin" href="{{ route('admin.consultations.show', $item) }}">عرض</a>
                                <form method="POST" action="{{ route('admin.consultations.destroy', $item) }}" onsubmit="return confirm('نقل الاستشارة إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">لا توجد نتائج.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $consultations->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
