<?php
// tests/Feature/AvailabilityControllerTest.php
namespace Tests\Feature;

use App\Models\User;
use App\Models\UserAvailability;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_set_availability()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/availability', [
            'user_id' => $user->id,
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '17:00',
            'timezone' => 'America/New_York',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_availability', [
            'user_id' => $user->id,
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '17:00',
            'timezone' => 'America/New_York',
        ]);
    }

    public function test_get_availability()
    {
        $user = User::factory()->create();
        UserAvailability::factory()->create([
            'user_id' => $user->id,
            'day_of_week' => 2,
            'start_time' => '09:00',
            'end_time' => '18:00',
            'timezone' => 'America/New_York',
        ]);

        $response = $this->getJson("/api/availability/{$user->id}?buyer_timezone=America/Los_Angeles");

        $response->assertStatus(200);
        $response->assertJson([
            [
                'day_of_week' => 2,
                'start_time' => '06:00',
                'end_time' => '15:00',
            ],
        ]);
    }
}
