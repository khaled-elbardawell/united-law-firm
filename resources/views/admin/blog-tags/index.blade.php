<x-admin.layouts.app heading="وسوم المدونة" description="إدارة Tags المقالات مع البحث وسلة المحذوفات.">
    <x-slot name="actions"><a class="btn-admin btn-gold" href="{{ route('admin.blog-tags.create') }}">إضافة وسم</a></x-slot>
    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث عن وسم أو slug...">
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.blog-tags.index') }}">مسح</a>
        </form>
        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.blog-tags.index', request()->except('view', 'page')) }}">النشطة</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.blog-tags.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>الوسم</th><th>Slug</th><th>المقالات</th><th>إجراءات</th></tr></thead>
                <tbody>
                @forelse ($tags as $tag)
                    <tr class="{{ $tag->trashed() ? 'is-trashed' : '' }}">
                        <td><strong>{{ $tag->name }}</strong></td>
                        <td>{{ $tag->slug }}</td>
                        <td>{{ $tag->posts_count }}</td>
                        <td class="actions-row">
                            @if ($tag->trashed())
                                <form method="POST" action="{{ route('admin.blog-tags.restore', $tag->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                <form method="POST" action="{{ route('admin.blog-tags.force-delete', $tag->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                            @else
                                <a class="btn-admin" href="{{ route('admin.blog-tags.edit', $tag) }}">تعديل</a>
                                <form method="POST" action="{{ route('admin.blog-tags.destroy', $tag) }}" onsubmit="return confirm('نقل الوسم إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">لا توجد نتائج.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $tags->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
