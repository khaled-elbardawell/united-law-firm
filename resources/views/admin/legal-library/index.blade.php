<x-admin.layouts.app heading="المكتبة القانونية" description="إدارة القوانين والقرارات القانونية والقرارات القضائية المنشورة في الموقع.">
    <x-slot name="actions">
        <a class="btn-admin btn-gold" href="{{ route('admin.legal-library.create') }}">إضافة عنصر</a>
    </x-slot>

    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث عن عنوان أو رابط أو وصف...">
            <select name="category">
                <option value="">كل التصنيفات</option>
                @foreach ($categories as $key => $label)
                    <option value="{{ $key }}" @selected(request('category') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="active">
                <option value="">كل الحالات</option>
                <option value="1" @selected(request('active') === '1')>منشور</option>
                <option value="0" @selected(request('active') === '0')>غير منشور</option>
            </select>
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.legal-library.index') }}">مسح</a>
        </form>

        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.legal-library.index', request()->except('view', 'page')) }}">النشطة</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.legal-library.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>التصنيف</th>
                        <th>الرابط</th>
                        <th>التاريخ</th>
                        <th>PDF</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr class="{{ $item->trashed() ? 'is-trashed' : '' }}">
                            <td><strong>{{ $item->title }}</strong><br><small>{{ $item->short_description }}</small></td>
                            <td>{{ $item->category_label }}</td>
                            <td dir="ltr">{{ $item->slug }}</td>
                            <td>{{ $item->published_at?->format('Y-m-d') ?: '-' }}</td>
                            <td>
                                @if ($item->pdf_url)
                                    <a href="{{ $item->pdf_url }}" target="_blank" rel="noopener">تحميل</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td><x-admin.partials.active :active="$item->is_active" /></td>
                            <td class="actions-row">
                                @if ($item->trashed())
                                    <form method="POST" action="{{ route('admin.legal-library.restore', $item->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                    <form method="POST" action="{{ route('admin.legal-library.force-delete', $item->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                                @else
                                    <a class="btn-admin" href="{{ route('admin.legal-library.edit', $item) }}">تعديل</a>
                                    @if ($item->is_active)
                                        <a class="btn-admin btn-muted" href="{{ route('legal-library.show', [$item->category_route, $item]) }}" target="_blank">عرض الآن</a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.legal-library.destroy', $item) }}" onsubmit="return confirm('نقل العنصر إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">لا توجد نتائج.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $items->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
