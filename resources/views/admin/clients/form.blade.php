<x-admin.layouts.app :heading="$client->exists ? 'تعديل جهة' : 'إضافة جهة'" description="تظهر هذه الجهات في سلايدر الصفحة الرئيسية وصفحة عن المكتب.">
    <section class="admin-card">
        <form method="POST" action="{{ $client->exists ? route('admin.clients.update', $client) : route('admin.clients.store') }}" enctype="multipart/form-data">
            @csrf
            @if ($client->exists) @method('PUT') @endif

            <div class="form-grid">
                <div class="field">
                    <label>اسم الجهة</label>
                    <input name="name" value="{{ old('name', $client->name) }}" required>
                </div>

                <div class="field">
                    <label>رابط الموقع</label>
                    <input name="website_url" dir="ltr" value="{{ old('website_url', $client->website_url) }}" placeholder="https://example.com">
                </div>

                <div class="field">
                    <label>شعار الجهة</label>
                    <input name="logo_file" type="file" accept="image/*">
                    @if ($client->logo)
                        <div class="settings-logo-row">
                            <img src="{{ str_starts_with($client->logo, 'http') ? $client->logo : asset($client->logo) }}" alt="{{ $client->name }}">
                            <small>اترك الحقل فارغا للاحتفاظ بالشعار الحالي.</small>
                        </div>
                    @else
                        <small class="admin-muted-text">اختياري - JPG / PNG / WEBP / SVG بحد أقصى 2MB.</small>
                    @endif
                </div>

                <div class="field">
                    <label>الترتيب</label>
                    <input name="sort_order" type="number" min="0" value="{{ old('sort_order', $client->sort_order ?? 0) }}">
                </div>

                <div class="field full">
                    <label>وصف مختصر</label>
                    <textarea name="description" rows="4">{{ old('description', $client->description) }}</textarea>
                </div>

                <label class="check-field full">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $client->is_active ?? true))>
                    إظهار الجهة على الموقع
                </label>
            </div>

            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.clients.index') }}">رجوع</a>
            </div>
        </form>
    </section>
</x-admin.layouts.app>
