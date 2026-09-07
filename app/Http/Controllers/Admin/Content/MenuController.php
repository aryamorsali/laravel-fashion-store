<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Content\MenuRequest;
use App\Http\Requests\Admin\Market\SearchRequest;
use App\Models\Content\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request)
    {
        $validated = $request->validated();

        $search = $validated['search'] ?? null;

        $query = Menu::query();
        if ($request->filled('search')) {

            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $menus = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());

        return view('admin.content.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parent_menus = Menu::where('parent_id', null)->get();
        return view('admin.content.menu.create', compact('parent_menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MenuRequest $request)
    {
        $inputs = $request->all();
        $inputs['parent_id'] = $inputs['parent_id'] ?? null;
        $menu = Menu::create($inputs);
        return redirect()->route('admin.content.menu.index')->with(
            'alert-section-success',
            'New menu successfully registered.'
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
    public function edit(Menu $menu)
    {
        $parent_menus = Menu::where('parent_id', null)->get()->except($menu->id);
        return view('admin.content.menu.edit', compact('parent_menus', 'menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MenuRequest $request, Menu $menu)
    {
        $inputs = $request->all();
        $inputs['parent_id'] = $inputs['parent_id'] ?? null;
        $menu->update($inputs);
        return redirect()->route('admin.content.menu.index')->with(
            'alert-section-success',
            'Menu successfully edited.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.content.menu.index')->with(
            'alert-section-success',
            'Menu successfully deleted.'
        );
    }

    public function status(Menu $menu)
    {
        $menu->status = $menu->status == 0 ? 1 : 0;
        $result = $menu->save();
        if ($result) {
            if ($menu->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
}
