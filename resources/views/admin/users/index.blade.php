<x-admin.layouts.app heading="المستخدمون" description="ربط مستخدمي لوحة التحكم بالأدوار المعرّفة في نظام الصلاحيات.">
    <x-slot name="actions"><a class="btn-admin btn-gold" href="{{ route('admin.users.create') }}">إضافة مستخدم</a></x-slot>
    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث بالاسم أو البريد...">
            <select name="role">
                <option value="">كل الأدوار</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected((string) request('role') === (string) $role->id)>{{ $role->name }}</option>
                @endforeach
            </select>
            <select name="active">
                <option value="">كل الحالات</option>
                <option value="1" @selected(request('active') === '1')>فعّال</option>
                <option value="0" @selected(request('active') === '0')>معطل</option>
            </select>
            <input type="hidden" name="view" value="{{ request('view') }}">
            <button class="btn-admin btn-gold" type="submit">فلترة</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.users.index') }}">مسح</a>
        </form>
        <div class="admin-tabs">
            <a class="{{ request('view') !== 'trash' ? 'active' : '' }}" href="{{ route('admin.users.index', request()->except('view', 'page')) }}">النشطون</a>
            <a class="{{ request('view') === 'trash' ? 'active' : '' }}" href="{{ route('admin.users.index', array_merge(request()->except('page'), ['view' => 'trash'])) }}">السلة <span>{{ $trashCount }}</span></a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>الاسم</th><th>البريد</th><th>الدور</th><th>الصلاحيات</th><th>الحالة</th><th>إجراءات</th></tr></thead>
                <tbody>
                @forelse ($users as $user)
                    <tr class="{{ $user->trashed() ? 'is-trashed' : '' }}">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->adminRole?->name ?: $user->role }}</td>
                        <td>{{ $user->adminRole?->permissionCountLabel() ?: count($user->adminPermissions()).' صلاحية' }}</td>
                        <td><x-admin.partials.active :active="$user->is_active" /></td>
                        <td class="actions-row">
                            @if ($user->trashed())
                                <form method="POST" action="{{ route('admin.users.restore', $user->id) }}">@csrf @method('PATCH')<button class="btn-admin btn-gold" type="submit">استرجاع</button></form>
                                <form method="POST" action="{{ route('admin.users.force-delete', $user->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف نهائي</button></form>
                            @else
                                <a class="btn-admin" href="{{ route('admin.users.edit', $user) }}">تعديل</a>
                                @if (auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('نقل المستخدم إلى السلة؟')">@csrf @method('DELETE')<button class="btn-admin btn-danger" type="submit">حذف</button></form>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">لا توجد نتائج.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
