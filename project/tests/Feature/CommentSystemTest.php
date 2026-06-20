<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $client;
    protected Ticket $ticket;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role_id' => 1]);
        $this->client = User::factory()->create(['role_id' => 2]);
        $category = Category::factory()->create();

        $this->ticket = Ticket::factory()->create([
            'user_id' => $this->client->id,
            'category_id' => $category->id,
            'ticket_status' => 'in_progress'
        ]);
    }

    /** @test */
    public function ticket_status_changes_to_pending_when_admin_comments()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('comments.store', $this->ticket->id), [
                'comment_text' => 'Please provide more logs.',
                'is_internal' => false
            ]);

        $response->assertRedirect(route('tickets.show', $this->ticket->id));

        // Prüfen, ob der Status automatisch auf 'pending' gesprungen ist
        $this->assertEquals('pending', $this->ticket->fresh()->ticket_status);
    }

    /** @test */
    public function ticket_status_changes_back_to_in_progress_when_client_replies()
    {
        $this->ticket->update(['ticket_status' => 'pending']);

        $response = $this->actingAs($this->client)
            ->post(route('comments.store', $this->ticket->id), [
                'comment_text' => 'Here are my logs...',
                'is_internal' => false
            ]);

        $response->assertRedirect(route('tickets.show', $this->ticket->id));

        // Prüfen, ob der Status automatisch zurück auf 'in_progress' gesprungen ist
        $this->assertEquals('in_progress', $this->ticket->fresh()->ticket_status);
    }

    /** @test */
    public function a_client_can_delete_their_own_comment()
    {
        $comment = $this->ticket->comments()->create([
            'user_id' => $this->client->id,
            'content' => 'My temporary comment'
        ]);

        $response = $this->actingAs($this->client)
            ->delete(route('comments.destroy', ['ticket' => $this->ticket->id, 'comment' => $comment->id]));

        $response->assertRedirect(route('tickets.show', $this->ticket->id));
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    /** @test */
    public function a_client_cannot_delete_an_admin_comment()
    {
        $adminComment = $this->ticket->comments()->create([
            'user_id' => $this->admin->id,
            'content' => 'Official Admin Answer'
        ]);

        $response = $this->actingAs($this->client)
            ->delete(route('comments.destroy', ['ticket' => $this->ticket->id, 'comment' => $adminComment->id]));

        $response->assertStatus(403);
        $this->assertDatabaseHas('comments', ['id' => $adminComment->id]);
    }

    /** @test */
    public function an_admin_can_delete_any_comment()
    {
        $clientComment = $this->ticket->comments()->create([
            'user_id' => $this->client->id,
            'content' => 'Client text'
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('comments.destroy', ['ticket' => $this->ticket->id, 'comment' => $clientComment->id]));

        $response->assertRedirect(route('tickets.show', $this->ticket->id));
        $this->assertDatabaseMissing('comments', ['id' => $clientComment->id]);
    }
}
