<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function setAvailability(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $availability = $user->availability()->create([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'timezone' => $request->timezone,
        ]);

        return response()->json($availability, 201);
    }

    public function getAvailability($userId, $buyerTimezone)
    {
        $user = User::findOrFail($userId);
        $availability = $user->availability()->get();

        $availabilityInBuyerTimezone = $availability->map(function ($item) use ($buyerTimezone) {
            $startTime = Carbon::createFromFormat('H:i', $item->start_time, $item->timezone)
                ->setTimezone($buyerTimezone)
                ->format('H:i');
            $endTime = Carbon::createFromFormat('H:i', $item->end_time, $item->timezone)
                ->setTimezone($buyerTimezone)
                ->format('H:i');

            return [
                'day_of_week' => $item->day_of_week,
                'start_time' => $startTime,
                'end_time' => $endTime,
            ];
        });

        return response()->json($availabilityInBuyerTimezone);
    }
}
