<x-admin.layouts.app heading="الخدمات" description="إضافة وتعديل وأرشفة خدمات الموقع مع فلاتر وسلة محذوفات.">
    <x-slot name="actions">
        <a class="btn-admin btn-gold" href="{{ route('admin.services.create') }}">إضافة خدمة</a>
    </x-slot>

    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث عن خدمة أو رابط...">
            <select name="active">
                <option value="">كل الحالات</option>
                <option value="1" @selected(request('active') === '1')>فعّالة</option>
                <option value="0" @selected(request('active') === '0')>مخفية</option>
            </select>
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.services.index') }}">مسح</a>
        </form>

        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.services.index', request()->except('view', 'page')) }}">النشطة</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.services.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>الخدمة</th><th>الأيقونة</th><th>الرابط</th><th>الترتيب</th><th>الحالة</th><th>إجراءات</th></tr></thead>
                <tbody>
                @forelse ($services as $service)
                    <tr class="{{ $service->trashed() ? 'is-trashed' : '' }}">
                        <td><strong>{{ $service->title }}</strong><br><small>{{ $service->summary }}</small></td>
                        <td><i class="{{ $service->icon ?: 'fa-solid fa-scale-balanced' }}"></i><br><small>{{ $service->icon ?: 'fa-solid fa-scale-balanced' }}</small></td>
                        <td>{{ $service->slug }}</td>
                        <td>{{ $service->sort_order }}</td>
                        <td><x-admin.partials.active :active="$service->is_active" /></td>
                        <td class="actions-row">
                            @if ($service->trashed())
                                <form method="POST" action="{{ route('admin.services.restore', $service->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                <form method="POST" action="{{ route('admin.services.force-delete', $service->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                            @else
                                <a class="btn-admin" href="{{ route('admin.services.edit', $service) }}">تعديل</a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('نقل الخدمة إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">لا توجد نتائج.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $services->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
