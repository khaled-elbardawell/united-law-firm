<x-admin.layouts.app heading="تفاصيل طلب التواصل" description="تحديث الحالة وكتابة ملاحظات داخلية.">
    <section class="grid-2">
        <article class="admin-card">
            <h2>{{ $requestItem->name }}</h2>
            <p><strong>الهاتف:</strong> <span dir="ltr">{{ $requestItem->phone }}</span></p>
            <p><strong>البريد:</strong> {{ $requestItem->email ?: 'غير مدخل' }}</p>
            <p><strong>التاريخ:</strong> {{ $requestItem->created_at->format('Y-m-d H:i') }}</p>
            <p><strong>الحالة:</strong> <x-admin.partials.status :status="$requestItem->status" :labels="$statuses" /></p>
            <hr>
            <p>{{ $requestItem->message }}</p>
        </article>
        <article class="admin-card">
            <form method="POST" action="{{ route('admin.contact-requests.update', $requestItem) }}">
                @csrf @method('PUT')
                <div class="field">
                    <label>الحالة</label>
                    <select name="status" required>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $requestItem->status) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label>ملاحظات داخلية</label><textarea name="admin_notes">{{ old('admin_notes', $requestItem->admin_notes) }}</textarea></div>
                <div class="actions-row">
                    <button class="btn-admin btn-gold" type="submit">تحديث</button>
                    <a class="btn-admin btn-muted" href="{{ route('admin.contact-requests.index') }}">رجوع</a>
                </div>
            </form>
            <form method="POST" action="{{ route('admin.contact-requests.destroy', $requestItem) }}" onsubmit="return confirm('حذف الطلب؟')" style="margin-top:14px;">
                @csrf @method('DELETE')
                <button class="btn-admin btn-danger" type="submit">حذف الطلب</button>
            </form>
        </article>
    </section>
</x-admin.layouts.app>
