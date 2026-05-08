<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Group extends Model
{
    protected $fillable = [
        'nom_groupe',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec les membres du groupe
     */
    public function members()
    {
        return $this->belongsToMany(GroupMember::class)
            ->withPivot(['responsabilite', 'date_adhesion', 'created_by'])
            ->withTimestamps();
    }

    /**
     * Relation avec les utilisateurs associés à ce groupe
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Obtenir le nombre de membres dans le groupe
     */
    public function getMembersCountAttribute(): int
    {
        return $this->members()->count();
    }

    /**
     * Scope pour les groupes avec au moins un membre
     */
    public function scopeWithMembers($query)
    {
        return $query->withCount('members');
    }
}
