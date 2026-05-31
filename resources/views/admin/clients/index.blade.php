<x-admin.layouts.app heading="الجهات الموثوقة" description="إدارة الجهات والعملاء الذين وثقوا بخدمات المكتب القانونية مع فلاتر وسلة محذوفات.">
    <x-slot name="actions">
        <a class="btn-admin btn-gold" href="{{ route('admin.clients.create') }}">إضافة جهة</a>
    </x-slot>

    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث باسم الجهة أو الوصف...">
            <select name="active">
                <option value="">كل الحالات</option>
                <option value="1" @selected(request('active') === '1')>فعالة</option>
                <option value="0" @selected(request('active') === '0')>مخفية</option>
            </select>
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.clients.index') }}">مسح</a>
        </form>

        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.clients.index', request()->except('view', 'page')) }}">النشطة</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.clients.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>الجهة</th>
                        <th>الرابط</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($clients as $client)
                    <tr class="{{ $client->trashed() ? 'is-trashed' : '' }}">
                        <td>
                            <div class="admin-client-cell">
                                <span class="admin-client-logo">
                                    @if ($client->logo)
                                        <img src="{{ str_starts_with($client->logo, 'http') ? $client->logo : asset($client->logo) }}" alt="{{ $client->name }}">
                                    @else
                                        <i class="fa-solid fa-building-columns"></i>
                                    @endif
                                </span>
                                <span>
                                    <strong>{{ $client->name }}</strong>
                                    @if ($client->description)
                                        <br><small>{{ $client->description }}</small>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td>
                            @if ($client->website_url)
                                <a href="{{ $client->website_url }}" target="_blank" rel="noopener">{{ $client->website_url }}</a>
                            @else
                                <span class="admin-muted-text">بدون رابط</span>
                            @endif
                        </td>
                        <td>{{ $client->sort_order }}</td>
                        <td><x-admin.partials.active :active="$client->is_active" /></td>
                        <td class="actions-row">
                            @if ($client->trashed())
                                <form method="POST" action="{{ route('admin.clients.restore', $client->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                <form method="POST" action="{{ route('admin.clients.force-delete', $client->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                            @else
                                <a class="btn-admin" href="{{ route('admin.clients.edit', $client) }}">تعديل</a>
                                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" onsubmit="return confirm('نقل الجهة إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">لا توجد نتائج.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $clients->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
