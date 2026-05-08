<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Catechist extends Model
{
    protected $fillable = [
        'matricule',
        'nom_prenom',
        'date_naissance',
        'photo',
        'lieu_habitation',
        'situation_matrimoniale',
        'nombre_enfant',
        'antecedent',
        'antecedent_date',
        'antecedent_annee_catechese',
        'antecedent_paroisse',
        'groupe_mouvement',
        'group_id',
        'member_id',
        'situation_professionnelle',
        'baptiser',
        'date_bapteme',
        'paroisse_bapteme',
        'carnet_bapteme',
        'annee_catechese',
        'statut_catechese',
        'created_by',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'antecedent' => 'boolean',
        'antecedent_date' => 'date',
        'groupe_mouvement' => 'boolean',
        'baptiser' => 'boolean',
        'date_bapteme' => 'date',
        'nombre_enfant' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($catechist) {
            if (empty($catechist->matricule)) {
                $catechist->matricule = 'CAT' . date('Y') . str_pad(Catechist::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function member()
    {
        return $this->belongsTo(GroupMember::class, 'member_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
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

    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nom_prenom', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('matricule', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['annee_catechese'])) {
            $query->where('annee_catechese', $filters['annee_catechese']);
        }

        if (isset($filters['statut_catechese'])) {
            $query->where('statut_catechese', $filters['statut_catechese']);
        }

        return $query;
    }
}
