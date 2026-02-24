<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use DatabaseTransactions;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_dashboard(): void
    {
        $user = User::factory()->create();
        Role::findOrCreate('editor', 'web');
        $user->assignRole('editor');

        $this->actingAs($user);
        $this->get('/dashboard')->assertStatus(200);
    }
}
