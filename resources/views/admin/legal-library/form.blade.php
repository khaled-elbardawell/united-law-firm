<x-admin.layouts.app :heading="$item->exists ? 'تعديل عنصر في المكتبة القانونية' : 'إضافة عنصر للمكتبة القانونية'" description="أدخل بيانات العنصر القانوني وملف PDF الاختياري.">
    <section class="admin-card">
        <form method="POST" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.legal-library.update', $item) : route('admin.legal-library.store') }}">
            @csrf
            @if ($item->exists) @method('PUT') @endif

            <div class="form-grid">
                <div class="field"><label>العنوان بالعربية</label><input name="title" value="{{ old('title', $item->title) }}" required></div>
                <div class="field">
                    <label>عنوان الرابط (Slug)</label>
                    <input name="slug" dir="ltr" value="{{ old('slug', $item->slug) }}" placeholder="civil-law-2026">
                    <small class="admin-muted-text">يؤثر على رابط العنصر في الموقع. استخدم أحرفاً إنجليزية صغيرة وأرقاماً وشرطة فقط. إذا تركته فارغاً سيتم توليده تلقائياً من العنوان.</small>
                </div>
                <div class="field">
                    <label>التصنيف</label>
                    <select name="category" required>
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}" @selected(old('category', $item->category) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label>تاريخ الإصدار أو النشر</label><input type="date" name="published_at" value="{{ old('published_at', $item->published_at?->format('Y-m-d')) }}"></div>
                <div class="field"><label>ترتيب العرض</label><input name="sort_order" type="number" min="0" value="{{ old('sort_order', $item->sort_order ?? 0) }}"></div>
                <div class="field">
                    <label>ملف PDF اختياري</label>
                    <input type="file" name="pdf_file" accept="application/pdf">
                    <small class="admin-muted-text">يسمح بملفات PDF فقط وبحد أقصى 10MB.</small>
                    @if ($item->pdf_url)
                        <a class="field-help-link" href="{{ $item->pdf_url }}" target="_blank" rel="noopener">تحميل الملف الحالي</a>
                    @endif
                </div>
                <div class="field full"><label>وصف مختصر</label><textarea name="short_description">{{ old('short_description', $item->short_description) }}</textarea></div>
                <div class="field full"><label>تفاصيل كاملة</label><textarea name="content" rows="10">{{ old('content', $item->content) }}</textarea></div>
                <div class="field"><label>SEO Title اختياري</label><input name="seo_title" value="{{ old('seo_title', $item->seo_title) }}"></div>
                <div class="field"><label>SEO Description اختياري</label><textarea name="seo_description">{{ old('seo_description', $item->seo_description) }}</textarea></div>
                <label class="check-field full"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true))> منشور في الموقع</label>
            </div>

            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.legal-library.index') }}">رجوع</a>
            </div>
        </form>
    </section>
</x-admin.layouts.app>
