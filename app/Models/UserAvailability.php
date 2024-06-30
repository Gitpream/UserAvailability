<?php
use Illuminate\Database\Eloquent\Model;

class UserAvailability extends Model
{
    protected $fillable = [
        'user_id',
        'day_of_week',
        'start_time',
        'end_time',
        'timezone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
