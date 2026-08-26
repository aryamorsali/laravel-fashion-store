<?php

namespace App\Http\Controllers\Admin\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Ticket\TicketRequest;
use App\Models\Ticket\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::whereNull('parent_id')->orderBy('created_at', 'DESC')->get();
        return  view("admin.ticket.index", compact('tickets'));
    }

    public function filter(Request $request)
    {
        if ($request->has('sort')) {
            switch ($request->sort) {
                case '1':
                    $tickets = Ticket::where('status', 0)->whereNull('parent_id')->get();
                    break;
                case '2':
                    $tickets = Ticket::where('status', 1)->whereNull('parent_id')->get();
                    break;
                default:
                    $tickets = Ticket::orderBy('created_at', 'DESC')->whereNull('parent_id')->get();
                    break;
            }
        }else{
            $tickets = Ticket::orderBy('created_at', 'DESC')->get();
        }
        return  view("admin.ticket.index", compact('tickets'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view("admin.ticket.show", compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function change(Ticket $ticket)
    {
        $ticket->status = $ticket->status == 0 ? 1 : 0;
        $result = $ticket->save();
        return redirect()->route('admin.ticket.index')->with(
            'alert-section-success',
            'Ticket status successfully updated.'
        );
    }

    public function answer(TicketRequest $request,  Ticket $ticket)
    {
        $inputs = $request->validated();
        $inputs['subject'] = $ticket->subject;
        $inputs['description'] = $inputs['description'];
        $inputs['seen'] = 1;
        $inputs['user_id'] = Auth::id();
        $inputs['category_id'] = $ticket->category_id;
        $inputs['priority_id'] =  $ticket->priority_id;
        $inputs['parent_id'] = $ticket->id;

        $replyTicket = Ticket::create($inputs);
        return redirect()->route('admin.ticket.index')->with(
            'alert-section-success',
            'Your response was successfully recorded.'
        );
    }
}
