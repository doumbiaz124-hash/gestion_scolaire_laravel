<?php

namespace App\Services;

use App\Models\Etudiant;
use App\Models\Paiement;

class PaiementService
{
    /**
     * Retourne les frais scolaires applicables à un étudiant (définis sur sa classe).
     */
    public static function fraisPour(Etudiant $etudiant): float
    {
        return (float) ($etudiant->classe->frais_scolaire ?? 0);
    }

    /**
     * Retourne le total déjà payé par un étudiant pour une année scolaire donnée.
     */
    public static function totalPaye(int $etudiantId, ?string $anneeScolaire, ?int $excludePaiementId = null): float
    {
        $query = Paiement::where('etudiant_id', $etudiantId)
            ->where('annee_scolaire', $anneeScolaire);

        if ($excludePaiementId) {
            $query->where('id', '!=', $excludePaiementId);
        }

        return (float) $query->sum('montant_paye');
    }

    /**
     * Retourne le reste à payer pour un étudiant, pour une année scolaire donnée.
     */
    public static function reste(Etudiant $etudiant, ?string $anneeScolaire, ?int $excludePaiementId = null): float
    {
        $frais = self::fraisPour($etudiant);
        $paye = self::totalPaye($etudiant->id, $anneeScolaire, $excludePaiementId);

        return max(0, round($frais - $paye, 2));
    }

    /**
     * Recalcule le "reste_paiement" de chaque paiement d'un étudiant pour une année,
     * en les prenant dans l'ordre chronologique (date puis création).
     */
    public static function recalculer(int $etudiantId, ?string $anneeScolaire): void
    {
        $etudiant = Etudiant::find($etudiantId);
        if (!$etudiant) {
            return;
        }

        $frais = self::fraisPour($etudiant);

        $paiements = Paiement::where('etudiant_id', $etudiantId)
            ->where('annee_scolaire', $anneeScolaire)
            ->orderBy('date_paiement')
            ->orderBy('id')
            ->get();

        $cumul = 0;
        foreach ($paiements as $paiement) {
            $cumul += (float) $paiement->montant_paye;
            $reste = max(0, round($frais - $cumul, 2));
            if ((float) $paiement->reste_paiement !== $reste) {
                $paiement->update(['reste_paiement' => $reste]);
            }
        }
    }
}
