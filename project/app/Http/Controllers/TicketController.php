<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * index show edit update destroy store create for tickets
 */
class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tickets.index', [
            'tickets' => Auth::user()->tickets()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TicketRequest $request)
    {
        $ticket = Auth::user()->tickets()->create([
            'ticket_subject' => $request->ticket_subject,
            'ticket_message' => $request->ticket_message,
            'ticket_status' => $request->ticket_status,
            'category_id' => $request->category_id,
        ]);

        $ticketNumber = 'TICKET-' . date('Y') . '-' . str_pad($ticket->id, 5, 0, STR_PAD_LEFT);

        $ticket->ticket_nr = $ticketNumber;
        $ticket->save();

        Auth::user() -> notify(new NewCommentNotification($ticket));
        return redirect('/tickets/'.$ticket->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        return view('tickets.show', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        return view('tickets.edit', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TicketRequest $request, Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        $ticket->update([
            'ticket_subject' => $request->input('ticket_subject'),
            'ticket_message' => $request->input('ticket_message')
        ]);

        return redirect('/tickets/'.$ticket->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        Gate::authorize('delete', $ticket);

        $ticket->delete();

        return redirect('/tickets');
    }
}
