<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Serie;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    public function index()
    {
        $series = Serie::with('classe')->orderBy('nom_serie')->paginate(10);
        return view('series.index', compact('series'));
    }

    public function create()
    {
        $classes = Classe::orderBy('nom_classe')->get();
        return view('series.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom_serie' => ['required', 'string', 'max:255'],
            'classe_id' => ['required', 'exists:classes,id'],
        ]);

        Serie::create($data);

        return redirect()->route('series.index')->with('success', 'Série créée avec succès.');
    }

    public function edit(Serie $serie)
    {
        $classes = Classe::orderBy('nom_classe')->get();
        return view('series.edit', compact('serie', 'classes'));
    }

    public function update(Request $request, Serie $serie)
    {
        $data = $request->validate([
            'nom_serie' => ['required', 'string', 'max:255'],
            'classe_id' => ['required', 'exists:classes,id'],
        ]);

        $serie->update($data);

        return redirect()->route('series.index')->with('success', 'Série mise à jour.');
    }

    public function destroy(Serie $serie)
    {
        $serie->delete();
        return redirect()->route('series.index')->with('success', 'Série supprimée.');
    }
}
