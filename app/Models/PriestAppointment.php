<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriestAppointment extends Model
{
    protected $fillable = [
        'priest_id',
        'appointment_date',
        'time_slot',
        'full_name',
        'phone',
        'email',
        'object',
        'status',
    ];

    public function priest()
    {
        return $this->belongsTo(Priest::class);
    }
}
