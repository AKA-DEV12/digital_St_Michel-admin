<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'group_name',
        'other_group_name',
        'reservation_object',
        'reservation_date',
        'time_slot',
        'room_id',
        'status',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
