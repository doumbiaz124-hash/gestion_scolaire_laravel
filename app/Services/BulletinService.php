<?php

namespace App\Services;

use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Note;

class BulletinService
{
    /**
     * Construit les données du bulletin d'un étudiant pour un trimestre donné.
     * Retourne un tableau avec le détail par matière, les totaux et la moyenne.
     */
    public static function bulletin(Etudiant $etudiant, int $trimestre, string $anneeScolaire): array
    {
        $notes = Note::where('etudiant_id', $etudiant->id)
            ->where('trimestre', $trimestre)
            ->where('annee_scolaire', $anneeScolaire)
            ->get()
            ->keyBy('matiere_id');

        // La classe utilisée pour lister les matières est celle enregistrée sur les notes de cette
        // période (pour que le bulletin reste correct après un passage en classe supérieure).
        // À défaut de notes existantes, on utilise la classe actuelle de l'étudiant.
        $classeId = $notes->first()?->classe_id ?? $etudiant->classe_id;
        $matieres = $classeId ? Matiere::where('classe_id', $classeId)->orderBy('nom_matiere')->get() : collect();

        $lignes = [];
        $totalPoints = 0;
        $totalCoefficients = 0;

        foreach ($matieres as $matiere) {
            $note = $notes->get($matiere->id);
            $devoir = $note?->note_devoir;
            $composition = $note?->note_composition;
            $aUneNote = ($devoir !== null || $composition !== null);
            $moyenne = $aUneNote ? round((($devoir ?? 0) + ($composition ?? 0)) / 2, 2) : null;
            $coefficient = (float) $matiere->coefficient;
            $pointsPonderes = $moyenne !== null ? round($moyenne * $coefficient, 2) : 0;

            $lignes[] = [
                'matiere' => $matiere,
                'devoir' => $devoir,
                'composition' => $composition,
                'moyenne' => $moyenne,
                'coefficient' => $coefficient,
                'points' => $pointsPonderes,
            ];

            // Seules les matières avec au moins une note saisie comptent dans la moyenne générale,
            // pour ne pas confondre "aucune note saisie" avec une moyenne de 0.
            if ($aUneNote) {
                $totalCoefficients += $coefficient;
                $totalPoints += $pointsPonderes;
            }
        }

        $moyenneGenerale = $totalCoefficients > 0 ? round($totalPoints / $totalCoefficients, 2) : null;

        return [
            'etudiant' => $etudiant,
            'trimestre' => $trimestre,
            'annee_scolaire' => $anneeScolaire,
            'lignes' => $lignes,
            'total_points' => round($totalPoints, 2),
            'total_coefficients' => $totalCoefficients,
            'moyenne_generale' => $moyenneGenerale,
        ];
    }

    /**
     * Calcule la moyenne annuelle (moyenne des 3 trimestres) pour un étudiant.
     * Utilisée sur le bulletin du 3e trimestre.
     */
    public static function moyenneAnnuelle(Etudiant $etudiant, string $anneeScolaire): ?float
    {
        $moyennes = [];

        for ($t = 1; $t <= 3; $t++) {
            $bulletin = self::bulletin($etudiant, $t, $anneeScolaire);
            if ($bulletin['moyenne_generale'] !== null) {
                $moyennes[] = $bulletin['moyenne_generale'];
            }
        }

        if (empty($moyennes)) {
            return null;
        }

        return round(array_sum($moyennes) / count($moyennes), 2);
    }
}
