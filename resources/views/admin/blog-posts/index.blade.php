<x-admin.layouts.app heading="مقالات المدونة" description="إدارة المقالات والمحتوى والسيو مع فلاتر وسلة محذوفات.">
    <x-slot name="actions"><a class="btn-admin btn-gold" href="{{ route('admin.blog-posts.create') }}">إضافة مقال</a></x-slot>
    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث في العنوان أو slug...">
            <select name="status"><option value="">كل الحالات</option>@foreach (\App\Models\BlogPost::STATUSES as $key => $label)<option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>@endforeach</select>
            <select name="category"><option value="">كل التصنيفات</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>@endforeach</select>
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.blog-posts.index') }}">مسح</a>
        </form>
        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.blog-posts.index', request()->except('view', 'page')) }}">النشطة</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.blog-posts.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>المقال</th><th>التصنيف</th><th>الحالة</th><th>القراءات</th><th>تاريخ النشر</th><th>الكاتب</th><th>إجراءات</th></tr></thead>
                <tbody>
                @forelse ($posts as $post)
                    <tr class="{{ $post->trashed() ? 'is-trashed' : '' }}">
                        <td><strong>{{ $post->title }}</strong><br><small>{{ $post->slug }}</small></td>
                        <td>{{ $post->category?->name ?: '-' }}</td>
                        <td><span class="badge {{ $post->status === 'published' ? 'active' : 'inactive' }}">{{ \App\Models\BlogPost::STATUSES[$post->status] ?? $post->status }}</span></td>
                        <td>{{ number_format($post->views_count) }}</td>
                        <td>{{ $post->published_at?->format('Y-m-d') ?: '-' }}</td>
                        <td>{{ $post->author?->name ?: '-' }}</td>
                        <td class="actions-row">
                            @if ($post->trashed())
                                <form method="POST" action="{{ route('admin.blog-posts.restore', $post->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                <form method="POST" action="{{ route('admin.blog-posts.force-delete', $post->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                            @else
                                @if ($post->status === 'published')<a class="btn-admin" href="{{ route('blog.show', $post->slug) }}" target="_blank">عرض</a>@endif
                                <a class="btn-admin" href="{{ route('admin.blog-posts.edit', $post) }}">تعديل</a>
                                <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" onsubmit="return confirm('نقل المقال إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">لا توجد نتائج.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $posts->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
