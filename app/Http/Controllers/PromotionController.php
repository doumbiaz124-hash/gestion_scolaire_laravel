<?php

namespace App\Http\Controllers;

use App\Services\PromotionService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Aperçu du passage en classe supérieure pour l'année scolaire indiquée.
     */
    public function index(Request $request)
    {
        $anneeScolaire = $request->input('annee_scolaire', date('Y') . '-' . (date('Y') + 1));
        $apercu = PromotionService::apercu($anneeScolaire);
        $anneeSuivante = PromotionService::anneeSuivante($anneeScolaire);

        return view('promotion.index', compact('apercu', 'anneeScolaire', 'anneeSuivante'));
    }

    /**
     * Exécute le passage en classe supérieure : met à jour classe et année scolaire de chaque étudiant.
     */
    public function executer(Request $request)
    {
        $data = $request->validate([
            'annee_scolaire' => ['required', 'string', 'max:20'],
        ]);

        $nombre = PromotionService::executer($data['annee_scolaire']);
        $anneeSuivante = PromotionService::anneeSuivante($data['annee_scolaire']);

        return redirect()->route('promotion.index')->with(
            'success',
            "{$nombre} étudiant(s) mis à jour pour l'année scolaire {$anneeSuivante}."
        );
    }
}
