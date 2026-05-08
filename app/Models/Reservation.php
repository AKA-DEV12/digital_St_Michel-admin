<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'group_name',
        'other_group_name',
        'reservation_object',
        'reservation_date',
        'time_slot',
        'room_id',
        'status',
        'price',
        'pricing_type',
        'payment_email',
        'payment_operator',
        'payment_receipt',
    ];

    protected $casts = [
        'time_slot' => 'array',
        'reservation_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get time slots as formatted string for display
     */
    public function getTimeSlotsDisplayAttribute()
    {
        if (is_array($this->time_slot)) {
            return implode(', ', $this->time_slot);
        }
        
        return $this->time_slot;
    }

    /**
     * Get number of time slots
     */
    public function getTimeSlotsCountAttribute()
    {
        if (is_array($this->time_slot)) {
            return count($this->time_slot);
        }
        
        return $this->time_slot ? 1 : 0;
    }

    /**
     * Check if reservation has multiple time slots
     */
    public function hasMultipleTimeSlots()
    {
        return $this->getTimeSlotsCountAttribute() > 1;
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
}
