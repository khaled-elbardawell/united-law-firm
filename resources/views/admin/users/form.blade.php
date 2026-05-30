<x-admin.layouts.app :heading="$user->exists ? 'تعديل مستخدم' : 'إضافة مستخدم'" description="بيانات حساب الدخول إلى لوحة التحكم.">
    <section class="admin-card">
        <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
            @csrf
            @if ($user->exists) @method('PUT') @endif
            <div class="form-grid">
                <div class="field"><label>الاسم</label><input name="name" value="{{ old('name', $user->name) }}" required></div>
                <div class="field"><label>البريد الإلكتروني</label><input name="email" type="email" value="{{ old('email', $user->email) }}" required></div>
                <div class="field">
                    <label>الصلاحية</label>
                    <select name="role" required>
                        @foreach (['admin' => 'مدير', 'manager' => 'مسؤول', 'editor' => 'محرر'] as $key => $label)
                            <option value="{{ $key }}" @selected(old('role', $user->role ?? 'admin') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="check-field"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true))> الحساب فعّال</label>
                <div class="field"><label>كلمة المرور</label><input name="password" type="password" @required(! $user->exists)></div>
                <div class="field"><label>تأكيد كلمة المرور</label><input name="password_confirmation" type="password" @required(! $user->exists)></div>
            </div>
            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.users.index') }}">رجوع</a>
            </div>
        </form>
    </section>
</x-admin.layouts.app>
