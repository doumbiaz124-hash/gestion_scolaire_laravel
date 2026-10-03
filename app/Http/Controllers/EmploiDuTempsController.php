<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\EmploiDuTemps;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class EmploiDuTempsController extends Controller
{
    /**
     * Liste des classes pour accéder à leur emploi du temps.
     */
    public function index()
    {
        $classes = Classe::withCount('emploisDuTemps')->orderBy('nom_classe')->paginate(15);
        return view('emploi_du_temps.index', compact('classes'));
    }

    /**
     * Affiche l'emploi du temps (grille hebdomadaire) d'une classe.
     */
    public function show(Classe $classe)
    {
        $creneaux = $classe->emploisDuTemps()->with('enseignant')->get();
        $grille = $this->construireGrille($creneaux);

        return view('emploi_du_temps.show', [
            'classe' => $classe,
            'creneaux' => $creneaux,
            'grille' => $grille,
            'jours' => EmploiDuTemps::JOURS,
        ]);
    }

    public function create(Classe $classe)
    {
        $enseignants = Enseignant::orderBy('nom_enseignant')->get();
        return view('emploi_du_temps.create', [
            'classe' => $classe,
            'enseignants' => $enseignants,
            'jours' => EmploiDuTemps::JOURS,
        ]);
    }

    public function store(Request $request, Classe $classe)
    {
        $data = $this->validateData($request);
        $data['classe_id'] = $classe->id;

        EmploiDuTemps::create($data);

        return redirect()->route('emploi-du-temps.show', $classe)->with('success', 'Créneau ajouté à l\'emploi du temps.');
    }

    public function edit(Classe $classe, EmploiDuTemps $creneau)
    {
        $enseignants = Enseignant::orderBy('nom_enseignant')->get();
        return view('emploi_du_temps.edit', [
            'classe' => $classe,
            'creneau' => $creneau,
            'enseignants' => $enseignants,
            'jours' => EmploiDuTemps::JOURS,
        ]);
    }

    public function update(Request $request, Classe $classe, EmploiDuTemps $creneau)
    {
        $data = $this->validateData($request);

        $creneau->update($data);

        return redirect()->route('emploi-du-temps.show', $classe)->with('success', 'Créneau mis à jour.');
    }

    public function destroy(Classe $classe, EmploiDuTemps $creneau)
    {
        $creneau->delete();
        return redirect()->route('emploi-du-temps.show', $classe)->with('success', 'Créneau supprimé.');
    }

    /**
     * Vue imprimable de l'emploi du temps d'une classe.
     */
    public function imprimer(Classe $classe)
    {
        $creneaux = $classe->emploisDuTemps()->with('enseignant')->get();
        $grille = $this->construireGrille($creneaux);

        return view('emploi_du_temps.imprimer', [
            'classe' => $classe,
            'grille' => $grille,
            'jours' => EmploiDuTemps::JOURS,
        ]);
    }

    /**
     * Construit une grille [jour][heure_debut-heure_fin] => créneau pour l'affichage.
     */
    private function construireGrille($creneaux): array
    {
        $grille = [];
        foreach (EmploiDuTemps::JOURS as $jour) {
            $grille[$jour] = [];
        }

        foreach ($creneaux as $creneau) {
            $grille[$creneau->jour][] = $creneau;
        }

        foreach ($grille as $jour => $liste) {
            usort($liste, fn ($a, $b) => strcmp($a->heure_debut, $b->heure_debut));
            $grille[$jour] = $liste;
        }

        return $grille;
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'jour' => ['required', 'in:' . implode(',', EmploiDuTemps::JOURS)],
            'heure_debut' => ['required'],
            'heure_fin' => ['required', 'after:heure_debut'],
            'matiere' => ['required', 'string', 'max:255'],
            'enseignant_id' => ['nullable', 'exists:enseignants,id'],
        ]);
    }
}
