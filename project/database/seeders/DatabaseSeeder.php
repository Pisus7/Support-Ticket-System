<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- ROLES ---
        Role::factory()->create([
            'description' => 'admin'
        ]);
        Role::factory()->create([
            'description' => 'visitor'
        ]);

        // --- USERS ---
        User::factory()->create([
            'name' => 'Paul Summerauer',
            'email' => 'paul@aon.at',
            'password' => Hash::make('paulaonat'),
            'role_id' => 1
        ]);

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('johndoe'),
            'role_id' => 2
        ]);

        // --- CATEGORIES ---
        Category::factory()->create([
            'name' => 'Hardware & Devices',
            'description' => 'Issues with laptops, monitors, printers, or office equipment.'
        ]);
        Category::factory()->create([
            'name' => 'Network & VPN',
            'description' => 'Problems with office Wi-Fi, ethernet connections, or remote VPN access.'
        ]);
        Category::factory()->create([
            'name' => 'Software & Licenses',
            'description' => 'Requests for software installations, license keys, or cloud tool access.'
        ]);
        Category::factory()->create([
            'name' => 'Accounts & Permissions',
            'description' => 'Password resets, account lockouts, or shared drive permission issues.'
        ]);

        // --- TICKETS ---
        Ticket::factory()->create([
            'user_id' => 2, // Created by John Doe
            'ticket_nr' => 'TICKET-26-1',
            'category_id' => 2, // Network & VPN
            'ticket_subject' => 'VPN connection failing from home office',
            'ticket_message' => 'Since this morning, I keep getting a "Server timeout" error when trying to connect to the corporate VPN. My home internet is working fine.',
            'ticket_status' => 'open'
        ]);

        Ticket::factory()->create([
            'user_id' => 2,
            'ticket_nr' => 'TICKET-26-2',
            'category_id' => 1, // Hardware & Devices
            'ticket_subject' => 'Second monitor stays black after update',
            'ticket_message' => 'After yesterday\'s Windows update, my secondary monitor connected to the docking station is no longer receiving a signal.',
            'ticket_status' => 'in_progress'
        ]);

        // --- COMMENTS ---
        Comment::factory()->create([
            'ticket_id' => 1,
            'user_id' => 2, // John Doe asking for help
            'content' => 'I already tried restarting my router, but the issue persists. Any ideas?'
        ]);

        Comment::factory()->create([
            'ticket_id' => 1,
            'user_id' => 1, // Admin replying
            'content' => 'Please verify that you are using the latest version of the VPN client. If you do, try flushing your DNS cache or reconnecting via the backup server gateway.'
        ]);
    }
}
