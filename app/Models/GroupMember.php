<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupMember extends Model
{
    protected $fillable = [
        'nom_prenom',
        'photo',
        'date_naissance',
        'situation_professionnelle',
        'nombre_enfant',
        'situation_matrimoniale',
        'created_by', // on garde pour savoir qui a créé l'enregistrement parent
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_adhesion' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getFormattedDateAttribute()
    {
        return $this->pivot && $this->pivot->date_adhesion 
            ? \Carbon\Carbon::parse($this->pivot->date_adhesion)->format('d/m/Y') 
            : null;
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            if (str_starts_with($this->photo, 'http')) {
                return $this->photo;
            }
            return asset('storage/' . $this->photo);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nom_prenom) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Relation avec les groupes du membre
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class)
            ->withPivot(['responsabilite', 'date_adhesion', 'created_by'])
            ->withTimestamps();
    }

    /**
     * Relation avec l'utilisateur qui a créé le membre
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Obtenir les noms des groupes
     */
    public function getGroupNamesAttribute(): string
    {
        return $this->groups->pluck('nom_groupe')->join(', ');
    }

    /**
     * Compatibilité pour les vues (lit depuis le pivot si disponible)
     */
    public function getResponsabiliteAttribute()
    {
        return $this->pivot ? $this->pivot->responsabilite : null;
    }

    public function getDateAdhesionAttribute()
    {
        return $this->pivot && $this->pivot->date_adhesion 
            ? \Carbon\Carbon::parse($this->pivot->date_adhesion) 
            : null;
    }
}
