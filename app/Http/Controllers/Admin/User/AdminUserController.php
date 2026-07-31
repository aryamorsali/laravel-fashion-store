<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\AdminUserRequest;
use App\Http\Services\Image\ImageService;
use App\Models\User;
use App\Models\User\Permission;
use App\Models\User\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:100',
        ]);

        $search = $validated['search'] ?? null;

        // فقط ادمین های سایت
        $query = User::where(function ($q) {
            $q->whereHas('roles', function ($r) {
                $r->where('name', 'admin');
            })->orWhere('is_owner', 1);
        });

        if ($request->filled('search')) {

            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $search . '%');
            });
        }

        $admins = $query->orderByDesc('created_at')->paginate(15)->appends(request()->query());

        return view('admin.user.admin-user.index', compact('admins'));
    }


    public function role(User $admin)
    {
        $roles = Role::where('status', 1)->where('is_system', 0)->get();
        return view('admin.user.admin-user.roleForm', compact('admin', 'roles'));
    }


    public function roleStore(Request $request, User $admin)
    {
        if ($admin->is_owner) {
            return back()->with('alert-section-error', 'Cannot modify owner roles or permissions.');
        }

        $validated = $request->validate([
            'roles' => 'required|array|min:1',
            'roles.*' => 'integer|exists:roles,id'
        ]);
        $admin->roles()->attach($validated['roles']);

        return redirect()->route('admin.user.admin.index')->with(
            'alert-section-success',
            'The admin role was successfully updated.'
        );
    }

    public function addPermission(User $admin)
    {
        $admin->load(['roles.permissions']);

        // جلوگیری از نشان دادن دسترسی های کاربر از طرق رول خودش
        $userHasThisPermissions = collect();

        foreach ($admin->roles as $role) {
            $userHasThisPermissions = $userHasThisPermissions->merge($role->permissions);
        }

        $permissionIds = $userHasThisPermissions->pluck('id');

        $permissions = Permission::where('status', 1)->where('name', '!=', 'access-admin-panel')->whereNotIn('id', $permissionIds)->get();
        return view('admin.user.admin-user.addPermissionForm', compact('admin', 'permissions'));
    }
    public function addPermissionStore(Request $request, User $admin)
    {
        // نمی‌تونه owner تغییر کنه
        if ($admin->is_owner) {
            return back()->with('alert-section-error', 'Cannot modify owner roles or permissions.');
        }

        if ($admin->roles()->count() === 1 && $admin->hasRole('user')) {
            return back()->with('alert-section-error', 'Cannot assign permissions to normal users.');
        }

        $inputs = $request->validate([
            'permissions' => 'nullable|array|min:1',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);
        $permissions = $inputs['permissions'] ?? null;
        if (empty($permissions)) {
            $admin->permissions()->detach();
        } else {
            $allowedPermissions = Permission::whereIn('id', $permissions)
                ->where('name', '!=', 'access-admin-panel')->get();


            if ($allowedPermissions->isEmpty()) {
                return back()->with('alert-section-error', 'No valid permissions selected.');
            }

            $admin->permissions()->sync($allowedPermissions);
        }


        return redirect()->route('admin.user.admin.index')->with(
            'alert-section-success',
            'The admin permissions was successfully updated.'
        );
    }


    public function permission(User $admin)
    {
        $permissions = collect();

        foreach ($admin->roles as $role) {
            $permissions = $permissions->merge($role->permissions);
        }

        $permissions = $permissions->merge($admin->permissions)->unique('id');

        return view('admin.user.admin-user.adminPermissions', compact('permissions'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.admin-user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminUserRequest $request, ImageService $imageService)
    {

        $inputs = $request->validated();

        if ($request->hasFile('profile_photo_path')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'users');
            $result = $imageService->save($request->file('profile_photo_path'));
            if ($result === false) {
                return redirect()->route('admin.user.admin.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['profile_photo_path'] = $result;
        }

        try {
            DB::transaction(function () use ($inputs) {

                $user = User::create([
                    'first_name' => $inputs['first_name'],
                    'last_name' => $inputs['last_name'],
                    'email' => $inputs['email'],
                    'password' => Hash::make($inputs['password']),
                    'mobile' => $inputs['mobile'],
                    'profile_photo_path' => $inputs['profile_photo_path'],
                    'activation' => $inputs['activation'],
                ]);
                $role = Role::where('name', 'admin')->where('is_system', 1)->firstOrFail();
                $user->roles()->sync($role->id);
            });
            return redirect()->route('admin.user.admin.index')->with(
                'alert-section-success',
                'New admin successfully registered.'
            );
        } catch (\Exception $e) {
            return redirect()->route('admin.user.admin.index')->with(
                'alert-section-error',
                'An error occurred while recording the information: ' . $e->getMessage()
            );
        }
    }

    public function addAdmin()
    {
        $roles = Role::where('status', 1)->where('is_system', 0)->get();

        $users = User::where('is_owner', 0)->whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->get();

        return view('admin.user.admin-user.add', compact('users', 'roles'));
    }

    public function storeAddAdmin(Request $request)
    {

        $inputs = $request->validate([
            'users' => 'required|array|min:1',
            'users.*' => 'integer|exists:users,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'integer|exists:roles,id'
        ]);

        DB::transaction(function () use ($inputs) {

            $adminRole = Role::where('name', 'admin')->firstOrFail();
            $users = User::whereIn('id', $inputs['users'])->get();

            $roles = collect($inputs['roles'])
                ->push($adminRole->id)
                ->unique()
                ->all();

            foreach ($users as $user) {
                $user->roles()->sync($roles);
            }
        });

        return redirect()->route('admin.user.admin.index')->with('alert-section-success', 'Users promoted.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $customer)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin)
    {
        return view('admin.user.admin-user.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminUserRequest $request, User $admin, ImageService $imageService)
    {
        if ($admin->is_owner) {
            return back()->with('alert-section-error', 'Cannot edit system owner.');
        }

        $inputs = $request->validated();

        // اگر کاربر فایل جدید آپلود کرد
        if ($request->hasFile('profile_photo_path')) {
            if (!empty($admin->profile_photo_path)) {
                $imageService->deleteImage($admin->profile_photo_path);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'users');
            $result = $imageService->save($request->file('profile_photo_path'));
            if ($result === false) {
                return redirect()->route('admin.user.admin.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['profile_photo_path'] = $result;
        }
        if ($request->password) {
            $inputs['password'] = Hash::make($request->password);
        } else {
            unset($inputs['password']);
        }
        $admin->update($inputs);
        return redirect(route('admin.user.admin.index'))->with(
            'alert-section-success',
            'User editing completed successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        if ($admin->is_owner) {
            return back()->with('alert-section-error', 'The system owner cannot be deleted.');
        }

        if (!Auth::user()->is_owner) {
            return back()->with('alert-section-error', 'Only the system owner can delete admin.');
        }

        if ($admin->id == Auth::id()) {
            return back()->with('alert-section-error', 'You cannot delete yourself.');
        }

        // حذف عکس پروفایل اگر موجود است
        if (!empty($admin->profile_photo_path)) {
            app(ImageService::class)->deleteImage($admin->profile_photo_path);
        }

        $admin->delete();
        return redirect(route('admin.user.admin.index'))->with(
            'alert-section-success',
            'Admin successfully deleted.'
        );
    }

    public function activation(User $admin)
    {
        if ($admin->is_owner) {
            return back()->with('alert-section-error', 'Owner status cannot be changed.');
        }

        $admin->activation = $admin->activation == 0 ? 1 : 0;
        $result = $admin->save();
        if ($result) {
            if ($admin->activation == 0) {
                return response()->json(['activation' => true, 'checked' => false]);
            } else {
                return response()->json(['activation' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['activation' => false]);
        }
    }

    public function revokeAdmin(User $admin)
    {
        if ($admin->is_owner) {
            return back()->with('alert-section-error', 'Cannot modify owner.');
        }

        // حذف تمام نقش‌های مدیریتی و فقط دادن نقش user
        $userRole = Role::where('name', 'user')->where('is_system', 1)->firstOrFail();
        $admin->roles()->sync($userRole->id);
        $admin->save();

        return redirect(route('admin.user.admin.index'))->with(
            'alert-section-success',
            'Manager successfully demoted.'
        );
    }
}
