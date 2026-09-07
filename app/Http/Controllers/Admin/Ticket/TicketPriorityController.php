<?php

namespace App\Http\Controllers\Admin\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\SearchRequest;
use App\Http\Requests\Admin\Ticket\TicketPriorityRequest;
use App\Models\Ticket\TicketPriority;
use Illuminate\Http\Request;

class TicketPriorityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request)
    {
        $validated = $request->validated();

        $search = $validated['search'] ?? null;

        $query = TicketPriority::query();
        if ($request->filled('search')) {

            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $priorities = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());

        return view('admin.ticket.priority.index', compact('priorities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ticket.priority.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TicketPriorityRequest $request)
    {
        $data = $request->validated();
        TicketPriority::create($data);
        return redirect()->route('admin.ticket.priority.index')->with(
            'alert-section-success',
            'Ticket priority successfully created.'
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
    public function edit(TicketPriority $priority)
    {
        return view('admin.ticket.priority.edit', compact('priority'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TicketPriorityRequest $request, TicketPriority $priority)
    {
        $data = $request->validated();
        $priority->update($data);
        return redirect()->route('admin.ticket.priority.index')->with(
            'alert-section-success',
            'Ticket priority successfully updated.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketPriority $priority)
    {
        $priority->delete();
        return redirect()->route('admin.ticket.priority.index')->with(
            'alert-section-success',
            'Ticket priority successfully deleted.'
        );
    }

    public function status(TicketPriority $priority)
    {
        $priority->status = $priority->status == 0 ? 1 : 0;
        $result = $priority->save();
        if ($result) {
            if ($priority->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
}
