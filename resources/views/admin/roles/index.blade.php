<x-admin.layouts.app heading="الأدوار والصلاحيات" description="إدارة الأدوار وتحديد الصفحات التي يمكن لكل دور الوصول إليها.">
    <x-slot name="actions"><a class="btn-admin btn-gold" href="{{ route('admin.roles.create') }}">إضافة دور</a></x-slot>
    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث باسم الدور أو الرابط...">
            <select name="active">
                <option value="">كل الحالات</option>
                <option value="1" @selected(request('active') === '1')>فعّال</option>
                <option value="0" @selected(request('active') === '0')>معطل</option>
            </select>
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.roles.index') }}">مسح</a>
        </form>
        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.roles.index', request()->except('view', 'page')) }}">النشطة</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.roles.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>الدور</th><th>Slug</th><th>الصلاحيات</th><th>المستخدمون</th><th>الحالة</th><th>إجراءات</th></tr></thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr class="{{ $role->trashed() ? 'is-trashed' : '' }}">
                            <td><strong>{{ $role->name }}</strong>@if($role->is_system)<br><small>دور نظامي</small>@endif</td>
                            <td dir="ltr">{{ $role->slug }}</td>
                            <td>{{ $role->permissionCountLabel() }}</td>
                            <td>{{ $role->users_count }}</td>
                            <td><x-admin.partials.active :active="$role->is_active" /></td>
                            <td class="actions-row">
                                @if ($role->trashed())
                                    <form method="POST" action="{{ route('admin.roles.restore', $role->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                    <form method="POST" action="{{ route('admin.roles.force-delete', $role->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                                @else
                                    <a class="btn-admin" href="{{ route('admin.roles.edit', $role) }}">تعديل</a>
                                    @unless ($role->is_system || $role->users_count)
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('نقل الدور إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                                    @endunless
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">لا توجد نتائج.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $roles->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
