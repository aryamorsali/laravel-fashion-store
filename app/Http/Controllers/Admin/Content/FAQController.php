<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Content\FAQRequest;
use App\Models\Content\FAQ;
use App\Models\Content\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = FAQ::all();
        return view('admin.content.faq.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        return view('admin.content.faq.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FAQRequest $request)
    {
        $data = $request->validated();
        $tags = $data['tags'] ?? null;


        DB::transaction(function () use ($data, $tags) {

            $faq = FAQ::create($data);

            // attach tags
            if ($tags) {
                $faq->tags()->sync($tags);
            }
        });

        return redirect()->route('admin.content.faq.index')->with(
            'alert-section-success',
            'New faq successfully registered.'
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
    public function edit(FAQ $faq)
    {
        $tags = Tag::all();
        return view('admin.content.faq.edit', compact('faq', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FAQRequest $request, FAQ $faq)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $faq) {

            $faq->update($data);

            // ---------------------------
            // تگ‌ها
            // ---------------------------
            if (!empty($data['tags'])) {
                $faq->tags()->sync($data['tags']);
            } else {
                $faq->tags()->detach();
            }
        });

        return redirect()->route('admin.content.faq.index')->with(
            'alert-section-success',
            'FAQ successfully updated.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FAQ $faq)
    {
        $faq->delete();
        return redirect()->route('admin.content.faq.index')->with(
            'alert-section-success',
            'FAQ successfully deleted.'
        );
    }

    public function status(FAQ $faq)
    {
        $faq->status = $faq->status == 0 ? 1 : 0;
        $result = $faq->save();
        if ($result) {
            if ($faq->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
}
