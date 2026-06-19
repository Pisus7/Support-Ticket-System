<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index()
    {
        return Inertia::render('Tickets/Index', [
            'tickets' => Auth::user()->tickets()->with('category')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Tickets/Create', [
            'categories' => Category::all(['id', 'name']),
        ]);
    }

    public function store(TicketRequest $request)
    {
        $ticket = Auth::user()->tickets()->create([
            'ticket_subject' => $request->ticket_subject,
            'ticket_message' => $request->ticket_message,
            'ticket_status' => TicketStatus::OPEN->value,
            'category_id' => $request->category_id,
        ]);

        $ticket->ticket_nr = 'TICKET-' . date('Y') . '-' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT);
        $ticket->save();

        return redirect()->route('tickets.index');
    }

    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket->load(['category', 'comments.user']),
        ]);
    }

    public function edit(Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        return Inertia::render('Tickets/Edit', [
            'ticket' => $ticket,
            'categories' => Category::all(['id', 'name']),
        ]);
    }

    public function update(TicketRequest $request, Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        $ticket->update([
            'ticket_subject' => $request->input('ticket_subject'),
            'ticket_message' => $request->input('ticket_message')
        ]);

        return redirect()->route('tickets.show', $ticket);
    }

    public function destroy(Ticket $ticket)
    {
        Gate::authorize('delete', $ticket);

        $ticket->delete();

        return redirect()->route('tickets.index');
    }
}
