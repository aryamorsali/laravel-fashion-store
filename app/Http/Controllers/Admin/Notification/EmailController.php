<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notification\EmailRequest;
use App\Models\Notification\Email;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emails = Email::all();
        return view('admin.notification.email.index', compact('emails'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.notification.email.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailRequest $request)
    {
        $inputs = $request->validated();
        if (!empty($inputs['published_at'])) {

            $publishedAt = Carbon::createFromFormat('Y-m-d H:i', $inputs['published_at']);
            if ($publishedAt <= Carbon::now()) {
                return back()->with('alert-section-error', 'Your email notification time has already passed.');
            }
            $inputs['published_at'] = $publishedAt;
            $inputs['status'] = 'scheduled';
        } else {
            $inputs['published_at'] = null;
            $inputs['status'] = 'draft';
        }

        Email::create($inputs);
        return redirect()->route('admin.notification.email.index')->with(
            'alert-section-success',
            'Your new product was successfully registered.'
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
    public function edit(Email $email)
    {
        return view('admin.notification.email.edit', compact('email'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailRequest $request, Email $email)
    {
        $inputs = $request->validated();

        if (!empty($inputs['published_at'])) {
            $publishedAt = Carbon::createFromFormat('Y-m-d H:i', $inputs['published_at']);
            if ($publishedAt <= Carbon::now()) {
                return back()->with('alert-section-error', 'Your email notification time has already passed.');
            }
            if ($email->status !== 'sent') {
                $inputs['status'] = 'scheduled';
            }

            $inputs['published_at'] = $publishedAt;
        } else {
            if ($email->status !== 'sent') {
                $inputs['status'] = 'draft';
            }
            $inputs['published_at'] = null;
        }

        $email->update($inputs);
        return redirect()->route('admin.notification.email.index')->with(
            'alert-section-success',
            'Email notification updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Email $email)
    {
        $email->delete();
        return redirect(route('admin.notification.email.index'))->with(
            'alert-section-success',
            'Email notification successfully deleted.'
        );
    }
    public function send(Email $email)
    {
        return redirect(route('admin.notification.email.index'))->with(
            'alert-section-success',
            'Email notification successfully deleted.'
        );
    }
}
