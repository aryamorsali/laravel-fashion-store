<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Comment;
use App\Models\Content\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unSeenComments = Comment::where('commentable_type', Post::class)->where('seen', 0)->get();
        foreach ($unSeenComments as $unSeenComment) {
            $unSeenComment->seen = 1;
            $result = $unSeenComment->save();
        }
        $comments = Comment::with('commentable')->orderBy('created_at', 'desc')->where('commentable_type', Post::class)->paginate(15);
        return view('admin.content.comment.index', compact('comments'));
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
    public function show(Comment $comment)
    {
        return view('admin.content.comment.show', compact('comment'));
    }
    public function answer(Request $request, Comment $comment)
    {
        $request->validate([
            'body' => 'required|min:2|max:1000|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي. !, ]+$/u',
        ]);
        if ($comment->parent_id == null) {

            $inputs = $request->all();
            $inputs['author_id'] = 1;
            $inputs['parent_id'] = $comment->id;
            $inputs['commentable_id'] = $comment->commentable_id;
            $inputs['commentable_type'] = $comment->commentable_type;
            $inputs['approved'] = 1;

            $comment = Comment::create($inputs);
            return redirect()->route('admin.content.comment.index')->with(
                'alert-section-success',
                'Your response was successfully recorded.'
            );
        }
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

    public function approved(Comment $comment)
    {
        $comment->approved = $comment->approved == 0 ? 1 : 0;
        $result = $comment->save();
        return redirect()->route('admin.content.comment.index')->with(
            'alert-section-success',
            'Comment successfully updated.'
        );
    }
}
