<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'id',
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
        'participant_group_id',
    ];

    public function registrationActivity()
    {
        return $this->belongsTo(RegistrationActivity::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'scanned_by_agent_id');
    }

    public function participantGroup()
    {
        return $this->belongsTo(ParticipantGroup::class);
    }

    /**
     * Retourne l'URL de scan pour ce participant (basée sur l'ID).
     */
    public function getScanUrl()
    {
        return url("/api/scan/{$this->id}");
    }

    /**
     * Obtenir l'URL correcte pour le reçu de paiement
     */
    public function getPaymentReceiptUrlAttribute()
    {
        if (!$this->payment_receipt) {
            return null;
        }

        // Si c'est déjà une URL complète (Cloudinary), la retourner directement
        if (str_starts_with($this->payment_receipt, 'http')) {
            return $this->payment_receipt;
        }

        // Si c'est un chemin commençant par 'assets/', utiliser l'URL du site public
        if (str_starts_with($this->payment_receipt, 'assets/')) {
            return rtrim(env('PUBLIC_SITE_URL', 'https://digital.saint-michel-archange.com'), '/') . '/' . $this->payment_receipt;
        }

        // Sinon, considérer que c'est un fichier local dans storage
        return asset('storage/' . $this->payment_receipt);
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
