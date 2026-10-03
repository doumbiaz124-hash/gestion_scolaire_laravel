<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    /**
     * Liste des classes pour accéder à la configuration de leurs matières.
     */
    public function index()
    {
        $classes = Classe::withCount('matieres')->orderBy('nom_classe')->paginate(15);
        return view('matieres.index', compact('classes'));
    }

    /**
     * Configuration des matières (rubriques) et coefficients pour une classe.
     */
    public function show(Classe $classe)
    {
        $matieres = $classe->matieres()->orderBy('nom_matiere')->get();
        return view('matieres.show', compact('classe', 'matieres'));
    }

    public function store(Request $request, Classe $classe)
    {
        $data = $request->validate([
            'nom_matiere' => ['required', 'string', 'max:255'],
            'coefficient' => ['required', 'numeric', 'min:0.1'],
        ]);

        $data['classe_id'] = $classe->id;
        Matiere::create($data);

        return redirect()->route('matieres.show', $classe)->with('success', 'Matière ajoutée.');
    }

    public function edit(Classe $classe, Matiere $matiere)
    {
        return view('matieres.edit', compact('classe', 'matiere'));
    }

    public function update(Request $request, Classe $classe, Matiere $matiere)
    {
        $data = $request->validate([
            'nom_matiere' => ['required', 'string', 'max:255'],
            'coefficient' => ['required', 'numeric', 'min:0.1'],
        ]);

        $matiere->update($data);

        return redirect()->route('matieres.show', $classe)->with('success', 'Matière mise à jour.');
    }

    public function destroy(Classe $classe, Matiere $matiere)
    {
        $matiere->delete();
        return redirect()->route('matieres.show', $classe)->with('success', 'Matière supprimée.');
    }
}
