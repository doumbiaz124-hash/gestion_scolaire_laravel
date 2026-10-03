@extends('layouts.app')
@section('title', 'Bulletins - ' . $classe->nom_classe)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h3>Bulletins — {{ $classe->nom_classe }}</h3>
    <div>
        <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Imprimer tout</button>
        <a href="{{ route('bulletins.choisir-etudiant') }}" class="btn btn-light">Retour</a>
    </div>
</div>

<form method="GET" class="row g-2 mb-3 no-print align-items-end">
    <div class="col-auto">
        <label class="form-label small mb-0">Trimestre</label>
        <select name="trimestre" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="1" {{ $trimestre == 1 ? 'selected' : '' }}>1er trimestre</option>
            <option value="2" {{ $trimestre == 2 ? 'selected' : '' }}>2e trimestre</option>
            <option value="3" {{ $trimestre == 3 ? 'selected' : '' }}>3e trimestre</option>
        </select>
    </div>
    <div class="col-auto">
        <label class="form-label small mb-0">Année scolaire</label>
        <input type="text" name="annee_scolaire" class="form-control form-control-sm" value="{{ $anneeScolaire }}">
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-outline-secondary">Afficher</button>
    </div>
</form>

@forelse ($bulletins as $bulletin)
    <div class="card p-5 mb-4" style="max-width: 800px; margin: 0 auto; page-break-after: always;">
        <div class="text-center mb-4">
            <h4><i class="bi bi-mortarboard-fill"></i> Gestion Scolaire</h4>
            <p class="text-muted mb-0">Bulletin de notes — {{ $trimestre == 1 ? '1er' : $trimestre . 'e' }} trimestre</p>
            <p class="text-muted small">Année scolaire {{ $anneeScolaire }}</p>
        </div>

        <table class="table table-borderless mb-4">
            <tr>
                <th style="width: 30%;">Étudiant</th>
                <td>{{ $bulletin['etudiant']->prenom_etudiant }} {{ $bulletin['etudiant']->nom_etudiant }}</td>
                <th style="width: 20%;">Classe</th>
                <td>{{ $classe->nom_classe }}</td>
            </tr>
        </table>

        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Matière</th>
                <th>Coef.</th>
                <th>Devoir</th>
                <th>Composition</th>
                <th>Moyenne</th>
                <th>Points</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($bulletin['lignes'] as $ligne)
                <tr>
                    <td>{{ $ligne['matiere']->nom_matiere }}</td>
                    <td>{{ $ligne['coefficient'] }}</td>
                    <td>{{ $ligne['devoir'] ?? '-' }}</td>
                    <td>{{ $ligne['composition'] ?? '-' }}</td>
                    <td>{{ $ligne['moyenne'] ?? '-' }}</td>
                    <td>{{ $ligne['points'] }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Aucune matière configurée.</td></tr>
            @endforelse
            </tbody>
            <tfoot>
            <tr class="table-light fw-bold">
                <td>Total</td>
                <td>{{ $bulletin['total_coefficients'] }}</td>
                <td colspan="2"></td>
                <td>Moyenne générale</td>
                <td>{{ $bulletin['moyenne_generale'] ?? '-' }} / 20</td>
            </tr>
            </tfoot>
        </table>

        @if ($trimestre == 3)
            <div class="alert alert-info text-center fw-bold fs-5">
                Moyenne générale annuelle : {{ $bulletin['moyenne_annuelle'] ?? '-' }} / 20
            </div>
        @endif

        <div class="row mt-5 pt-4">
            <div class="col-6"></div>
            <div class="col-6 text-center">
                <p class="mb-5">Le Directeur</p>
                <p class="mb-0 border-top pt-2">Signature</p>
            </div>
        </div>
    </div>
@empty
    <p class="text-center text-muted">Aucun étudiant dans cette classe.</p>
@endforelse
@endsection
