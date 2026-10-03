@extends('layouts.app')
@section('title', $etudiant->nomComplet())
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>{{ $etudiant->prenom_etudiant }} {{ $etudiant->nom_etudiant }}</h3>
    <a href="{{ route('etudiants.index') }}" class="btn btn-light">Retour</a>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card p-3">
            <h6>Informations</h6>
            <p class="mb-1"><strong>Date de naissance :</strong> {{ $etudiant->date_naissance }}</p>
            <p class="mb-1"><strong>Sexe :</strong> {{ $etudiant->sexe_etudiant }}</p>
            <p class="mb-1"><strong>Classe :</strong> {{ $etudiant->classe->nom_classe ?? '-' }}</p>
            <p class="mb-1"><strong>Série :</strong> {{ $etudiant->serie->nom_serie ?? '-' }}</p>
            <p class="mb-1"><strong>Tuteur :</strong> {{ $etudiant->tel_tuteur }}</p>
            <p class="mb-1"><strong>Adresse :</strong> {{ $etudiant->adresse }}</p>
            <p class="mb-0"><strong>Année scolaire :</strong> {{ $etudiant->annee_scolaire }}</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Paiements</h6>
                @if (auth()->user()->hasModule('paiements'))
                <div>
                    <a href="{{ route('paiements.historique', $etudiant) }}" class="btn btn-sm btn-outline-secondary">Historique complet</a>
                    <a href="{{ route('paiements.create', $etudiant) }}" class="btn btn-sm btn-primary"><i class="bi bi-cash"></i> Nouveau paiement</a>
                </div>
                @endif
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($etudiant->paiements as $p)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $p->date_paiement }} — {{ number_format($p->montant_paye, 2) }} ({{ $p->annee_scolaire }})</span>
                        <span class="text-muted small">Reste : {{ number_format($p->reste_paiement, 2) }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Aucun paiement.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
