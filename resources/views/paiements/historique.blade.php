@extends('layouts.app')
@section('title', 'Historique des paiements')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Historique des paiements — {{ $etudiant->prenom_etudiant }} {{ $etudiant->nom_etudiant }}</h3>
    <a href="{{ route('etudiants.show', $etudiant) }}" class="btn btn-light">Retour</a>
</div>

<div class="card p-3">
    <table class="table table-hover align-middle">
        <thead>
        <tr>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'date', 'dir' => $sort == 'date' && $dir == 'asc' ? 'desc' : 'asc']) }}">Date @if($sort=='date') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'annee', 'dir' => $sort == 'annee' && $dir == 'asc' ? 'desc' : 'asc']) }}">Année scolaire @if($sort=='annee') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th>Montant payé</th>
            <th>Reste</th>
            <th class="text-end">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($paiements as $p)
            <tr>
                <td>{{ $p->date_paiement }}</td>
                <td>{{ $p->annee_scolaire }}</td>
                <td>{{ number_format($p->montant_paye, 2) }}</td>
                <td>{{ number_format($p->reste_paiement, 2) }}</td>
                <td class="text-end">
                    <a href="{{ route('paiements.recu', $p) }}" class="btn btn-sm btn-outline-primary" target="_blank"><i class="bi bi-receipt"></i> Reçu</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted">Aucun paiement.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $paiements->links() }}
</div>
@endsection
