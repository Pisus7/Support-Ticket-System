<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCloseResolvedTickets extends Command
{
    // The command name for the terminal
    protected $signature = 'tickets:auto-close';
    protected $description = 'Closes resolved tickets after 24 hours of inactivity';

    public function handle(): void
    {
        // Fetch all resolved tickets updated more than 24 hours ago
        $tickets = Ticket::query()
            ->where('ticket_status', 'resolved')
            ->where('updated_at', '<=', Carbon::now()->subHours(24))
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->ticket_status = 'closed';
            $ticket->save();

            $this->info("Ticket #$ticket->ticket_nr was automatically closed.");
        }
    }
}
