@extends('layouts.app')
@section('title', 'Paiements')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Paiements</h3>
    <div>
        <a href="{{ route('paiements.soldes') }}" class="btn btn-outline-secondary"><i class="bi bi-search"></i> Consulter les soldes</a>
        <a href="{{ route('paiements.choisir-etudiant') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nouveau paiement</a>
    </div>
</div>

<form method="GET" class="row g-2 mb-3 align-items-end">
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
        <label class="form-label small mb-0">Année scolaire</label>
        <select name="annee_scolaire" class="form-select form-select-sm">
            <option value="">Toutes</option>
            @foreach ($annees as $annee)
                <option value="{{ $annee }}" {{ request('annee_scolaire') == $annee ? 'selected' : '' }}>{{ $annee }}</option>
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
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'nom', 'dir' => $sort == 'nom' && $dir == 'asc' ? 'desc' : 'asc']) }}">Nom @if($sort=='nom') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'prenom', 'dir' => $sort == 'prenom' && $dir == 'asc' ? 'desc' : 'asc']) }}">Prénom @if($sort=='prenom') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'classe', 'dir' => $sort == 'classe' && $dir == 'asc' ? 'desc' : 'asc']) }}">Classe @if($sort=='classe') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'date', 'dir' => $sort == 'date' && $dir == 'asc' ? 'desc' : 'asc']) }}">Date @if($sort=='date') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'annee', 'dir' => $sort == 'annee' && $dir == 'asc' ? 'desc' : 'asc']) }}">Année @if($sort=='annee') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th>Montant payé</th>
            <th>Reste</th>
            <th class="text-end">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($paiements as $p)
            <tr>
                <td>{{ $p->etudiant->nom_etudiant ?? '' }}</td>
                <td>{{ $p->etudiant->prenom_etudiant ?? '' }}</td>
                <td>{{ $p->etudiant->classe->nom_classe ?? '-' }}</td>
                <td>{{ $p->date_paiement }}</td>
                <td>{{ $p->annee_scolaire }}</td>
                <td>{{ number_format($p->montant_paye, 2) }}</td>
                <td>{{ number_format($p->reste_paiement, 2) }}</td>
                <td class="text-end">
                    <a href="{{ route('paiements.recu', $p) }}" class="btn btn-sm btn-outline-primary" target="_blank"><i class="bi bi-receipt"></i></a>
                    <a href="{{ route('paiements.edit', $p) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('paiements.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce paiement ?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-muted">Aucun paiement.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $paiements->links() }}
</div>
@endsection
