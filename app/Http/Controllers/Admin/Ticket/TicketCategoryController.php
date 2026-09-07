<?php

namespace App\Http\Controllers\Admin\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\SearchRequest;
use App\Http\Requests\Admin\Ticket\TicketCategoryRequest;
use App\Models\Ticket\TicketCategory;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request)
    {
        $validated = $request->validated();

        $search = $validated['search'] ?? null;

        $query = TicketCategory::query();
        if ($request->filled('search')) {

            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());

        return view('admin.ticket.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ticket.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TicketCategoryRequest $request)
    {
        $data = $request->validated();
        TicketCategory::create($data);
        return redirect()->route('admin.ticket.category.index')->with(
            'alert-section-success',
            'Ticket category successfully created.'
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
    public function edit(TicketCategory $category)
    {
        return view('admin.ticket.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TicketCategoryRequest $request, TicketCategory $category)
    {
        $data = $request->validated();
        $category->update($data);
        return redirect()->route('admin.ticket.category.index')->with(
            'alert-section-success',
            'Ticket category successfully updated.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.ticket.category.index')->with(
            'alert-section-success',
            'Ticket category successfully deleted.'
        );
    }

    public function status(TicketCategory $category)
    {
        $category->status = $category->status == 0 ? 1 : 0;
        $result = $category->save();
        if ($result) {
            if ($category->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
}
