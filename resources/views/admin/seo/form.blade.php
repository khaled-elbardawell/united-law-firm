<x-admin.layouts.app :heading="'SEO: '.$setting->page_name" description="هذه البيانات تظهر داخل meta tags في الصفحة.">
    <section class="admin-card">
        <form method="POST" action="{{ route('admin.seo.update', $setting) }}">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="field full"><label>عنوان الصفحة</label><input name="title" value="{{ old('title', $setting->title) }}"></div>
                <div class="field full"><label>الوصف</label><textarea name="description">{{ old('description', $setting->description) }}</textarea></div>
                <div class="field full"><label>الكلمات المفتاحية</label><textarea name="keywords">{{ old('keywords', $setting->keywords) }}</textarea></div>
                <div class="field"><label>Canonical URL</label><input name="canonical_url" value="{{ old('canonical_url', $setting->canonical_url) }}"></div>
                <div class="field"><label>صورة المشاركة</label><input name="og_image" value="{{ old('og_image', $setting->og_image) }}"></div>
                <label class="check-field full"><input type="checkbox" name="is_indexable" value="1" @checked(old('is_indexable', $setting->is_indexable ?? true))> السماح بالأرشفة</label>
            </div>
            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.seo.index') }}">رجوع</a>
            </div>
        </form>
    </section>
</x-admin.layouts.app>
