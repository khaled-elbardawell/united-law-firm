<x-admin.layouts.app :heading="$tag->exists ? 'تعديل وسم' : 'إضافة وسم'" description="الوسوم تساعد في ربط المقالات المتشابهة.">
    <section class="admin-card">
        <form method="POST" action="{{ $tag->exists ? route('admin.blog-tags.update', $tag) : route('admin.blog-tags.store') }}">
            @csrf
            @if ($tag->exists) @method('PUT') @endif
            <div class="form-grid">
                <div class="field"><label>اسم الوسم</label><input name="name" value="{{ old('name', $tag->name) }}" required></div>
                <div class="field"><label>Slug</label><input name="slug" value="{{ old('slug', $tag->slug) }}"></div>
            </div>
            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.blog-tags.index') }}">رجوع</a>
            </div>
        </form>
    </section>
</x-admin.layouts.app>
