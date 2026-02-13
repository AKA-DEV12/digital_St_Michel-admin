<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'uuid',
        'registration_activity_id',
        'full_name',
        'phone_number',
        'amount',
        'option',
        'group_name',
        'status',
        'payment_email',
        'payment_operator',
        'payment_receipt',
        'qr_code_scanned',
        'scanned_by_agent_id',
    ];

    public function registrationActivity()
    {
        return $this->belongsTo(RegistrationActivity::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'scanned_by_agent_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
