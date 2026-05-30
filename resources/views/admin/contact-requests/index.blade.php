<x-admin.layouts.app heading="طلبات التواصل" description="كل الرسائل الواردة من صفحة اتصل بنا مع فلاتر وسلة محذوفات.">
    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث بالاسم، الهاتف، البريد أو الرسالة...">
            <select name="status">
                <option value="">كل الحالات</option>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.contact-requests.index') }}">مسح</a>
        </form>
        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.contact-requests.index', request()->except('view', 'page')) }}">الواردة</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.contact-requests.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>المرسل</th><th>الهاتف</th><th>التاريخ</th><th>الحالة</th><th>إجراءات</th></tr></thead>
                <tbody>
                @forelse ($requests as $item)
                    <tr class="{{ $item->trashed() ? 'is-trashed' : '' }}">
                        <td><strong>{{ $item->name }}</strong><br><small>{{ $item->email }}</small></td>
                        <td dir="ltr">{{ $item->phone }}</td>
                        <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                        <td><x-admin.partials.status :status="$item->status" :labels="$statuses" /></td>
                        <td class="actions-row">
                            @if ($item->trashed())
                                <form method="POST" action="{{ route('admin.contact-requests.restore', $item->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                <form method="POST" action="{{ route('admin.contact-requests.force-delete', $item->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                            @else
                                <a class="btn-admin" href="{{ route('admin.contact-requests.show', $item) }}">عرض</a>
                                <form method="POST" action="{{ route('admin.contact-requests.destroy', $item) }}" onsubmit="return confirm('نقل الطلب إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">لا توجد نتائج.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $requests->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
