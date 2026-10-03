@extends('layouts.app')
@section('title', 'Consulter les soldes')
@section('content')
<h3 class="mb-3">Consulter les soldes des étudiants</h3>
<p class="text-muted">Recherchez un étudiant pour connaître son reste à payer, même s'il n'est pas venu effectuer de paiement.</p>

<form method="GET" class="row g-2 mb-3 align-items-end">
    <div class="col-auto">
        <label class="form-label small mb-0">Nom ou prénom</label>
        <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}">
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
        <label class="form-label small mb-0">Année scolaire</label>
        <input type="text" name="annee_scolaire" class="form-control form-control-sm" value="{{ $anneeScolaire }}">
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
            <th>Frais scolaire</th>
            <th>Déjà payé</th>
            <th>Reste à payer</th>
            <th class="text-end">Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($soldes as $s)
            <tr>
                <td>{{ $s['etudiant']->classe->nom_classe ?? '-' }}</td>
                <td>{{ $s['etudiant']->nom_etudiant }}</td>
                <td>{{ $s['etudiant']->prenom_etudiant }}</td>
                <td>{{ number_format($s['frais'], 2) }}</td>
                <td>{{ number_format($s['paye'], 2) }}</td>
                <td>
                    <span class="badge {{ $s['reste'] <= 0 ? 'bg-success' : 'bg-warning text-dark' }}">{{ number_format($s['reste'], 2) }}</span>
                </td>
                <td class="text-end">
                    <a href="{{ route('paiements.create', $s['etudiant']) }}" class="btn btn-sm btn-outline-primary">Payer</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted">Aucun étudiant.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $etudiants->links() }}
</div>
@endsection
