<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\SettingRequest;
use App\Http\Services\Image\ImageService;
use Illuminate\Http\Request;
use App\Models\Setting\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('admin.setting.index', compact('settings'));
    }

    public function edit(Setting $setting)
    {
        return view('admin.setting.edit', compact('setting'));
    }

    public function update(SettingRequest $request, Setting $setting, ImageService $imageService)
    {
        $inputs = $request->validated();

        if ($setting->key === 'site_logo' && $request->hasFile('value')) {
            if (!empty($setting->value) && file_exists(public_path($setting->value))) {
                $imageService->deleteImage($setting->value);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'setting');
            $result = $imageService->save($request->file('value'));
            if ($result === false) {
                return redirect()->route('admin.setting.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['value'] = $result;
            $setting->update($inputs);
        } else {
            $setting->update(['value' => $request->value]);
        }
        return redirect(route('admin.setting.index'))->with(
            'alert-section-success',
            'setting editing completed successfully.'
        );
    }
    public function status(Setting $setting)
    {
        $setting->status = $setting->status == 0 ? 1 : 0;
        $setting->save();
        return redirect()->back()->with(
            'alert-section-success',
            'Status successifuly changed.'
        );
    }
}
