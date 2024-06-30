<?php
namespace Tests\Unit;

use App\Models\User;
use App\Models\UserAvailability;
use Carbon\Carbon;
use Tests\TestCase;

class UserAvailabilityTest extends TestCase
{
    public function test_user_availability_relationship()
    {
        $user = User::factory()->create();
        $availability = UserAvailability::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $availability->user);
        $this->assertEquals($user->id, $availability->user->id);
    }

    public function test_convert_availability_to_buyer_timezone()
    {
        $availability = new UserAvailability([
            'day_of_week' => 3,
            'start_time' => '09:00',
            'end_time' => '18:00',
            'timezone' => 'America/New_York',
        ]);

        $availabilityInBuyerTimezone = $availability->convertToBuyerTimezone('America/Los_Angeles');

        $this->assertEquals(3, $availabilityInBuyerTimezone['day_of_week']);
        $this->assertEquals('06:00', $availabilityInBuyerTimezone['start_time']);
        $this->assertEquals('15:00', $availabilityInBuyerTimezone['end_time']);
    }
}
