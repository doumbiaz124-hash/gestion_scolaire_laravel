@extends('layouts.app')
@section('title', 'Nouveau paiement — choisir l’étudiant')
@section('content')
<h3 class="mb-3">Nouveau paiement — Choisir l'étudiant</h3>

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
        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i> Filtrer</button>
    </div>
</form>

<div class="card p-3">
    <table class="table table-hover align-middle">
        <thead>
        <tr>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'classe', 'dir' => $sort == 'classe' && $dir == 'asc' ? 'desc' : 'asc']) }}">Classe @if($sort=='classe') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'nom', 'dir' => $sort == 'nom' && $dir == 'asc' ? 'desc' : 'asc']) }}">Nom @if($sort=='nom') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'prenom', 'dir' => $sort == 'prenom' && $dir == 'asc' ? 'desc' : 'asc']) }}">Prénom @if($sort=='prenom') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="text-end">Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($etudiants as $etudiant)
            <tr>
                <td>{{ $etudiant->classe->nom_classe ?? '-' }}</td>
                <td>{{ $etudiant->nom_etudiant }}</td>
                <td>{{ $etudiant->prenom_etudiant }}</td>
                <td class="text-end">
                    <a href="{{ route('paiements.create', $etudiant) }}" class="btn btn-sm btn-primary"><i class="bi bi-cash"></i> Payer</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">Aucun étudiant.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $etudiants->links() }}
</div>
@endsection
