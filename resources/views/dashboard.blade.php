@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('content')
<h3 class="mb-4">Tableau de bord</h3>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-3">
            <div class="text-muted small">Étudiants</div>
            <div class="fs-3 fw-bold">{{ $stats['etudiants'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="text-muted small">Enseignants</div>
            <div class="fs-3 fw-bold">{{ $stats['enseignants'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="text-muted small">Classes</div>
            <div class="fs-3 fw-bold">{{ $stats['classes'] }}</div>
        </div>
    </div>
</div>

@if ($etudiants !== null)
<div class="card p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0"><i class="bi bi-people"></i> Étudiants</h5>
        <a href="{{ route('etudiants.index') }}" class="btn btn-sm btn-outline-primary">Voir tous</a>
    </div>
    <form method="GET" action="{{ route('dashboard') }}" class="row g-2 mb-2">
        <div class="col-auto">
            <select name="sort_etudiants" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="nom" {{ request('sort_etudiants', 'nom') == 'nom' ? 'selected' : '' }}>Trier par nom</option>
                <option value="classe" {{ request('sort_etudiants') == 'classe' ? 'selected' : '' }}>Trier par classe</option>
                <option value="serie" {{ request('sort_etudiants') == 'serie' ? 'selected' : '' }}>Trier par série</option>
            </select>
        </div>
    </form>
    <table class="table table-sm table-hover align-middle mb-0">
        <thead><tr><th>Nom</th><th>Prénom</th><th>Classe</th><th>Série</th></tr></thead>
        <tbody>
        @forelse ($etudiants as $etudiant)
            <tr>
                <td>{{ $etudiant->nom_etudiant }}</td>
                <td>{{ $etudiant->prenom_etudiant }}</td>
                <td>{{ $etudiant->classe->nom_classe ?? '-' }}</td>
                <td>{{ $etudiant->serie->nom_serie ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">Aucun étudiant.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endif

@if ($enseignants !== null)
<div class="card p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0"><i class="bi bi-person-badge"></i> Enseignants</h5>
        <div>
            <a href="{{ route('enseignants.imprimer') }}" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="bi bi-printer"></i> Imprimer</a>
            <a href="{{ route('enseignants.index') }}" class="btn btn-sm btn-outline-primary">Voir tous</a>
        </div>
    </div>
    <form method="GET" action="{{ route('dashboard') }}" class="row g-2 mb-2">
        <div class="col-auto">
            <select name="sort_enseignants" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="nom" {{ request('sort_enseignants', 'nom') == 'nom' ? 'selected' : '' }}>Trier par nom</option>
                <option value="matiere" {{ request('sort_enseignants') == 'matiere' ? 'selected' : '' }}>Trier par matière</option>
            </select>
        </div>
    </form>
    <table class="table table-sm table-hover align-middle mb-0">
        <thead><tr><th>Nom</th><th>Prénom</th><th>Matière</th></tr></thead>
        <tbody>
        @forelse ($enseignants as $enseignant)
            <tr>
                <td>{{ $enseignant->nom_enseignant }}</td>
                <td>{{ $enseignant->prenom_enseignant }}</td>
                <td>{{ $enseignant->matiere_enseignee }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="text-center text-muted">Aucun enseignant.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endif
@endsection
