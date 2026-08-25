<?php

namespace App\Http\Controllers\Admin\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Ticket\AdminTicketRequest;
use App\Models\Ticket\AdminTicket;
use App\Models\Ticket\TicketCategory;
use App\Models\User;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adminTickets = AdminTicket::with(['admin', 'category'])->get();
        return view('admin.ticket.admin.index', compact('adminTickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();
        $ticketCategories = TicketCategory::where('status', 1)->get();
        return view('admin.ticket.admin.create', compact('admins', 'ticketCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminTicketRequest $request)
    {
        $data = $request->validated();
        AdminTicket::create($data);
        return redirect()->route('admin.ticket.admin.index')->with(
            'alert-section-success',
            'Admin successfully added.'
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
    public function edit(AdminTicket $adminTicket)
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();
        $ticketCategories = TicketCategory::where('status', 1)->get();
        return view('admin.ticket.admin.edit', compact('admins', 'ticketCategories', 'adminTicket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminTicketRequest $request, AdminTicket $adminTicket)
    {
        $data = $request->validated();
        $adminTicket->update($data);
        return redirect()->route('admin.ticket.admin.index')->with(
            'alert-section-success',
            'Ticket admin successfully updated.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminTicket $adminTicket)
    {
        $adminTicket->delete();
        return redirect()->route('admin.ticket.admin.index')->with(
            'alert-section-success',
            'dmin successfully deleted.'
        );
    }
}
