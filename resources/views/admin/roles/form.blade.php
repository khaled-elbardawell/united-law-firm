@php
    $selectedPermissions = old('permissions', $role->permissions ?: []);
@endphp

<x-admin.layouts.app :heading="$role->exists ? 'تعديل دور' : 'إضافة دور'" description="حدد صلاحيات الدور ثم اربط المستخدمين به من صفحة المستخدمين.">
    <section class="admin-card">
        <form method="POST" action="{{ $role->exists ? route('admin.roles.update', $role) : route('admin.roles.store') }}">
            @csrf
            @if ($role->exists) @method('PUT') @endif
            <div class="form-grid">
                <div class="field"><label>اسم الدور</label><input name="name" value="{{ old('name', $role->name) }}" required></div>
                <div class="field">
                    <label>عنوان الرابط (Slug)</label>
                    <input name="slug" dir="ltr" value="{{ old('slug', $role->slug) }}" placeholder="content-editor">
                    <small class="admin-muted-text">للاستخدام الداخلي. إذا تركته فارغاً سيتم توليده تلقائياً.</small>
                </div>
                <label class="check-field"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $role->is_active ?? true))> الدور فعّال</label>
                <div class="field full">
                    <label>الصلاحيات</label>
                    <div class="permissions-grid">
                        <div class="permission-group permission-group--full">
                            <strong>صلاحية كاملة</strong>
                            <label>
                                <input type="checkbox" name="permissions[]" value="*" @checked(in_array('*', $selectedPermissions, true))>
                                <span>كل صفحات لوحة التحكم</span>
                            </label>
                        </div>
                        @foreach ($permissionGroups as $group)
                            <div class="permission-group">
                                <strong>{{ $group['label'] }}</strong>
                                @foreach ($group['permissions'] as $key => $label)
                                    <label>
                                        <input type="checkbox" name="permissions[]" value="{{ $key }}" @checked(in_array('*', $selectedPermissions, true) || in_array($key, $selectedPermissions, true))>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.roles.index') }}">رجوع</a>
            </div>
        </form>
    </section>
</x-admin.layouts.app>
