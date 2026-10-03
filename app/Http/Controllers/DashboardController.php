<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'etudiants' => Etudiant::count(),
            'enseignants' => Enseignant::count(),
            'classes' => Classe::count(),
        ];

        $etudiants = null;
        if ($user->hasModule('etudiants')) {
            $etudiants = $this->sortedEtudiants($request);
        }

        $enseignants = null;
        if ($user->hasModule('enseignants')) {
            $enseignants = $this->sortedEnseignants($request);
        }

        return view('dashboard', compact('stats', 'etudiants', 'enseignants'));
    }

    private function sortedEtudiants(Request $request)
    {
        $sort = $request->input('sort_etudiants', 'nom');
        $dir = $request->input('dir_etudiants', 'asc') === 'desc' ? 'desc' : 'asc';

        $query = Etudiant::with('classe', 'serie');

        match ($sort) {
            'classe' => $query->join('classes', 'etudiants.classe_id', '=', 'classes.id')
                ->orderBy('classes.nom_classe', $dir)
                ->select('etudiants.*'),
            'serie' => $query->join('series', 'etudiants.serie_id', '=', 'series.id')
                ->orderBy('series.nom_serie', $dir)
                ->select('etudiants.*'),
            default => $query->orderBy('nom_etudiant', $dir),
        };

        return $query->limit(10)->get();
    }

    private function sortedEnseignants(Request $request)
    {
        $sort = $request->input('sort_enseignants', 'nom');
        $dir = $request->input('dir_enseignants', 'asc') === 'desc' ? 'desc' : 'asc';

        $query = Enseignant::with('classe');

        match ($sort) {
            'classe' => $query->join('classes', 'enseignants.classe_id', '=', 'classes.id')
                ->orderBy('classes.nom_classe', $dir)
                ->select('enseignants.*'),
            'matiere' => $query->orderBy('matiere_enseignee', $dir),
            default => $query->orderBy('nom_enseignant', $dir),
        };

        return $query->limit(10)->get();
    }
}
