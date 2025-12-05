<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\HomeBoxRequest;
use App\Http\Services\Image\ImageService;
use App\Models\Market\HomeBox;
use App\Models\Market\ProductCategory;
use Illuminate\Http\Request;

class HomeBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boxes = HomeBox::orderby('created_at', 'desc')->get();
        return view('admin.market.home-box.index', compact('boxes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (HomeBox::count() >= 3) {
            return redirect()->route('admin.market.home-box.index')
                ->with('alert-section-error', 'فقط مجاز به ساخت سه باکس هستید.');
        }
        $categories = ProductCategory::where('status', 1)->get();
        return view('admin.market.home-box.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HomeBoxRequest $request, ImageService $imageService)
    {
        if (HomeBox::count() >= 3) {
            return redirect()->route('admin.market.home-box.index')
                ->with('alert-section-error', 'حداکثر ۳ باکس می‌توانید بسازید.');
        }
        $inputs = $request->validated();

        if ($request->hasFile('image')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'home-box');
            $result = $imageService->save($request->file('image'));

            if ($result === false) {
                return redirect()->back()->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['image'] = $result;
        }

        $box = HomeBox::create($inputs);
        return redirect()->route('admin.market.home-box.index')->with(
            'alert-section-success',
            'Your new box was successfully registered.'
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
    public function edit(HomeBox $homeBox)
    {
        $categories = ProductCategory::where('status', 1)->get();
        return view('admin.market.home-box.edit', compact('categories', 'homeBox'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HomeBoxRequest $request, HomeBox $homeBox, ImageService $imageService)
    {
        $inputs = $request->validated();

        if ($request->hasFile('image')) {
            if (!empty($homeBox->image) && file_exists(public_path($homeBox->image))) {
                $imageService->deleteImage($homeBox->image);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'home-box');
            $result = $imageService->save($request->file('image'));
            if ($result === false) {
                return redirect()->route('admin.market.home-box.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['image'] = $result;
        }
        $homeBox->update($inputs);
        return redirect(route('admin.market.home-box.index'))->with(
            'alert-section-success',
            'Box Category editing completed successfully.'
        );
    }
    public function destroy(HomeBox $homeBox)
    {
        $homeBox->delete();
        return redirect()->route('admin.market.home-box.index')->with(
            'alert-section-success',
            'Box Category successfully deleted.'
        );
    }

    public function status(HomeBox $homeBox)
    {
        $homeBox->status = $homeBox->status == 0 ? 1 : 0;
        $result = $homeBox->save();
        if ($result) {
            if ($homeBox->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
}
