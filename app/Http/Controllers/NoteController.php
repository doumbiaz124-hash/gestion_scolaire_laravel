<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Étape 1 : choisir l'étudiant pour saisir ses notes (triable par classe / nom / prénom).
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

        return view('notes.choisir_etudiant', compact('etudiants', 'classes', 'sort', 'dir'));
    }

    /**
     * Étape 2 : formulaire de saisie (choix de la matière + trimestre), avec le tableau
     * des notes déjà saisies pour cet étudiant.
     */
    public function saisie(Etudiant $etudiant, Request $request)
    {
        $etudiant->load('classe');
        $anneeScolaire = $request->input('annee_scolaire', $etudiant->annee_scolaire ?? date('Y'));

        if (!$etudiant->classe) {
            return back()->withErrors(['classe' => "Cet étudiant n'est affecté à aucune classe."]);
        }

        $matieres = $etudiant->classe->matieres()->orderBy('nom_matiere')->get();

        $notes = Note::where('etudiant_id', $etudiant->id)
            ->where('annee_scolaire', $anneeScolaire)
            ->with('matiere')
            ->get()
            ->groupBy('matiere_id');

        return view('notes.saisie', compact('etudiant', 'matieres', 'notes', 'anneeScolaire'));
    }

    /**
     * Enregistre (ou met à jour) la note de devoir/composition d'une matière pour un trimestre.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'etudiant_id' => ['required', 'exists:etudiants,id'],
            'matiere_id' => ['required', 'exists:matieres,id'],
            'trimestre' => ['required', 'integer', 'in:1,2,3'],
            'note_devoir' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'note_composition' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
        ]);

        $etudiant = Etudiant::findOrFail($data['etudiant_id']);

        Note::updateOrCreate(
            [
                'etudiant_id' => $data['etudiant_id'],
                'matiere_id' => $data['matiere_id'],
                'trimestre' => $data['trimestre'],
                'annee_scolaire' => $data['annee_scolaire'],
            ],
            [
                'note_devoir' => $data['note_devoir'] ?? null,
                'note_composition' => $data['note_composition'] ?? null,
                'classe_id' => $etudiant->classe_id,
            ]
        );

        return redirect()
            ->route('notes.saisie', ['etudiant' => $data['etudiant_id'], 'annee_scolaire' => $data['annee_scolaire']])
            ->with('success', 'Note enregistrée avec succès.');
    }

    public function destroy(Note $note)
    {
        $etudiantId = $note->etudiant_id;
        $annee = $note->annee_scolaire;
        $note->delete();

        return redirect()
            ->route('notes.saisie', ['etudiant' => $etudiantId, 'annee_scolaire' => $annee])
            ->with('success', 'Note supprimée.');
    }
}
