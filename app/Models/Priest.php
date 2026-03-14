<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priest extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'role',
        'audience',
        'photo_path',
        'available_time_slots',
        'unavailable_dates',
        'is_active',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'available_time_slots' => 'array',
        'unavailable_dates' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(PriestAppointment::class);
    }
}
