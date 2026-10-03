<?php

namespace App\Services;

use App\Models\Classe;
use App\Models\Etudiant;

class PromotionService
{
    /**
     * Calcule l'année scolaire suivante à partir d'une chaîne "AAAA-AAAA".
     * Exemple : "2026-2027" -> "2027-2028".
     */
    public static function anneeSuivante(string $anneeScolaire): string
    {
        if (preg_match('/^(\d{4})-(\d{4})$/', trim($anneeScolaire), $m)) {
            return ($m[1] + 1) . '-' . ($m[2] + 1);
        }

        // Format inattendu : on retourne tel quel pour éviter de casser les données.
        return $anneeScolaire;
    }

    /**
     * Construit un aperçu du passage en classe supérieure pour une année scolaire donnée,
     * sans modifier les données.
     */
    public static function apercu(string $anneeScolaire): array
    {
        $classes = Classe::with(['etudiants' => function ($q) use ($anneeScolaire) {
            $q->where('annee_scolaire', $anneeScolaire)->orderBy('nom_etudiant');
        }, 'classeSuivante'])->orderBy('nom_classe')->get();

        $resultat = [];

        foreach ($classes as $classe) {
            $lignes = [];

            foreach ($classe->etudiants as $etudiant) {
                if ($classe->cycle === 'jardin') {
                    $lignes[] = [
                        'etudiant' => $etudiant,
                        'moyenne' => null,
                        'decision' => 'admis',
                        'motif' => 'Jardin : passage direct',
                    ];
                    continue;
                }

                $moyenne = BulletinService::moyenneAnnuelle($etudiant, $anneeScolaire);
                $seuil = $classe->moyennePassage();

                if ($moyenne === null) {
                    $decision = 'admis';
                    $motif = 'Aucune note saisie : admis par défaut';
                } elseif ($moyenne >= $seuil) {
                    $decision = 'admis';
                    $motif = "Moyenne {$moyenne} ≥ {$seuil}";
                } else {
                    $decision = 'redouble';
                    $motif = "Moyenne {$moyenne} < {$seuil}";
                }

                $lignes[] = [
                    'etudiant' => $etudiant,
                    'moyenne' => $moyenne,
                    'decision' => $decision,
                    'motif' => $motif,
                ];
            }

            $resultat[] = [
                'classe' => $classe,
                'lignes' => $lignes,
            ];
        }

        return $resultat;
    }

    /**
     * Exécute le passage en classe supérieure : met à jour la classe et l'année scolaire
     * de chaque étudiant selon la décision (admis -> classe suivante, redouble -> même classe).
     */
    public static function executer(string $anneeScolaire): int
    {
        $apercu = self::apercu($anneeScolaire);
        $anneeSuivante = self::anneeSuivante($anneeScolaire);
        $compteur = 0;

        foreach ($apercu as $bloc) {
            $classe = $bloc['classe'];

            foreach ($bloc['lignes'] as $ligne) {
                /** @var Etudiant $etudiant */
                $etudiant = $ligne['etudiant'];

                if ($ligne['decision'] === 'admis' && $classe->classe_suivante_id) {
                    $etudiant->classe_id = $classe->classe_suivante_id;
                    $etudiant->serie_id = null; // la série doit être réattribuée dans la nouvelle classe
                } else {
                    // Redouble, ou admis mais pas de classe suivante définie (ex : classe terminale) :
                    // l'étudiant reste dans la même classe.
                }

                $etudiant->annee_scolaire = $anneeSuivante;
                $etudiant->save();
                $compteur++;
            }
        }

        return $compteur;
    }
}
