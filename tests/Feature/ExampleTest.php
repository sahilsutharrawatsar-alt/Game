<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->withoutVite();
        $this->seed();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_seeded_super_admin_can_open_admin_dashboard(): void
    {
        $this->withoutVite();
        $this->seed();

        $admin = User::where('email', 'admin@arenax.com')->firstOrFail();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertStatus(200)
            ->assertSee('ArenaX command center');
    }

    public function test_approved_vendor_can_open_vendor_dashboard(): void
    {
        $this->withoutVite();
        $this->seed();

        $vendor = User::where('email', 'owner@arenax.test')->firstOrFail();

        $this->actingAs($vendor)
            ->get('/vendor')
            ->assertStatus(200)
            ->assertSee('Vendor Studio');
    }
}
