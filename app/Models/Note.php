<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'matiere_id',
        'classe_id',
        'trimestre',
        'note_devoir',
        'note_composition',
        'annee_scolaire',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    /**
     * Moyenne de la matière pour ce trimestre : (devoir + composition) / 2.
     */
    public function moyenne(): float
    {
        $devoir = $this->note_devoir ?? 0;
        $composition = $this->note_composition ?? 0;

        return round(($devoir + $composition) / 2, 2);
    }
}
