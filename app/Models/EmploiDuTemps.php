<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploiDuTemps extends Model
{
    use HasFactory;

    protected $table = 'emplois_du_temps';

    protected $fillable = [
        'classe_id',
        'jour',
        'heure_debut',
        'heure_fin',
        'matiere',
        'enseignant_id',
    ];

    public const JOURS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function libelle(): string
    {
        $label = $this->matiere;

        if ($this->enseignant) {
            $label .= ' (' . $this->enseignant->prenom_enseignant . ' ' . $this->enseignant->nom_enseignant . ')';
        }

        return $label;
    }
}
