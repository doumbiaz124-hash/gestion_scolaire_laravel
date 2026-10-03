<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['nom_classe', 'effectif_classe', 'frais_scolaire', 'cycle', 'classe_suivante_id'];

    protected $casts = [
        'frais_scolaire' => 'decimal:2',
    ];

    public const CYCLES = [
        'jardin' => 'Jardin (pas de bulletin, passage direct)',
        'premier_cycle' => '1er cycle (moyenne de passage : 5,00)',
        'second_cycle' => '2nd cycle (moyenne de passage : 10,00)',
        'lycee' => 'Lycée (moyenne de passage : 10,00)',
    ];

    public function classeSuivante(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_suivante_id');
    }

    public function matieres(): HasMany
    {
        return $this->hasMany(Matiere::class);
    }

    /**
     * Moyenne minimale pour passer en classe supérieure, selon le cycle.
     * Retourne null pour le jardin (passage automatique, pas de bulletin).
     */
    public function moyennePassage(): ?float
    {
        return match ($this->cycle) {
            'jardin' => null,
            'premier_cycle' => 5.00,
            'second_cycle', 'lycee' => 10.00,
            default => 10.00,
        };
    }

    public function emploisDuTemps(): HasMany
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    public function series(): HasMany
    {
        return $this->hasMany(Serie::class);
    }

    public function etudiants(): HasMany
    {
        return $this->hasMany(Etudiant::class);
    }

    public function enseignants(): HasMany
    {
        return $this->hasMany(Enseignant::class);
    }
}
