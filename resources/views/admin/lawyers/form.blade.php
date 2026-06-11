<x-admin.layouts.app :heading="$lawyer->exists ? 'تعديل محامي' : 'إضافة محامي'" description="إدارة بطاقة المحامي وصفحة السيرة المهنية التي تظهر في الموقع.">
    @php
        $lines = fn (string $key) => old($key, implode("\n", $lawyer->{$key} ?? []));
    @endphp

    <section class="admin-card">
        <form method="POST" action="{{ $lawyer->exists ? route('admin.lawyers.update', $lawyer) : route('admin.lawyers.store') }}" enctype="multipart/form-data">
            @csrf
            @if ($lawyer->exists) @method('PUT') @endif

            <div class="settings-section-head">
                <div>
                    <span>البيانات الأساسية</span>
                    <h2>بطاقة المحامي</h2>
                </div>
            </div>

            <div class="form-grid">
                <div class="field"><label>الاسم</label><input name="name" value="{{ old('name', $lawyer->name) }}" required></div>
                <div class="field">
                    <label>عنوان الرابط (Slug)</label>
                    <input name="slug" dir="ltr" value="{{ old('slug', $lawyer->slug) }}" placeholder="ahmad-saleh">
                    <small class="admin-muted-text">يؤثر على رابط صفحة المحامي في الموقع. استخدم أحرفاً إنجليزية صغيرة وأرقاماً وشرطة فقط. إذا تركته فارغاً سيتم توليده تلقائياً من اسم المحامي.</small>
                </div>
                <div class="field"><label>المنصب</label><input name="position" value="{{ old('position', $lawyer->position) }}" required></div>
                <div class="field">
                    <label>تصنيف الفريق</label>
                    <select name="team_category">
                        <option value="">غير مصنف</option>
                        @foreach (\App\Models\Lawyer::TEAM_CATEGORIES as $key => $label)
                            <option value="{{ $key }}" @selected(old('team_category', $lawyer->team_category) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label>التخصص الرئيسي</label><input name="specialty" value="{{ old('specialty', $lawyer->specialty) }}"></div>
                <div class="field"><label>رقم مزاولة / قيد النقابة</label><input name="bar_number" value="{{ old('bar_number', $lawyer->bar_number) }}"></div>
                <div class="field"><label>سنوات الخبرة</label><input name="years_experience" type="number" min="0" max="80" value="{{ old('years_experience', $lawyer->years_experience) }}"></div>

                <div class="field">
                    <label>صورة المحامي</label>
                    <input name="photo_file" type="file" accept="image/*">
                    @if ($lawyer->photo)
                        <div class="settings-logo-row">
                            <img src="{{ str_starts_with($lawyer->photo, 'http') ? $lawyer->photo : asset($lawyer->photo) }}" alt="{{ $lawyer->name }}">
                            <small>اترك الحقل فارغاً للاحتفاظ بالصورة الحالية.</small>
                        </div>
                    @else
                        <small class="admin-muted-text">اختياري - JPG / PNG / WEBP بحد أقصى 2MB.</small>
                    @endif
                </div>

                <div class="field">
                    <label>ملف السيرة الذاتية</label>
                    <input name="cv_file_upload" type="file" accept=".pdf,.doc,.docx">
                    @if ($lawyer->cv_file)
                        <small><a href="{{ asset($lawyer->cv_file) }}" target="_blank" rel="noopener">عرض الملف الحالي</a></small>
                    @else
                        <small class="admin-muted-text">اختياري - PDF أو Word بحد أقصى 5MB.</small>
                    @endif
                </div>

                <div class="field"><label>البريد</label><input name="email" type="email" value="{{ old('email', $lawyer->email) }}"></div>
                <div class="field"><label>الهاتف</label><input name="phone" value="{{ old('phone', $lawyer->phone) }}"></div>
                <div class="field"><label>LinkedIn</label><input name="linkedin_url" dir="ltr" value="{{ old('linkedin_url', $lawyer->linkedin_url) }}" placeholder="linkedin.com/in/name"></div>
                <div class="field"><label>موقع أو ملف خارجي</label><input name="website_url" dir="ltr" value="{{ old('website_url', $lawyer->website_url) }}" placeholder="example.com"></div>
                <div class="field"><label>الترتيب</label><input name="sort_order" type="number" min="0" value="{{ old('sort_order', $lawyer->sort_order ?? 0) }}"></div>
                <div class="field"><label>وسوم مفصولة بفواصل</label><input name="tags" value="{{ old('tags', implode(', ', $lawyer->tags ?? [])) }}" placeholder="قانون تجاري، تحكيم، عقود"></div>

                <div class="field full"><label>نبذة قصيرة للبطاقة</label><textarea name="bio" rows="4" required>{{ old('bio', $lawyer->bio) }}</textarea></div>
                <div class="field full"><label>الملخص المهني في صفحة CV</label><textarea name="professional_summary" rows="5">{{ old('professional_summary', $lawyer->professional_summary) }}</textarea></div>
            </div>

            <div class="settings-section-head" style="margin-top: 28px;">
                <div>
                    <span>صفحة السيرة المهنية</span>
                    <h2>تفاصيل CV</h2>
                </div>
            </div>

            <div class="form-grid">
                <div class="field full"><label>الخبرات العملية - كل سطر عنصر</label><textarea name="experience" rows="7">{{ $lines('experience') }}</textarea></div>
                <div class="field full"><label>التعليم والمؤهلات - كل سطر عنصر</label><textarea name="education" rows="5">{{ $lines('education') }}</textarea></div>
                <div class="field full"><label>الشهادات والدورات - كل سطر عنصر</label><textarea name="certifications" rows="5">{{ $lines('certifications') }}</textarea></div>
                <div class="field full"><label>الترافع أمام المحاكم والجهات - كل سطر عنصر</label><textarea name="court_admissions" rows="4">{{ $lines('court_admissions') }}</textarea></div>
                <div class="field full"><label>العضويات المهنية - كل سطر عنصر</label><textarea name="memberships" rows="4">{{ $lines('memberships') }}</textarea></div>
                <div class="field full"><label>الجوائز والإنجازات - كل سطر عنصر</label><textarea name="awards" rows="4">{{ $lines('awards') }}</textarea></div>
                <div class="field full"><label>اللغات - كل سطر عنصر</label><textarea name="languages" rows="3">{{ $lines('languages') }}</textarea></div>

                <label class="check-field full"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $lawyer->is_active ?? true))> إظهار المحامي في الموقع</label>
            </div>

            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.lawyers.index') }}">رجوع</a>
            </div>
        </form>
    </section>
</x-admin.layouts.app>
