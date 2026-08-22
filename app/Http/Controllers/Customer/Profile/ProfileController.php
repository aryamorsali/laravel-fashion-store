<?php

namespace App\Http\Controllers\Customer\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Profile\UserProfileRequest;
use App\Http\Services\Image\ImageService;
use App\Models\Market\Product;
use App\Models\Market\Province;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('customer.profile.profile');
    }


    public function updateProfile(UserProfileRequest $request, ImageService $imageService)
    {
        $data = $request->validated();
        $user = $request->user();


        if ($request->hasFile('profile_photo_path')) {
            if (!empty($user->profile_photo_path)) {
                $imageService->deleteImage($user->profile_photo_path);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'users');
            $avatarPath = $imageService->createAvatarAndSave(
                $request->file('profile_photo_path')
            );

            if ($avatarPath === false) {
                return redirect()->back()->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $data['profile_photo_path'] = $avatarPath;
        }


        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'national_code' => $data['national_code'],
            'profile_photo_path' => $data['profile_photo_path'] ?? $user->profile_photo_path,
        ]);

        return redirect()->back()->with(
            'toast-success',
            'User information was successfully updated.'
        );
    }


    public function myAddresses()
    {
        $provinces = Province::with('cities')->get();
        $addresses = Auth::user()->addresses()->with(['province', 'city'])->get();

        return view('customer.profile.address', compact('addresses', 'provinces'));
    }

    public function myFavorites()
    {
        $products = Auth::user()->favoriteProducts()->latest('likes.created_at')->paginate(10);


        return view('customer.profile.favorites', compact('products'));
    }

    public function deleteMyFavorite(Product $product)
    {
        $user = Auth::user();
        $user->favoriteProducts()->detach($product->id);  // حذف کن
        return redirect()->route('customer.profile.my-favorites')->with('toast-success', 'Product successfully removed from wishlist');
    }
}
