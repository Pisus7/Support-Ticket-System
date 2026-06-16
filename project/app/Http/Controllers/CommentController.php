<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Notifications\NewCommentNotification;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('comments.index', [
            'comments' => Auth::user()->comments()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('comments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request)
    {
        $comment = Auth::user()->comments()->create([
            'content' => $request->content,
        ]);

        Auth::user() -> notify(new NewCommentNotification($comment));
        return redirect('/tickets');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        Gate::authorize('update', $comment);

        return view('comment.show', [
            'ticket' => $comment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        Gate::authorize('update', $comment);

        return view('comments.edit', [
            'comment' => $comment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $comment->update([
            'content' => $request->input('content')
        ]);

        return redirect('/comment/'.$comment->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('update', $comment);

        $comment->delete();

        return redirect('/comments');
    }
}
