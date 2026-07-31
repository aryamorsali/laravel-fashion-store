<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\User\CustomerRequest;
use App\Http\Services\Image\ImageService;
use App\Models\User;
use App\Models\User\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
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

        $query = User::whereHas('roles', function ($r) {
            $r->where('name', 'user');
        });

        if ($request->filled('search')) {

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());
        return view('admin.user.customer.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request, ImageService $imageService)
    {
        $inputs = $request->validated();
        $inputs['password'] = Hash::make($request->password);
        if ($request->hasFile('profile_photo_path')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'users');
            $result = $imageService->save($request->file('profile_photo_path'));

            if ($result === false) {
                return redirect()->route('admin.user.customer.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['profile_photo_path'] = $result;
        }

        DB::transaction(function () use ($inputs) {

            $role =  Role::where('name', 'user')->firstOrFail();
            $user = User::create($inputs);

            $user->roles()->sync($role->id);
        });

        return redirect()->route('admin.user.customer.index')->with(
            'alert-section-success',
            'New user successfully registered.'
        );
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
    public function edit(User $customer)
    {
        return view('admin.user.customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, User $customer, ImageService $imageService)
    {
        $inputs = $request->validated();

        // اگر کاربر فایل جدید آپلود کرد
        if ($request->hasFile('profile_photo_path')) {
            if (!empty($customer->profile_photo_path)) {
                $imageService->deleteImage($customer->profile_photo_path);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'users');
            $result = $imageService->save($request->file('profile_photo_path'));
            if ($result === false) {
                return redirect()->route('admin.user.customer.index')->with(
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
        $customer->update($inputs);
        return redirect(route('admin.user.customer.index'))->with(
            'alert-section-success',
            'User editing completed successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $customer)
    {
        if ($customer->is_owner) {
            return back()->with('alert-section-error', 'This user cannot be deleted.');
        }

        $customer->delete();
        return redirect()
            ->route('admin.user.customer.index')
            ->with('alert-section-success', 'User successfully deleted.');
    }

    public function activation(User $customer)
    {
        $customer->activation = $customer->activation == 0 ? 1 : 0;
        $result = $customer->save();
        if ($result) {
            if ($customer->activation == 0) {
                return response()->json(['activation' => true, 'checked' => false]);
            } else {
                return response()->json(['activation' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['activation' => false]);
        }
    }
}
