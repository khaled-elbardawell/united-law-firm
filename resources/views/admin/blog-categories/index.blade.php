<x-admin.layouts.app heading="تصنيفات المدونة" description="إدارة تصنيفات المقالات مع الفلاتر وسلة المحذوفات.">
    <x-slot name="actions"><a class="btn-admin btn-gold" href="{{ route('admin.blog-categories.create') }}">إضافة تصنيف</a></x-slot>
    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث عن تصنيف أو slug...">
            <select name="active"><option value="">كل الحالات</option><option value="1" @selected(request('active') === '1')>ظاهر</option><option value="0" @selected(request('active') === '0')>مخفي</option></select>
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.blog-categories.index') }}">مسح</a>
        </form>
        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.blog-categories.index', request()->except('view', 'page')) }}">النشطة</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.blog-categories.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>التصنيف</th><th>Slug</th><th>المقالات</th><th>الحالة</th><th>إجراءات</th></tr></thead>
                <tbody>
                @forelse ($categories as $category)
                    <tr class="{{ $category->trashed() ? 'is-trashed' : '' }}">
                        <td><strong>{{ $category->name }}</strong><br><small>{{ $category->description }}</small></td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->posts_count }}</td>
                        <td><x-admin.partials.active :active="$category->is_active" /></td>
                        <td class="actions-row">
                            @if ($category->trashed())
                                <form method="POST" action="{{ route('admin.blog-categories.restore', $category->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                <form method="POST" action="{{ route('admin.blog-categories.force-delete', $category->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                            @else
                                <a class="btn-admin" href="{{ route('admin.blog-categories.edit', $category) }}">تعديل</a>
                                <form method="POST" action="{{ route('admin.blog-categories.destroy', $category) }}" onsubmit="return confirm('نقل التصنيف إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">لا توجد نتائج.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $categories->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
