<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notification\SMSRequest;
use App\Models\Notification\SMS;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sms = SMS::all();
        return view('admin.notification.sms.index', compact('sms'));
    }

    public function create()
    {
        return view('admin.notification.sms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SMSRequest $request)
    {
        $inputs = $request->validated();
        if (!empty($inputs['published_at'])) {

            $publishedAt = Carbon::createFromFormat('Y-m-d H:i', $inputs['published_at']);
            if ($publishedAt <= Carbon::now()) {
                return back()->with('alert-section-error', 'Your SMS notification time has already passed.');
            }
            $inputs['published_at'] = $publishedAt;
            $inputs['status'] = 'scheduled';
        } else {
            $inputs['published_at'] = null;
            $inputs['status'] = 'draft';
        }

        SMS::create($inputs);
        return redirect()->route('admin.notification.sms.index')->with(
            'alert-section-success',
            'Your new sms was successfully registered.'
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
    public function edit(SMS $sms)
    {
        return view('admin.notification.sms.edit', compact('sms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SMSRequest $request, SMS $sms)
    {
        $inputs = $request->validated();

        if (!empty($inputs['published_at'])) {
            $publishedAt = Carbon::createFromFormat('Y-m-d H:i', $inputs['published_at']);
            if ($publishedAt <= Carbon::now()) {
                return back()->with('alert-section-error', 'Your sms notification time has already passed.');
            }
            if ($sms->status !== 'sent') {
                $inputs['status'] = 'scheduled';
            }

            $inputs['published_at'] = $publishedAt;
        } else {
            if ($sms->status !== 'sent') {
                $inputs['status'] = 'draft';
            }
            $inputs['published_at'] = null;
        }

        $sms->update($inputs);
        return redirect()->route('admin.notification.sms.index')->with(
            'alert-section-success',
            'sms notification updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SMS $sms)
    {
        $sms->delete();
        return redirect(route('admin.notification.sms.index'))->with(
            'alert-section-success',
            'sms notification successfully deleted.'
        );
    }
    public function send(SMS $sms)
    {
        return redirect(route('admin.notification.sms.index'))->with(
            'alert-section-success',
            'sms notification successfully deleted.'
        );
    }
}
