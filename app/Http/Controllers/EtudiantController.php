<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Serie;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EtudiantController extends Controller
{
    public function index(Request $request)
    {
        $query = Etudiant::with('classe', 'serie');

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

        if ($request->filled('serie_id')) {
            $query->where('serie_id', $request->input('serie_id'));
        }

        $sort = $request->input('sort', 'nom');
        $dir = $request->input('dir', 'asc') === 'desc' ? 'desc' : 'asc';

        match ($sort) {
            'classe' => $query->join('classes', 'etudiants.classe_id', '=', 'classes.id')
                ->orderBy('classes.nom_classe', $dir)
                ->select('etudiants.*'),
            'serie' => $query->leftJoin('series', 'etudiants.serie_id', '=', 'series.id')
                ->orderBy('series.nom_serie', $dir)
                ->select('etudiants.*'),
            'prenom' => $query->orderBy('prenom_etudiant', $dir),
            default => $query->orderBy('nom_etudiant', $dir),
        };

        $etudiants = $query->paginate(15)->withQueryString();

        $classes = Classe::orderBy('nom_classe')->get();
        $series = Serie::orderBy('nom_serie')->get();

        return view('etudiants.index', compact('etudiants', 'classes', 'series', 'sort', 'dir'));
    }

    public function create()
    {
        $classes = Classe::orderBy('nom_classe')->get();
        $series = Serie::orderBy('nom_serie')->get();
        return view('etudiants.create', compact('classes', 'series'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Etudiant::create($data);

        return redirect()->route('etudiants.index')->with('success', 'Étudiant ajouté avec succès.');
    }

    public function show(Etudiant $etudiant)
    {
        $etudiant->load('classe', 'serie', 'paiements');
        return view('etudiants.show', compact('etudiant'));
    }

    public function edit(Etudiant $etudiant)
    {
        $classes = Classe::orderBy('nom_classe')->get();
        $series = Serie::orderBy('nom_serie')->get();
        return view('etudiants.edit', compact('etudiant', 'classes', 'series'));
    }

    public function update(Request $request, Etudiant $etudiant)
    {
        $data = $this->validateData($request);

        $etudiant->update($data);

        return redirect()->route('etudiants.index')->with('success', 'Étudiant mis à jour.');
    }

    public function destroy(Etudiant $etudiant)
    {
        $etudiant->delete();
        return redirect()->route('etudiants.index')->with('success', 'Étudiant supprimé.');
    }

    /**
     * Liste des étudiants d'une classe, triée par ordre alphabétique, prête à imprimer.
     */
    public function listeParClasse(Classe $classe)
    {
        $etudiants = $classe->etudiants()
            ->with('serie')
            ->orderBy('nom_etudiant')
            ->orderBy('prenom_etudiant')
            ->get();

        return view('etudiants.liste_classe', compact('classe', 'etudiants'));
    }

    /**
     * Export CSV (enregistrable sur le PC) de la liste des étudiants d'une classe.
     */
    public function exportParClasse(Classe $classe): StreamedResponse
    {
        $etudiants = $classe->etudiants()
            ->with('serie')
            ->orderBy('nom_etudiant')
            ->orderBy('prenom_etudiant')
            ->get();

        $filename = 'liste_' . str_replace(' ', '_', $classe->nom_classe) . '.csv';

        $callback = function () use ($etudiants, $classe) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // BOM pour Excel
            fputcsv($handle, ['Nom', 'Prénom', 'Sexe', 'Classe', 'Date de naissance']);
            foreach ($etudiants as $etudiant) {
                fputcsv($handle, [
                    $etudiant->nom_etudiant,
                    $etudiant->prenom_etudiant,
                    $etudiant->sexe_etudiant,
                    $classe->nom_classe,
                    $etudiant->date_naissance,
                ]);
            }
            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nom_etudiant' => ['required', 'string', 'max:255'],
            'prenom_etudiant' => ['required', 'string', 'max:255'],
            'date_naissance' => ['nullable', 'date'],
            'tel_tuteur' => ['nullable', 'string', 'max:50'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'annee_scolaire' => ['nullable', 'string', 'max:20'],
            'sexe_etudiant' => ['nullable', 'in:M,F'],
            'classe_id' => ['nullable', 'exists:classes,id'],
            'serie_id' => ['nullable', 'exists:series,id'],
        ]);
    }
}
