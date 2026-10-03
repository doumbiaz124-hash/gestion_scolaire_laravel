<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
    public function index(Request $request)
    {
        $query = Enseignant::with('classe');

        $sort = $request->input('sort', 'nom');
        $dir = $request->input('dir', 'asc') === 'desc' ? 'desc' : 'asc';

        match ($sort) {
            'classe' => $query->leftJoin('classes', 'enseignants.classe_id', '=', 'classes.id')
                ->orderBy('classes.nom_classe', $dir)
                ->select('enseignants.*'),
            'matiere' => $query->orderBy('matiere_enseignee', $dir),
            'prenom' => $query->orderBy('prenom_enseignant', $dir),
            default => $query->orderBy('nom_enseignant', $dir),
        };

        $enseignants = $query->paginate(15)->withQueryString();

        return view('enseignants.index', compact('enseignants', 'sort', 'dir'));
    }

    public function create()
    {
        $classes = Classe::orderBy('nom_classe')->get();
        return view('enseignants.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Enseignant::create($data);

        return redirect()->route('enseignants.index')->with('success', 'Enseignant ajouté avec succès.');
    }

    public function edit(Enseignant $enseignant)
    {
        $classes = Classe::orderBy('nom_classe')->get();
        return view('enseignants.edit', compact('enseignant', 'classes'));
    }

    public function update(Request $request, Enseignant $enseignant)
    {
        $data = $this->validateData($request);

        $enseignant->update($data);

        return redirect()->route('enseignants.index')->with('success', 'Enseignant mis à jour.');
    }

    public function destroy(Enseignant $enseignant)
    {
        $enseignant->delete();
        return redirect()->route('enseignants.index')->with('success', 'Enseignant supprimé.');
    }

    /**
     * Liste complète des enseignants, triée, prête à imprimer.
     */
    public function imprimer(Request $request)
    {
        $sort = $request->input('sort', 'nom');
        $dir = $request->input('dir', 'asc') === 'desc' ? 'desc' : 'asc';

        $query = Enseignant::with('classe');

        match ($sort) {
            'classe' => $query->leftJoin('classes', 'enseignants.classe_id', '=', 'classes.id')
                ->orderBy('classes.nom_classe', $dir)
                ->select('enseignants.*'),
            'matiere' => $query->orderBy('matiere_enseignee', $dir),
            default => $query->orderBy('nom_enseignant', $dir),
        };

        $enseignants = $query->get();

        return view('enseignants.imprimer', compact('enseignants'));
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nom_enseignant' => ['required', 'string', 'max:255'],
            'prenom_enseignant' => ['required', 'string', 'max:255'],
            'tel_enseignant' => ['nullable', 'string', 'max:50'],
            'adresse_enseignant' => ['nullable', 'string', 'max:255'],
            'matiere_enseignee' => ['nullable', 'string', 'max:255'],
            'classe_id' => ['nullable', 'exists:classes,id'],
        ]);
    }
}
