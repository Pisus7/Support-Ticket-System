<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Admin sieht alle Tickets, User nur seine eigenen
        if ($user->role_id === 1) {
            $tickets = Ticket::with('user')->latest()->get();
        } else {
            $tickets = Ticket::where('user_id', $user->id)->latest()->get();
        }

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets
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

    public function show(Request $request, Ticket $ticket)
    {
        if ($request->user()->role_id !== 1) {
            Gate::authorize('view', $ticket);
        }

        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket->load([
                'category',
                'comments' => function ($query) {
                    $query->latest();
                },
                'comments.user'
            ]),
        ]);
    }

    public function edit(Request $request, Ticket $ticket)
    {
        if ($request->user()->role_id !== 1) {
            Gate::authorize('update', $ticket);
        }

        return Inertia::render('Tickets/Edit', [
            'ticket' => $ticket,
            'categories' => Category::all(['id', 'name']),
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($request->user()->role_id !== 1) {
            Gate::authorize('update', $ticket);
        }

        $newStatus = $request->input('ticket_status');
        $ticket->ticket_status = $newStatus;
        $ticket->admin_id = Auth::id();
        $ticket->save();
        $ticket->refresh();

        return redirect()->route('tickets.index', $ticket);
    }

    public function destroy(Request $request, Ticket $ticket)
    {
        if ($request->user()->role_id !== 1) {
            Gate::authorize('delete', $ticket);
        }

        $ticket->delete();

        return redirect()->route('tickets.index');
    }
}
