<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $category = Category::factory()->create([
            'name' => 'Web Programming',
            'description' => 'web-programming issues'
        ]);
        Ticket::factory()->create([
            'user_id' => 1,
            'ticket_nr' => 1,
            'category_id' => $category->id,
            'ticket_subject' => 'Internet gelöscht',
            'ticket_message' => 'Nichts geht mehr auf dem Glump',
            'ticket_status' => 'open'
        ]);
        Comment::factory()->create([
            'ticket_id' => '1',
            'user_id' => '1',
            'content' => 'Mein erster Test-Kommentar'
        ]);


    }
}
