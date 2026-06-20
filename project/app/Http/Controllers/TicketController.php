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

        $query = Ticket::with(['user', 'category'])->latest();

        if ($user->role_id !== 1) {
            $query->where('user_id', $user->id);
        }

        $tickets = $query->get();

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
        if ($request->user()->role_id !== 1 && $ticket->ticket_status !== 'open') {
            abort(403, 'Nur offene Tickets können bearbeitet werden.');
        }

        Gate::authorize('update', $ticket);

        return Inertia::render('Tickets/Edit', [
            'ticket' => $ticket,
            'categories' => Category::all(['id', 'name']),
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $user = $request->user();

        if ($user->role_id !== 1) {
            Gate::authorize('update', $ticket);
        }

        if ($request->has('ticket_status') && $request->input('ticket_status') !== $ticket->ticket_status) {
            $newStatus = $request->input('ticket_status');

            if ($user->role_id !== 1 && !in_array($newStatus, ['resolved', 'archived'])) {
                abort(403, 'Unauthorized action.');
            }

            $ticket->ticket_status = $newStatus;

            if ($newStatus === 'in_progress' && $user->role_id === 1) {
                $ticket->admin_id = $user->id;
            }

            $ticket->save();
            return redirect()->route('tickets.show', $ticket);
        }

        if ($user->role_id !== 1 && $ticket->ticket_status !== 'open') {
            abort(403, 'Du kannst nur offene Tickets bearbeiten.');
        }

        $validated = $request->validate([
            'ticket_subject' => 'required|string|max:255',
            'ticket_message' => 'required|string',
            'category_id'   => 'required|exists:categories,id',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket);
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
