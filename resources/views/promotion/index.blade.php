@extends('layouts.app')
@section('title', 'Passage en classe supérieure')
@section('content')
<h3 class="mb-3">Passage en classe supérieure</h3>

<form method="GET" class="row g-2 mb-3 align-items-end">
    <div class="col-auto">
        <label class="form-label small mb-0">Année scolaire en cours</label>
        <input type="text" name="annee_scolaire" class="form-control form-control-sm" value="{{ $anneeScolaire }}" placeholder="2026-2027">
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-outline-secondary">Aperçu</button>
    </div>
</form>

<div class="alert alert-info">
    Aperçu du passage de <strong>{{ $anneeScolaire }}</strong> vers <strong>{{ $anneeSuivante }}</strong>.
    Les étudiants admis passeront dans leur classe supérieure définie, les redoublants resteront dans
    la même classe. Le jardin passe automatiquement (pas de bulletin). Rien n'est modifié tant que vous
    n'avez pas cliqué sur "Valider le passage" ci-dessous.
</div>

@foreach ($apercu as $bloc)
    <div class="card p-3 mb-3">
        <h6>
            {{ $bloc['classe']->nom_classe }}
            <span class="badge bg-secondary">{{ \App\Models\Classe::CYCLES[$bloc['classe']->cycle] ?? $bloc['classe']->cycle }}</span>
            @if ($bloc['classe']->classeSuivante)
                <span class="text-muted small">→ {{ $bloc['classe']->classeSuivante->nom_classe }}</span>
            @else
                <span class="text-muted small">(classe terminale — pas de classe supérieure définie)</span>
            @endif
        </h6>
        <table class="table table-sm align-middle mb-0">
            <thead><tr><th>Étudiant</th><th>Moyenne annuelle</th><th>Décision</th><th>Motif</th></tr></thead>
            <tbody>
            @forelse ($bloc['lignes'] as $ligne)
                <tr>
                    <td>{{ $ligne['etudiant']->prenom_etudiant }} {{ $ligne['etudiant']->nom_etudiant }}</td>
                    <td>{{ $ligne['moyenne'] ?? '-' }}</td>
                    <td>
                        @if ($ligne['decision'] === 'admis')
                            <span class="badge bg-success">Admis</span>
                        @else
                            <span class="badge bg-danger">Redouble</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $ligne['motif'] }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted">Aucun étudiant dans cette classe pour {{ $anneeScolaire }}.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endforeach

<form method="POST" action="{{ route('promotion.executer') }}" onsubmit="return confirm('Confirmer le passage en classe supérieure ? Cette action mettra à jour la classe et l\'année scolaire de tous les étudiants listés ci-dessus.');">
    @csrf
    <input type="hidden" name="annee_scolaire" value="{{ $anneeScolaire }}">
    <button class="btn btn-primary btn-lg"><i class="bi bi-arrow-up-circle"></i> Valider le passage vers {{ $anneeSuivante }}</button>
</form>
@endsection
