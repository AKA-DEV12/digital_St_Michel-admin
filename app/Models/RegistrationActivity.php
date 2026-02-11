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
        'color',
        'is_active',
    ];
}
