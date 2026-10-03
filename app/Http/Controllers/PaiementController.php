<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Paiement;
use App\Services\PaiementService;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    /**
     * Liste de tous les paiements, triable par nom, prénom, classe, date ou année.
     */
    public function index(Request $request)
    {
        $query = Paiement::with('etudiant.classe');

        if ($request->filled('classe_id')) {
            $query->whereHas('etudiant', fn ($q) => $q->where('classe_id', $request->input('classe_id')));
        }

        if ($request->filled('annee_scolaire')) {
            $query->where('annee_scolaire', $request->input('annee_scolaire'));
        }

        $sort = $request->input('sort', 'date');
        $dir = $request->input('dir', 'desc') === 'asc' ? 'asc' : 'desc';

        match ($sort) {
            'nom' => $query->join('etudiants', 'paiements.etudiant_id', '=', 'etudiants.id')
                ->orderBy('etudiants.nom_etudiant', $dir)
                ->select('paiements.*'),
            'prenom' => $query->join('etudiants', 'paiements.etudiant_id', '=', 'etudiants.id')
                ->orderBy('etudiants.prenom_etudiant', $dir)
                ->select('paiements.*'),
            'classe' => $query->join('etudiants', 'paiements.etudiant_id', '=', 'etudiants.id')
                ->join('classes', 'etudiants.classe_id', '=', 'classes.id')
                ->orderBy('classes.nom_classe', $dir)
                ->select('paiements.*'),
            'annee' => $query->orderBy('annee_scolaire', $dir),
            default => $query->orderBy('date_paiement', $dir),
        };

        $paiements = $query->paginate(15)->withQueryString();
        $classes = Classe::orderBy('nom_classe')->get();
        $annees = Paiement::select('annee_scolaire')->distinct()->orderByDesc('annee_scolaire')->pluck('annee_scolaire')->filter();

        return view('paiements.index', compact('paiements', 'classes', 'annees', 'sort', 'dir'));
    }

    /**
     * Consultation des soldes (reste à payer) des étudiants, avec filtre nom/prénom/classe,
     * sans avoir besoin de passer par la création d'un paiement.
     */
    public function soldes(Request $request)
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
        $anneeScolaire = $request->input('annee_scolaire', date('Y') . '-' . (date('Y') + 1));

        $soldes = $etudiants->getCollection()->map(function ($etudiant) use ($anneeScolaire) {
            return [
                'etudiant' => $etudiant,
                'frais' => PaiementService::fraisPour($etudiant),
                'paye' => PaiementService::totalPaye($etudiant->id, $anneeScolaire),
                'reste' => PaiementService::reste($etudiant, $anneeScolaire),
            ];
        });

        return view('paiements.soldes', compact('etudiants', 'soldes', 'classes', 'sort', 'dir', 'anneeScolaire'));
    }

    /**
     * Étape 1 : sélection de l'étudiant (triable par classe, nom, prénom) pour un nouveau paiement.
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

        return view('paiements.choisir_etudiant', compact('etudiants', 'classes', 'sort', 'dir'));
    }

    /**
     * Étape 2 : formulaire de paiement pour un étudiant donné, avec historique et reste à payer.
     */
    public function create(Etudiant $etudiant, Request $request)
    {
        $etudiant->load('classe');
        $anneeScolaire = $request->input('annee_scolaire', $etudiant->annee_scolaire ?? date('Y'));

        $frais = PaiementService::fraisPour($etudiant);
        $reste = PaiementService::reste($etudiant, $anneeScolaire);

        $historique = Paiement::where('etudiant_id', $etudiant->id)
            ->where('annee_scolaire', $anneeScolaire)
            ->orderByDesc('date_paiement')
            ->get();

        $soldeAtteint = $reste <= 0;

        // Vérifie s'il reste un solde impayé de l'année scolaire précédente (information seulement,
        // ne bloque pas le paiement de l'année en cours).
        $anneePrecedente = $this->anneePrecedente($anneeScolaire);
        $resteAnneePrecedente = null;
        if ($anneePrecedente) {
            $resteCalcule = PaiementService::reste($etudiant, $anneePrecedente);
            if ($resteCalcule > 0) {
                $resteAnneePrecedente = $resteCalcule;
            }
        }

        return view('paiements.create', compact(
            'etudiant', 'frais', 'reste', 'historique', 'anneeScolaire', 'soldeAtteint', 'resteAnneePrecedente', 'anneePrecedente'
        ));
    }

    /**
     * Retourne l'année scolaire précédente au format "AAAA-AAAA", ou null si le format est inattendu.
     */
    private function anneePrecedente(string $anneeScolaire): ?string
    {
        if (preg_match('/^(\d{4})-(\d{4})$/', trim($anneeScolaire), $m)) {
            return ($m[1] - 1) . '-' . ($m[2] - 1);
        }

        return null;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'etudiant_id' => ['required', 'exists:etudiants,id'],
            'date_paiement' => ['required', 'date'],
            'heure_paiement' => ['nullable'],
            'montant_paye' => ['required', 'numeric', 'min:0.01'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
        ]);

        $etudiant = Etudiant::with('classe')->findOrFail($data['etudiant_id']);

        $reste = PaiementService::reste($etudiant, $data['annee_scolaire']);

        if ($reste <= 0) {
            return back()->withInput()->withErrors([
                'montant_paye' => "Les frais scolaires de {$data['annee_scolaire']} sont déjà entièrement payés pour cet étudiant.",
            ]);
        }

        if ($data['montant_paye'] > $reste) {
            return back()->withInput()->withErrors([
                'montant_paye' => "Le montant dépasse le reste à payer (" . number_format($reste, 2) . ").",
            ]);
        }

        $paiement = Paiement::create([
            ...$data,
            'reste_paiement' => $reste - $data['montant_paye'],
            'utilisateur_id' => $request->user()->id,
        ]);

        return redirect()->route('paiements.recu', $paiement)->with('success', 'Paiement enregistré avec succès.');
    }

    public function edit(Paiement $paiement)
    {
        $paiement->load('etudiant.classe');
        return view('paiements.edit', compact('paiement'));
    }

    public function update(Request $request, Paiement $paiement)
    {
        $data = $request->validate([
            'date_paiement' => ['required', 'date'],
            'heure_paiement' => ['nullable'],
            'montant_paye' => ['required', 'numeric', 'min:0.01'],
        ]);

        $etudiant = $paiement->etudiant;
        $resteDisponible = PaiementService::reste($etudiant, $paiement->annee_scolaire, $paiement->id);

        if ($data['montant_paye'] > $resteDisponible) {
            return back()->withInput()->withErrors([
                'montant_paye' => "Le montant dépasse le reste disponible (" . number_format($resteDisponible, 2) . ").",
            ]);
        }

        $paiement->update($data);
        PaiementService::recalculer($etudiant->id, $paiement->annee_scolaire);

        return redirect()->route('paiements.index')->with('success', 'Paiement mis à jour.');
    }

    public function destroy(Paiement $paiement)
    {
        $etudiantId = $paiement->etudiant_id;
        $annee = $paiement->annee_scolaire;

        $paiement->delete();
        PaiementService::recalculer($etudiantId, $annee);

        return redirect()->route('paiements.index')->with('success', 'Paiement supprimé.');
    }

    /**
     * Historique des paiements d'un étudiant, triable par date, classe, année.
     */
    public function historique(Etudiant $etudiant, Request $request)
    {
        $query = Paiement::where('etudiant_id', $etudiant->id);

        $sort = $request->input('sort', 'date');
        $dir = $request->input('dir', 'desc') === 'asc' ? 'asc' : 'desc';

        match ($sort) {
            'annee' => $query->orderBy('annee_scolaire', $dir),
            default => $query->orderBy('date_paiement', $dir),
        };

        $paiements = $query->paginate(15)->withQueryString();

        return view('paiements.historique', compact('etudiant', 'paiements', 'sort', 'dir'));
    }

    /**
     * Reçu de paiement imprimable, avec le rôle de l'utilisateur pour signature.
     */
    public function recu(Paiement $paiement)
    {
        $paiement->load('etudiant.classe', 'utilisateur.role');
        return view('paiements.recu', compact('paiement'));
    }
}
