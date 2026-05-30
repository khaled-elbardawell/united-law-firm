<x-admin.layouts.app heading="إعدادات SEO" description="تحكم بعنوان ووصف وكلمات كل صفحة في الموقع.">
    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث بالصفحة، المفتاح، العنوان أو الوصف...">
            <select name="indexable">
                <option value="">كل حالات الأرشفة</option>
                <option value="1" @selected(request('indexable') === '1')>مؤرشف</option>
                <option value="0" @selected(request('indexable') === '0')>غير مؤرشف</option>
            </select>
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.seo.index') }}">مسح</a>
        </form>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>الصفحة</th><th>العنوان</th><th>الوصف</th><th>الأرشفة</th><th>إجراءات</th></tr></thead>
                <tbody>
                @forelse ($settings as $setting)
                    <tr>
                        <td><strong>{{ $setting->page_name }}</strong><br><small>{{ $setting->page_key }}</small></td>
                        <td>{{ $setting->title ?: 'غير محدد' }}</td>
                        <td>{{ $setting->description ?: 'غير محدد' }}</td>
                        <td><x-admin.partials.active :active="$setting->is_indexable" /></td>
                        <td><a class="btn-admin" href="{{ route('admin.seo.edit', $setting) }}">تعديل</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5">لا توجد نتائج.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-admin.layouts.app>
