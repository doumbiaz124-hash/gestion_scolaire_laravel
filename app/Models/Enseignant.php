<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enseignant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_enseignant',
        'prenom_enseignant',
        'tel_enseignant',
        'adresse_enseignant',
        'matiere_enseignee',
        'classe_id',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }
}
