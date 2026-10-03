<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_etudiant',
        'prenom_etudiant',
        'date_naissance',
        'tel_tuteur',
        'adresse',
        'annee_scolaire',
        'sexe_etudiant',
        'classe_id',
        'serie_id',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function nomComplet(): string
    {
        return "{$this->prenom_etudiant} {$this->nom_etudiant}";
    }
}
