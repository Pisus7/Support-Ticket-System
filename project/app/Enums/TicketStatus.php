<?php

namespace App\Enums;

enum TicketStatus : string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case PENDING = 'pending';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    // Optional: Lesbare Namen für Pius' Frontend (Blade/React)
    public function label(): string
    {
        return match($this) {
            self::OPEN => 'Offen',
            self::IN_PROGRESS => 'In Bearbeitung',
            self::PENDING => 'Wartend',
            self::RESOLVED => 'Gelöst',
            self::CLOSED => 'Geschlossen',
        };
    }
}
