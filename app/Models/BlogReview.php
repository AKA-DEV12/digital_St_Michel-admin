<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogReview extends Model
{
    protected $fillable = ['title', 'image', 'score', 'is_active'];
}
