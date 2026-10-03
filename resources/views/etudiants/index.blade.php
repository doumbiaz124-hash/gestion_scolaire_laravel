@extends('layouts.app')
@section('title', 'Étudiants')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Étudiants</h3>
    <a href="{{ route('etudiants.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nouvel étudiant</a>
</div>

<form method="GET" class="row g-2 mb-3 align-items-end">
    <div class="col-auto">
        <label class="form-label small mb-0">Recherche</label>
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Nom ou prénom..." value="{{ request('search') }}">
    </div>
    <div class="col-auto">
        <label class="form-label small mb-0">Classe</label>
        <select name="classe_id" class="form-select form-select-sm">
            <option value="">Toutes</option>
            @foreach ($classes as $classe)
                <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>{{ $classe->nom_classe }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <label class="form-label small mb-0">Série</label>
        <select name="serie_id" class="form-select form-select-sm">
            <option value="">Toutes</option>
            @foreach ($series as $serie)
                <option value="{{ $serie->id }}" {{ request('serie_id') == $serie->id ? 'selected' : '' }}>{{ $serie->nom_serie }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i> Filtrer</button>
    </div>
    @if (request('classe_id'))
    <div class="col-auto ms-auto">
        <a href="{{ route('etudiants.liste-classe', request('classe_id')) }}" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="bi bi-printer"></i> Imprimer la liste de cette classe</a>
        <a href="{{ route('etudiants.export-classe', request('classe_id')) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i> Enregistrer (CSV)</a>
    </div>
    @endif
</form>

<div class="card p-3">
    <table class="table table-hover align-middle">
        <thead>
        <tr>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'nom', 'dir' => $sort == 'nom' && $dir == 'asc' ? 'desc' : 'asc']) }}">Nom @if($sort=='nom') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'prenom', 'dir' => $sort == 'prenom' && $dir == 'asc' ? 'desc' : 'asc']) }}">Prénom @if($sort=='prenom') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'classe', 'dir' => $sort == 'classe' && $dir == 'asc' ? 'desc' : 'asc']) }}">Classe @if($sort=='classe') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'serie', 'dir' => $sort == 'serie' && $dir == 'asc' ? 'desc' : 'asc']) }}">Série @if($sort=='serie') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th>Sexe</th>
            <th class="text-end">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($etudiants as $etudiant)
            <tr>
                <td>{{ $etudiant->nom_etudiant }}</td>
                <td>{{ $etudiant->prenom_etudiant }}</td>
                <td>{{ $etudiant->classe->nom_classe ?? '-' }}</td>
                <td>{{ $etudiant->serie->nom_serie ?? '-' }}</td>
                <td>{{ $etudiant->sexe_etudiant }}</td>
                <td class="text-end">
                    <a href="{{ route('etudiants.show', $etudiant) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('etudiants.edit', $etudiant) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('etudiants.destroy', $etudiant) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet étudiant ?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted">Aucun étudiant.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $etudiants->links() }}
</div>
@endsection
