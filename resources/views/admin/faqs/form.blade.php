<x-admin.layouts.app :heading="$faq->exists ? 'تعديل سؤال' : 'إضافة سؤال'" description="اكتب السؤال والإجابة كما ستظهر للزائر.">
    <section class="admin-card">
        <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
            @csrf
            @if ($faq->exists) @method('PUT') @endif
            <div class="form-grid">
                <div class="field full"><label>السؤال</label><input name="question" value="{{ old('question', $faq->question) }}" required></div>
                <div class="field"><label>الترتيب</label><input name="sort_order" type="number" min="0" value="{{ old('sort_order', $faq->sort_order ?? 0) }}"></div>
                <label class="check-field"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $faq->is_active ?? true))> إظهار السؤال</label>
                <div class="field full"><label>الإجابة</label><textarea name="answer" required>{{ old('answer', $faq->answer) }}</textarea></div>
            </div>
            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.faqs.index') }}">رجوع</a>
            </div>
        </form>
    </section>
</x-admin.layouts.app>
