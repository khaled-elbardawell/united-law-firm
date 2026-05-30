<x-admin.layouts.app heading="البروفايل" description="تحديث بيانات حسابك وكلمة المرور الخاصة بك.">
    <section class="grid-2">
        <div class="admin-card">
            <h2>بيانات الحساب</h2>
            <p class="admin-muted-text">هذه البيانات تستخدم لتسجيل الدخول والتعريف بحسابك داخل لوحة التحكم.</p>

            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="profile_name">الاسم</label>
                    <input id="profile_name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="field">
                    <label for="profile_email">البريد الإلكتروني</label>
                    <input id="profile_email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="field">
                    <label>نوع الحساب</label>
                    <input value="{{ $user->role }}" disabled>
                </div>

                <button class="btn-admin btn-gold" type="submit">حفظ البيانات</button>
            </form>
        </div>

        <div class="admin-card">
            <h2>تغيير كلمة المرور</h2>
            <p class="admin-muted-text">استخدم كلمة مرور قوية لحماية الدخول إلى لوحة التحكم.</p>

            <form method="POST" action="{{ route('admin.profile.password') }}">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="current_password">كلمة المرور الحالية</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                </div>

                <div class="field">
                    <label for="password">كلمة المرور الجديدة</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                </div>

                <button class="btn-admin btn-gold" type="submit">تحديث كلمة المرور</button>
            </form>
        </div>
    </section>
</x-admin.layouts.app>
