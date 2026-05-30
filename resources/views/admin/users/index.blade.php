<x-admin.layouts.app heading="المستخدمون" description="إنشاء حسابات لوحة التحكم وإدارتها مع فلترة وسلة محذوفات.">
    <x-slot name="actions"><a class="btn-admin btn-gold" href="{{ route('admin.users.create') }}">إضافة مستخدم</a></x-slot>
    <section class="admin-card">
        <form method="GET" class="admin-filters">
            <input name="q" value="{{ request('q') }}" placeholder="بحث بالاسم أو البريد...">
            <select name="role"><option value="">كل الصلاحيات</option><option value="admin" @selected(request('role') === 'admin')>مدير</option><option value="manager" @selected(request('role') === 'manager')>مسؤول</option><option value="editor" @selected(request('role') === 'editor')>محرر</option></select>
            <select name="active"><option value="">كل الحالات</option><option value="1" @selected(request('active') === '1')>فعّال</option><option value="0" @selected(request('active') === '0')>معطل</option></select>
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
                <thead><tr><th>الاسم</th><th>البريد</th><th>الصلاحية</th><th>الحالة</th><th>إجراءات</th></tr></thead>
                <tbody>
                @forelse ($users as $user)
                    <tr class="{{ $user->trashed() ? 'is-trashed' : '' }}">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
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
                    <tr><td colspan="5">لا توجد نتائج.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->links('vendor.pagination.admin') }}
    </section>
</x-admin.layouts.app>
