<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MassRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requested_date',
        'time_slots',
        'name1',
        'name2',
        'name3',
        'request_object',
        'amount',
        'status',
        'email',
        'phone',
        'payment_operator',
        'payment_receipt',
    ];

    protected $casts = [
        'time_slots' => 'array',
        'requested_date' => 'date',
    ];

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
}
