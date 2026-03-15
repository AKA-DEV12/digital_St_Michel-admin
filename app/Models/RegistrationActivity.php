<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationActivity extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'date',
        'start_time',
        'end_time',
        'location',
        'registration_amount',
        'notification_email',
        'wave_number',
        'mtn_number',
        'orange_number',
        'moov_number',
        'color',
        'is_active',
    ];
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    protected static function booted()
    {
        static::deleting(function ($activity) {
            // Delete all registrations associated with this activity
            // Using each() to trigger the deleting event on each Registration model
            $activity->registrations->each(function ($registration) {
                $registration->delete();
            });
        });
    }
}
