<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_are_created_with_correct_roles()
    {
        // Rollen über IDs simulieren
        $admin = User::factory()->create(['role_id' => 1]);
        $client = User::factory()->create(['role_id' => 2]);

        $this->assertEquals(1, $admin->role_id);
        $this->assertEquals(2, $client->role_id);
    }
}
