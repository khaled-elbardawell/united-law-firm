<x-admin.layouts.app :heading="$lawyer->exists ? 'تعديل محامي' : 'إضافة محامي'" description="بيانات البطاقة التي تظهر في صفحة المحامين.">
    <section class="admin-card">
        <form method="POST" action="{{ $lawyer->exists ? route('admin.lawyers.update', $lawyer) : route('admin.lawyers.store') }}" enctype="multipart/form-data">
            @csrf
            @if ($lawyer->exists) @method('PUT') @endif
            <div class="form-grid">
                <div class="field"><label>الاسم</label><input name="name" value="{{ old('name', $lawyer->name) }}" required></div>
                <div class="field"><label>المنصب</label><input name="position" value="{{ old('position', $lawyer->position) }}" required></div>
                <div class="field"><label>التخصص</label><input name="specialty" value="{{ old('specialty', $lawyer->specialty) }}"></div>
                <div class="field">
                    <label>صورة المحامي</label>
                    <input name="photo_file" type="file" accept="image/*">
                    @if ($lawyer->photo)
                        <div class="settings-logo-row">
                            <img src="{{ str_starts_with($lawyer->photo, 'http') ? $lawyer->photo : asset($lawyer->photo) }}" alt="{{ $lawyer->name }}">
                            <small>اترك الحقل فارغا للاحتفاظ بالصورة الحالية.</small>
                        </div>
                    @else
                        <small class="admin-muted-text">اختياري - JPG / PNG / WEBP بحد أقصى 2MB.</small>
                    @endif
                </div>
                <div class="field"><label>البريد</label><input name="email" type="email" value="{{ old('email', $lawyer->email) }}"></div>
                <div class="field"><label>الهاتف</label><input name="phone" value="{{ old('phone', $lawyer->phone) }}"></div>
                <div class="field"><label>الترتيب</label><input name="sort_order" type="number" min="0" value="{{ old('sort_order', $lawyer->sort_order ?? 0) }}"></div>
                <div class="field"><label>وسوم مفصولة بفواصل</label><input name="tags" value="{{ old('tags', implode(', ', $lawyer->tags ?? [])) }}"></div>
                <div class="field full"><label>نبذة</label><textarea name="bio" required>{{ old('bio', $lawyer->bio) }}</textarea></div>
                <label class="check-field full"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $lawyer->is_active ?? true))> إظهار المحامي</label>
            </div>
            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.lawyers.index') }}">رجوع</a>
            </div>
        </form>
    </section>
</x-admin.layouts.app>
