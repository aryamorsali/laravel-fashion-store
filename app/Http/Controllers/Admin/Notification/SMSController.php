<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notification\SMSRequest;
use App\Jobs\SendSmsToUsers;
use App\Models\Notification\SMS;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SMSController extends Controller
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

        $query = SMS::query();

        if ($request->filled('search')) {

            $query->where('title', 'LIKE', '%' . $search . '%')->orWhere('body', 'LIKE', '%' . $search . '%');
        }

        $sms = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());

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
            $inputs['status'] = 'scheduled';

            $inputs['published_at'] = $publishedAt;
        } else {
            $inputs['status'] = 'draft';
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
        if (in_array($sms->status, ['sent', 'queued', 'sending'])) {
            return redirect(route('admin.notification.sms.index'))->with(
                'alert-section-error',
                'This sms notification cannot be sent in its current status.'
            );
        }

        $sms->update([
            'status' => 'queued',
        ]);

        SendSmsToUsers::dispatch($sms);

        return redirect(route('admin.notification.sms.index'))->with(
            'alert-section-success',
            'SMS notification has been queued for sending.'
        );
    }
}

