<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $client;
    protected User $otherClient;
    protected Category $category;
    protected Ticket $ticket;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 1]);
        $this->client = User::factory()->create(['role_id' => 2]);
        $this->otherClient = User::factory()->create(['role_id' => 2]);
        $this->category = Category::factory()->create();

        $this->ticket = Ticket::factory()->create([
            'user_id' => $this->client->id,
            'category_id' => $this->category->id,
            'ticket_status' => 'open'
        ]);
    }

    /** @test */
    public function an_admin_can_take_over_a_ticket()
    {
        $response = $this->actingAs($this->admin)
            ->put(route('tickets.update', $this->ticket->id), [
                'status' => 'in_progress'
            ]);

        $response->assertRedirect(route('tickets.show', $this->ticket->id));

        $this->assertDatabaseHas('tickets', [
            'id' => $this->ticket->id,
            'ticket_status' => 'in_progress',
            'admin_id' => $this->admin->id // Überprüft die automatische Admin-Zuweisung
        ]);
    }

    /** @test */
    public function an_admin_can_set_a_ticket_to_pending()
    {
        $this->ticket->update(['ticket_status' => 'in_progress', 'admin_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)
            ->put(route('tickets.update', $this->ticket->id), [
                'status' => 'pending'
            ]);

        $response->assertRedirect(route('tickets.show', $this->ticket->id));
        $this->assertEquals('pending', $this->ticket->fresh()->ticket_status);
    }

    /** @test */
    public function a_client_can_mark_their_own_ticket_as_resolved()
    {
        $this->ticket->update(['ticket_status' => 'in_progress']);

        $response = $this->actingAs($this->client)
            ->put(route('tickets.update', $this->ticket->id), [
                'status' => 'resolved'
            ]);

        $response->assertRedirect(route('tickets.show', $this->ticket->id));
        $this->assertEquals('resolved', $this->ticket->fresh()->ticket_status);
    }

    /** @test */
    public function a_client_cannot_mark_someone_elses_ticket_as_resolved()
    {
        $this->ticket->update(['ticket_status' => 'in_progress']);

        $response = $this->actingAs($this->otherClient)
            ->put(route('tickets.update', $this->ticket->id), [
                'status' => 'resolved'
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function an_admin_can_hard_close_a_ticket()
    {
        $response = $this->actingAs($this->admin)
            ->put(route('tickets.update', $this->ticket->id), [
                'status' => 'closed'
            ]);

        $response->assertRedirect(route('tickets.show', $this->ticket->id));
        $this->assertEquals('closed', $this->ticket->fresh()->ticket_status);
    }

    /** @test */
    public function a_client_can_edit_their_ticket_only_if_it_is_open()
    {
        // 1. Test: Ticket ist offen -> Editieren erlaubt
        $response = $this->actingAs($this->client)
            ->put(route('tickets.update', $this->ticket->id), [
                'ticket_subject' => 'Updated Subject',
                'ticket_message' => 'Updated Message',
                'category_id' => $this->category->id,
            ]);

        $response->assertRedirect(route('tickets.show', $this->ticket->id));

        // 2. Test: Ticket ist in_progress -> Editieren verboten
        $this->ticket->update(['ticket_status' => 'in_progress']);

        $response2 = $this->actingAs($this->client)
            ->put(route('tickets.update', $this->ticket->id), [
                'ticket_subject' => 'Hacker Try',
                'ticket_message' => 'Hacker Message',
                'category_id' => $this->category->id,
            ]);

        $response2->assertStatus(403);
    }
}
