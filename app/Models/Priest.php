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

    protected static function booted()
    {
        static::deleting(function ($priest) {
            // Delete associated appointments
            $priest->appointments()->delete();

            // Delete photo file
            if ($priest->photo_path && file_exists(public_path($priest->photo_path))) {
                @unlink(public_path($priest->photo_path));
            }
        });
    }
}
