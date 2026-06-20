<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Ticket;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Ticket $ticket)
    {
        if ($request->user()->role_id !== 1) {
            Gate::authorize('view', $ticket);
        }

        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->input('comment_text'),
            'is_internal' => $request->boolean('is_internal', false)
        ]);

        $ticket->user->notify(new NewCommentNotification($comment));

        if($request->user()->role_id === 1) {
            if ($ticket->ticket_status !== 'closed' && $ticket->ticket_status !== 'resolved') {
                if ($request->user()->role_id === 1) {
                    $ticket->ticket_status = 'pending';
                } else {
                    $ticket->ticket_status = 'in_progress';
                }
                $ticket->save();
            }
        }

        return redirect()->route('tickets.show', $ticket);
    }

    public function destroy(Request $request, Ticket $ticket, Comment $comment)
    {
        if ($request->user()->role_id !== 1) {
            Gate::authorize('delete', $comment);
        }

        $comment->delete();

        return redirect()->route('tickets.show', $ticket);
    }
}
