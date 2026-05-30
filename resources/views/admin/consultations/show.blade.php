<x-admin.layouts.app heading="تفاصيل الاستشارة" description="متابعة الطلب وتحديث حالته.">
    <section class="grid-2">
        <article class="admin-card">
            <h2>{{ $consultation->name }}</h2>
            <p><strong>الهاتف:</strong> <span dir="ltr">{{ $consultation->phone }}</span></p>
            <p><strong>البريد:</strong> {{ $consultation->email ?: 'غير مدخل' }}</p>
            <p><strong>الخدمة:</strong> {{ $consultation->service }}</p>
            <p><strong>الموعد المفضل:</strong> {{ $consultation->preferred_date?->format('Y-m-d') ?: 'غير محدد' }}</p>
            <p><strong>طريقة التواصل:</strong> {{ $consultation->contact_method }}</p>
            <p><strong>الأولوية:</strong> {{ $consultation->priority }}</p>
            <p><strong>الحالة:</strong> <x-admin.partials.status :status="$consultation->status" :labels="$statuses" /></p>
            <hr>
            <p>{{ $consultation->details }}</p>
            @if ($consultation->attachments)
                <h3>المرفقات</h3>
                @foreach ($consultation->attachments as $file)
                    <a class="btn-admin" href="{{ asset('storage/'.$file) }}" target="_blank">فتح مرفق</a>
                @endforeach
            @endif
        </article>
        <article class="admin-card">
            <form method="POST" action="{{ route('admin.consultations.update', $consultation) }}">
                @csrf @method('PUT')
                <div class="field">
                    <label>الحالة</label>
                    <select name="status" required>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $consultation->status) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label>ملاحظات داخلية</label><textarea name="admin_notes">{{ old('admin_notes', $consultation->admin_notes) }}</textarea></div>
                <div class="actions-row">
                    <button class="btn-admin btn-gold" type="submit">تحديث</button>
                    <a class="btn-admin btn-muted" href="{{ route('admin.consultations.index') }}">رجوع</a>
                </div>
            </form>
            <form method="POST" action="{{ route('admin.consultations.destroy', $consultation) }}" onsubmit="return confirm('حذف الاستشارة؟')" style="margin-top:14px;">
                @csrf @method('DELETE')
                <button class="btn-admin btn-danger" type="submit">حذف الطلب</button>
            </form>
        </article>
    </section>
</x-admin.layouts.app>
