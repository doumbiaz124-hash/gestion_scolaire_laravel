<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Etudiant;
use App\Services\BulletinService;
use Illuminate\Http\Request;

class BulletinController extends Controller
{
    /**
     * Recherche/filtre d'un étudiant (nom, prénom, classe) pour consulter/imprimer son bulletin.
     */
    public function choisirEtudiant(Request $request)
    {
        $query = Etudiant::with('classe');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nom_etudiant', 'like', "%{$search}%")
                  ->orWhere('prenom_etudiant', 'like', "%{$search}%");
            });
        }

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->input('classe_id'));
        }

        $sort = $request->input('sort', 'classe');
        $dir = $request->input('dir', 'asc') === 'desc' ? 'desc' : 'asc';

        match ($sort) {
            'nom' => $query->orderBy('nom_etudiant', $dir),
            'prenom' => $query->orderBy('prenom_etudiant', $dir),
            default => $query->join('classes', 'etudiants.classe_id', '=', 'classes.id')
                ->orderBy('classes.nom_classe', $dir)
                ->select('etudiants.*'),
        };

        $etudiants = $query->paginate(15)->withQueryString();
        $classes = Classe::orderBy('nom_classe')->get();

        return view('bulletins.choisir_etudiant', compact('etudiants', 'classes', 'sort', 'dir'));
    }

    /**
     * Bulletin imprimable d'un étudiant pour un trimestre donné (avec moyenne annuelle au 3e).
     */
    public function etudiant(Etudiant $etudiant, Request $request)
    {
        $etudiant->load('classe');
        $anneeScolaire = $request->input('annee_scolaire', $etudiant->annee_scolaire ?? date('Y'));
        $trimestre = (int) $request->input('trimestre', 1);

        $bulletin = BulletinService::bulletin($etudiant, $trimestre, $anneeScolaire);
        $moyenneAnnuelle = $trimestre == 3 ? BulletinService::moyenneAnnuelle($etudiant, $anneeScolaire) : null;

        return view('bulletins.etudiant', compact('bulletin', 'moyenneAnnuelle', 'trimestre', 'anneeScolaire', 'etudiant'));
    }

    /**
     * Impression groupée des bulletins de toute une classe pour un trimestre donné.
     */
    public function classe(Classe $classe, Request $request)
    {
        $trimestre = (int) $request->input('trimestre', 1);
        $anneeScolaire = $request->input('annee_scolaire', date('Y'));

        $etudiants = $classe->etudiants()->orderBy('nom_etudiant')->orderBy('prenom_etudiant')->get();

        $bulletins = $etudiants->map(function ($etudiant) use ($trimestre, $anneeScolaire) {
            $bulletin = BulletinService::bulletin($etudiant, $trimestre, $anneeScolaire);
            $bulletin['moyenne_annuelle'] = $trimestre == 3
                ? BulletinService::moyenneAnnuelle($etudiant, $anneeScolaire)
                : null;
            return $bulletin;
        });

        return view('bulletins.classe', compact('classe', 'bulletins', 'trimestre', 'anneeScolaire'));
    }
}
