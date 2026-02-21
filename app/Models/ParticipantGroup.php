<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantGroup extends Model
{
    protected $fillable = ['name', 'target_size'];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
