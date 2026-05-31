<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use App\Support\AdminPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminRoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = AdminRole::query()
            ->withCount('users')
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('slug', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('active'), fn ($query) => $query->where('is_active', $request->active))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.roles.index', [
            'roles' => $roles,
            'trashCount' => AdminRole::onlyTrashed()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.roles.form', [
            'role' => new AdminRole(['is_active' => true]),
            'permissionGroups' => AdminPermissions::groups(),
        ]);
    }

    public function store(Request $request)
    {
        AdminRole::create($this->validated($request));

        return redirect()->route('admin.roles.index')->with('success', 'تم إنشاء الدور بنجاح.');
    }

    public function edit(AdminRole $role)
    {
        return view('admin.roles.form', [
            'role' => $role,
            'permissionGroups' => AdminPermissions::groups(),
        ]);
    }

    public function update(Request $request, AdminRole $role)
    {
        $role->update($this->validated($request, $role));

        return redirect()->route('admin.roles.index')->with('success', 'تم تحديث الدور بنجاح.');
    }

    public function destroy(AdminRole $role)
    {
        abort_if($role->is_system, 422, 'لا يمكن حذف دور نظامي.');
        abort_if($role->users()->exists(), 422, 'لا يمكن حذف دور مرتبط بمستخدمين.');

        $role->delete();

        return back()->with('success', 'تم نقل الدور إلى السلة.');
    }

    public function restore(int $role)
    {
        AdminRole::onlyTrashed()->findOrFail($role)->restore();

        return back()->with('success', 'تم استرجاع الدور بنجاح.');
    }

    public function forceDelete(int $role)
    {
        $role = AdminRole::onlyTrashed()->withCount('users')->findOrFail($role);

        abort_if($role->is_system || $role->users_count > 0, 422, 'لا يمكن حذف هذا الدور نهائياً.');

        $role->forceDelete();

        return back()->with('success', 'تم حذف الدور نهائياً.');
    }

    private function validated(Request $request, ?AdminRole $role = null): array
    {
        $request->merge(['slug' => Str::lower(trim((string) $request->input('slug')))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('admin_roles', 'slug')->ignore($role?->id),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(array_merge(['*'], AdminPermissions::all()))],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'slug.regex' => 'الرابط يجب أن يحتوي أحرفاً إنجليزية صغيرة أو أرقاماً وشرطة (-) فقط.',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']) ?: Str::random(8);
        $data['permissions'] = AdminPermissions::sanitize($data['permissions'] ?? []);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
