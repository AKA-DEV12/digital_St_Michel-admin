<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['name', 'description', 'capacity', 'icon', 'status', 'price_per_hour', 'price_half_day', 'price_full_day', 'wave_number', 'mtn_number', 'orange_number', 'moov_number'];

    protected $casts = [
        'price_per_hour' => 'decimal:2',
        'price_half_day' => 'decimal:2',
        'price_full_day' => 'decimal:2',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Calculate price based on time slot duration
     */
    public function calculatePrice($timeSlot)
    {
        // For bloc reservations
        if ($timeSlot === 'half_day') {
            return $this->price_half_day ?: 0;
        }
        if ($timeSlot === 'full_day') {
            return $this->price_full_day ?: 0;
        }

        // For hourly slots - parse the time range
        if (preg_match('/(\d{2}:\d{2})\s*-\s*(\d{2}:\d{2})/', $timeSlot, $matches)) {
            $startTime = \Carbon\Carbon::createFromFormat('H:i', $matches[1]);
            $endTime = \Carbon\Carbon::createFromFormat('H:i', $matches[2]);
            $hours = $startTime->diffInHours($endTime);

            // Apply pricing logic
            if ($hours >= 8) {
                return $this->price_full_day ?: 0;
            } elseif ($hours >= 4) {
                return $this->price_half_day ?: 0;
            } else {
                // Minimum 2 hours for hourly pricing
                $actualHours = max(2, $hours);
                return $this->price_per_hour ? ($this->price_per_hour * $actualHours) : 0;
            }
        }

        return 0;
    }
}
