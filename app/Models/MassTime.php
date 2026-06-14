<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MassTime extends Model
{
    protected $fillable = ['time', 'day_type', 'is_active'];
}
