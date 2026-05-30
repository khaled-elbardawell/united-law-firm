<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->view === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->role))
            ->when($request->filled('active'), fn ($query) => $query->where('is_active', $request->active))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'trashCount' => User::onlyTrashed()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User()]);
    }

    public function store(Request $request)
    {
        User::create($this->validated($request));

        return redirect()->route('admin.users.index')->with('success', 'تم إنشاء الحساب.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validated($request, $user);
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'تم تحديث الحساب.');
    }

    public function destroy(User $user)
    {
        abort_if(auth()->id() === $user->id, 422, 'لا يمكن حذف حسابك الحالي.');
        $user->delete();

        return back()->with('success', 'تم نقل الحساب إلى سلة المحذوفات.');
    }

    public function restore(int $user)
    {
        User::onlyTrashed()->findOrFail($user)->restore();

        return back()->with('success', 'تم استرجاع الحساب بنجاح.');
    }

    public function forceDelete(int $user)
    {
        abort_if(auth()->id() === $user, 422, 'لا يمكن حذف حسابك الحالي.');
        User::onlyTrashed()->findOrFail($user)->forceDelete();

        return back()->with('success', 'تم حذف الحساب نهائياً.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,manager,editor'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
