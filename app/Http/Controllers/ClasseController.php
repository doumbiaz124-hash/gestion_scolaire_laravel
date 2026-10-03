<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::withCount('etudiants')->orderBy('nom_classe')->paginate(10);
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        $classes = Classe::orderBy('nom_classe')->get();
        return view('classes.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom_classe' => ['required', 'string', 'max:255'],
            'effectif_classe' => ['nullable', 'integer', 'min:0'],
            'frais_scolaire' => ['nullable', 'numeric', 'min:0'],
            'cycle' => ['required', 'in:jardin,premier_cycle,second_cycle,lycee'],
            'classe_suivante_id' => ['nullable', 'exists:classes,id'],
        ]);

        Classe::create($data);

        return redirect()->route('classes.index')->with('success', 'Classe créée avec succès.');
    }

    public function show(Classe $classe)
    {
        $classe->load('etudiants', 'series', 'enseignants');
        return view('classes.show', compact('classe'));
    }

    public function edit(Classe $classe)
    {
        $classes = Classe::where('id', '!=', $classe->id)->orderBy('nom_classe')->get();
        return view('classes.edit', compact('classe', 'classes'));
    }

    public function update(Request $request, Classe $classe)
    {
        $data = $request->validate([
            'nom_classe' => ['required', 'string', 'max:255'],
            'effectif_classe' => ['nullable', 'integer', 'min:0'],
            'frais_scolaire' => ['nullable', 'numeric', 'min:0'],
            'cycle' => ['required', 'in:jardin,premier_cycle,second_cycle,lycee'],
            'classe_suivante_id' => ['nullable', 'exists:classes,id'],
        ]);

        $classe->update($data);

        return redirect()->route('classes.index')->with('success', 'Classe mise à jour.');
    }

    public function destroy(Classe $classe)
    {
        $classe->delete();
        return redirect()->route('classes.index')->with('success', 'Classe supprimée.');
    }
}
