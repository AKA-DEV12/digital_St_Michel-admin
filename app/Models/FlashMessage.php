<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashMessage extends Model
{
    protected $fillable = ['message', 'is_active'];
}
