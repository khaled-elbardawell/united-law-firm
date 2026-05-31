<x-admin.layouts.app :heading="$category->exists ? 'تعديل تصنيف' : 'إضافة تصنيف'" description="تصنيفات المدونة تساعد الزائر على فلترة المقالات.">
    <section class="admin-card">
        <form method="POST" action="{{ $category->exists ? route('admin.blog-categories.update', $category) : route('admin.blog-categories.store') }}">
            @csrf
            @if ($category->exists) @method('PUT') @endif
            <div class="form-grid">
                <div class="field"><label>اسم التصنيف</label><input name="name" value="{{ old('name', $category->name) }}" required></div>
                <div class="field">
                    <label>عنوان الرابط (Slug)</label>
                    <input name="slug" dir="ltr" value="{{ old('slug', $category->slug) }}" placeholder="legal-news">
                    <small class="admin-muted-text">يؤثر على رابط فلترة المقالات بهذا التصنيف. استخدم أحرفاً إنجليزية صغيرة وأرقاماً وشرطة فقط. إذا تركته فارغاً سيتم توليده تلقائياً من اسم التصنيف.</small>
                </div>
                <div class="field"><label>الترتيب</label><input name="sort_order" type="number" min="0" value="{{ old('sort_order', $category->sort_order ?? 0) }}"></div>
                <label class="check-field"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))> إظهار التصنيف</label>
                <div class="field full"><label>الوصف</label><textarea name="description">{{ old('description', $category->description) }}</textarea></div>
            </div>
            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.blog-categories.index') }}">رجوع</a>
            </div>
        </form>
    </section>
</x-admin.layouts.app>
