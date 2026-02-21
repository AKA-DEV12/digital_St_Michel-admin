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
}
