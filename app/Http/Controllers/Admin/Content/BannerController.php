<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Content\BannerRequest;
use App\Http\Services\Image\ImageService;
use App\Models\Content\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::all();
        return view('admin.content.banner.index', compact('banners'));
    }
    public function create()
    {
        return view('admin.content.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerRequest $request, ImageService $imageService)
    {
        $inputs = $request->validated();
        if ($request->hasFile('image')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'banner');
            $result = $imageService->save($request->file('image'));

            if ($result === false) {
                return redirect()->route('admin.content.banner.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['image'] = $result;
        }

        $banner = Banner::create($inputs);
        return redirect()->route('admin.content.banner.index')->with(
            'alert-section-success',
            'Your new banner was successfully registered.'
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
    public function edit(Banner $banner)
    {
        return view('admin.content.banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BannerRequest $request, Banner $banner, ImageService $imageService)
    {
        $inputs = $request->validated();

        if ($request->hasFile('image')) {
          if (!empty($banner->image) && file_exists(public_path($banner->image))) {
    $imageService->deleteImage($banner->image);
}
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'banner');
            $result = $imageService->save($request->file('image'));
            if ($result === false) {
                return redirect()->route('admin.content.banner.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['image'] = $result;
        } 
        $banner->update($inputs);
        return redirect(route('admin.content.banner.index'))->with(
            'alert-section-success',
            'banner editing completed successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.content.banner.index')->with(
            'alert-section-success',
            'banner successfully deleted.'
        );
    }

    public function status(Banner $banner)
    {
        $banner->status = $banner->status == 0 ? 1 : 0;
        $result = $banner->save();
        if ($result) {
            if ($banner->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
}
