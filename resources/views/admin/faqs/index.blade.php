<x-admin.layouts.app heading="الأسئلة الشائعة" description="إضافة وتعديل الأسئلة مع فلترة وسلة محذوفات.">
    <x-slot name="actions"><a class="btn-admin btn-gold" href="{{ route('admin.faqs.create') }}">إضافة سؤال</a></x-slot>
    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث في السؤال أو الإجابة...">
            <select name="active"><option value="">كل الحالات</option><option value="1" @selected(request('active') === '1')>ظاهر</option><option value="0" @selected(request('active') === '0')>مخفي</option></select>
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.faqs.index') }}">مسح</a>
        </form>
        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.faqs.index', request()->except('view', 'page')) }}">النشطة</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.faqs.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>السؤال</th><th>الترتيب</th><th>الحالة</th><th>إجراءات</th></tr></thead>
                <tbody>
                @forelse ($faqs as $faq)
                    <tr class="{{ $faq->trashed() ? 'is-trashed' : '' }}">
                        <td><strong>{{ $faq->question }}</strong><br><small>{{ $faq->answer }}</small></td>
                        <td>{{ $faq->sort_order }}</td>
                        <td><x-admin.partials.active :active="$faq->is_active" /></td>
                        <td class="actions-row">
                            @if ($faq->trashed())
                                <form method="POST" action="{{ route('admin.faqs.restore', $faq->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                <form method="POST" action="{{ route('admin.faqs.force-delete', $faq->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                            @else
                                <a class="btn-admin" href="{{ route('admin.faqs.edit', $faq) }}">تعديل</a>
                                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('نقل السؤال إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">لا توجد نتائج.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $faqs->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
