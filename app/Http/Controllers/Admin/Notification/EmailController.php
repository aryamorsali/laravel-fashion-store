<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notification\EmailRequest;
use App\Http\Services\Message\Email\EmailService;
use App\Http\Services\Message\MessageService;
use App\Jobs\SendEmailToUsers;
use App\Models\Notification\Email;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmailController extends Controller
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

        $query = Email::query();

        if ($request->filled('search')) {

            $query->where('subject', 'LIKE', '%' . $search . '%')->orWhere('body', 'LIKE', '%' . $search . '%');
        }

        $emails = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());

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

            $inputs['status'] = 'scheduled';

            $inputs['published_at'] = $publishedAt;
        } else {
            $inputs['status'] = 'draft';
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

        if (in_array($email->status, ['sent', 'queued', 'sending'])) {
            return redirect(route('admin.notification.email.index'))->with(
                'alert-section-error',
                'This email notification cannot be sent in its current status.'
            );
        }

        $email->update([
            'status' => 'queued',
        ]);
      
        SendEmailToUsers::dispatch($email);

        return redirect(route('admin.notification.email.index'))->with(
            'alert-section-success',
            'Email notification has been queued for sending.'
        );
    }
}
