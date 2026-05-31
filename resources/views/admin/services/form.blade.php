<x-admin.layouts.app :heading="$service->exists ? 'تعديل خدمة' : 'إضافة خدمة'" description="أدخل بيانات الخدمة التي تظهر في صفحات الموقع.">
    <section class="admin-card">
        <form method="POST" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}">
            @csrf
            @if ($service->exists) @method('PUT') @endif
            <div class="form-grid">
                <div class="field"><label>العنوان</label><input name="title" value="{{ old('title', $service->title) }}" required></div>
                <div class="field">
                    <label>عنوان الرابط (Slug)</label>
                    <input name="slug" dir="ltr" value="{{ old('slug', $service->slug) }}" placeholder="corporate-law">
                    <small class="admin-muted-text">يؤثر على رابط الخدمة في الموقع. استخدم أحرفاً إنجليزية صغيرة وأرقاماً وشرطة فقط. إذا تركته فارغاً سيتم توليده تلقائياً من عنوان الخدمة.</small>
                </div>
                <div class="field">
                    <label>الأيقونة</label>
                    <input name="icon" value="{{ old('icon', $service->icon) }}" placeholder="fa-solid fa-scale-balanced">
                    <a class="field-help-link" href="https://fontawesome.com/search?m=free&o=r" target="_blank" rel="noopener">
                        مكتبة Font Awesome لاختيار اسم الكلاس
                    </a>
                </div>
                <div class="field"><label>الترتيب</label><input name="sort_order" type="number" min="0" value="{{ old('sort_order', $service->sort_order ?? 0) }}"></div>
                <div class="field full"><label>ملخص قصير</label><textarea name="summary" required>{{ old('summary', $service->summary) }}</textarea></div>
                <div class="field full"><label>الوصف الكامل</label><textarea name="description">{{ old('description', $service->description) }}</textarea></div>
                <label class="check-field full"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active ?? true))> إظهار الخدمة في الموقع</label>
            </div>
            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.services.index') }}">رجوع</a>
            </div>
        </form>
    </section>
</x-admin.layouts.app>
