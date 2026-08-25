<?php

namespace App\Http\Controllers\Customer\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Profile\TicketRequest;
use App\Http\Requests\ProfileTicketRequest;
use App\Models\Ticket\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileTiketController extends Controller
{
    public function index()
    {
        $tickets = Auth::user()->tickets()->where('parent_id', null)->orderBy('created_at', 'desc')->paginate(10);
        return view('customer.profile.ticket.index', compact('tickets'));
    }


    public function change(Ticket $ticket)
    {
        if ($ticket->status === 1) {
            return redirect()->back()->with('toast-error', 'This ticket is closed. Please submit a new ticket to resolve the issue.');
        }

        $ticket->update([
            'status' => $ticket->status === 0 ? 1 : 0
        ]);
        return redirect()->back();
    }

    public function show(Ticket $ticket)
    {
        return view('customer.profile.ticket.show', compact('ticket'));
    }

    public function answer(TicketRequest $request, Ticket $ticket)
    {
        if ($ticket->status === 1) {
            return redirect()->back()->with('toast-error', 'This ticket is closed. Please submit a new ticket to resolve the issue.');
        }

        $data = $request->validated();

        Ticket::create([
            'user_id' => Auth::user()->id,
            'subject' => $ticket->subject,
            'description' => $data['description'],
            'category_id' => $ticket->category_id,
            'priority_id' => $ticket->priority_id,
            'parent_id' => $ticket->id,
        ]);

        return redirect()->back();
    }
}
