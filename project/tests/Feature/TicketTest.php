<?php

namespace Tests\Feature;

use App\Enums\TicketStatus;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase; // Setzt die SQLite-Datenbank für jeden Testlauf sauber zurück

    /**
     * Test 1: Nicht angemeldete Gäste werden zum Login umgeleitet (Routenschutz).
     */
    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get(route('tickets.index'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test 2: Ein angemeldeter User kann erfolgreich ein Support-Ticket erstellen (CRUD-Logik).
     */
    public function test_authenticated_user_can_create_ticket(): void
    {
        $this->withoutExceptionHandling();
        // 1. Vorbereitung: User und Kategorie erstellen
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Technik', 'description' => 'IT Support']);

        // 2. Aktion: Als User einloggen und Ticket-Formular absenden
        $response = $this->actingAs($user)->post(route('tickets.store'), [
            'category_id' => $category->id,
            'ticket_subject' => 'Internet geht nicht',
            'ticket_message' => 'Mein Router blinkt nur noch rot seit heute Morgen.',
            'ticket_status' => TicketStatus::OPEN->value
        ]);

        // 3. Überprüfung: Wurde in die DB geschrieben und weitergeleitet?
        $this->assertDatabaseHas('tickets', [
            'user_id' => $user->id,
            'ticket_subject' => 'Internet geht nicht',
        ]);

        // Prüft, ob der Controller auf die Show-Seite des neuen Tickets weiterleitet
        $ticket = Ticket::first();
        $response->assertRedirect(route('tickets.show', $ticket));
    }

    /**
     * Test 3: Ein User darf das Ticket eines anderen Users nicht einsehen (Prüfung der Policy).
     */
    public function test_user_cannot_view_someone_elses_ticket(): void
    {
        // 1. Vorbereitung: Zwei verschiedene User und eine Kategorie anlegen
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $category = Category::create(['name' => 'Buchhaltung']);

        // User A erstellt ein Ticket
        $ticketOfUserA = Ticket::create([
            'user_id' => $userA->id,
            'category_id' => $category->id,
            'ticket_nr' => 'TK-12345',
            'ticket_subject' => 'Rechnungsfehler',
            'ticket_message' => 'Die Abrechnung stimmt nicht.',
            'ticket_status' => 'open'
        ]);

        // 2. Aktion: Wir loggen uns als User B ein und versuchen das Ticket von User A aufzurufen
        $response = $this->actingAs($userB)->get(route('tickets.show', $ticketOfUserA));

        // 3. Überprüfung: Die Policy muss den Zugriff verweigern (HTTP 403 Forbidden)
        $response->assertStatus(403);
    }
}
