<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User\Role;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\User\RoleRequest;
use App\Models\User\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:100',
        ]);

        $search = $validated['search'] ?? null;

        $query = Role::where('is_system', 0);

        if ($request->filled('search')) {

            $query->where('name', 'Like', '%' . $search . '%');
        }

        $roles  = $query->orderByDesc('created_at')->paginate(15)->appends($request->query());
        return view('admin.user.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::where('status', 1)->where('name', '!=', 'access-admin-panel')->get();
        return view('admin.user.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $inputs = $request->validated();
        DB::transaction(function () use ($inputs){

            $role = Role::create($inputs);

            $permissions = $inputs['permissions'] ?? [];

            $role->permissions()->sync($permissions);
        });

        return redirect()->route('admin.user.role.index')->with(
            'alert-section-success',
            'New role successfully registered.'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('admin.user.role.edit', compact('role'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        $inputs = $request->all();
        $role->update($inputs);

        return redirect()->route('admin.user.role.index')->with(
            'alert-section-success',
            'Your role was successfully edited.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // جلوگیری از حذف نقش admin
        if ($role->name === 'admin') {
            return back()->with('alert-section-error', 'The admin role cannot be deleted.');
        }
        if ($role->name === 'user') {
            return back()->with('alert-section-error', 'The user role cannot be deleted.');
        }
        $result = $role->Delete();
        return redirect()->route('admin.user.role.index')->with(
            'alert-section-success',
            'Your role was successfully deleted.'
        );
    }
    public function status(Role $role)
    {
        $role->status = $role->status == 0 ? 1 : 0;
        $result = $role->save();
        if ($result) {
            if ($role->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }


    public function permissionForm(Role $role)
    {
        $permissions = Permission::where('status', 1)->where('name', '!=', 'access-admin-panel')->get();
        return view('admin.user.role.permission-form', compact('role', 'permissions'));
    }
    public function permissionUpdate(RoleRequest $request, Role $role)
    {
        $inputs = $request->validated();

        $permissions = $inputs['permissions'] ?? [];
        $role->permissions()->sync($permissions);

        return redirect()->route('admin.user.role.index')->with(
            'alert-section-success',
            'Accessibility was successfully edited.'
        );
    }
}
