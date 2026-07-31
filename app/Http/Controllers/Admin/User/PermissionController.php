<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:100',
        ]);

        $search = $validated['search'] ?? null;

        $query = Permission::where('name', '!=', 'access-admin-panel');


        if ($request->filled('search')) {

           $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $permissions = $query->orderByDesc('id')->paginate(15)->appends(request()->query());

        return view('admin.user.permission.index', compact('permissions'));
    }

    public function status(Permission $permission)
    {
        $permission->status = $permission->status == 0 ? 1 : 0;
        $result = $permission->save();
        if ($result) {
            if ($permission->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
}
