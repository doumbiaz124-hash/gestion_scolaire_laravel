@extends('layouts.app')
@section('title', 'Nouveau paiement')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Nouveau paiement — {{ $etudiant->prenom_etudiant }} {{ $etudiant->nom_etudiant }}</h3>
    <a href="{{ route('paiements.choisir-etudiant') }}" class="btn btn-light">Retour</a>
</div>

@if ($resteAnneePrecedente)
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle"></i> Cet étudiant a un reste à payer de <strong>{{ number_format($resteAnneePrecedente, 2) }}</strong>
    pour l'année scolaire précédente ({{ $anneePrecedente }}). Le paiement de l'année {{ $anneeScolaire }} peut tout de même être effectué.
</div>
@endif

<div class="row g-3">
    <div class="col-md-5">
        <div class="card p-3 mb-3">
            <h6>Informations</h6>
            <p class="mb-1"><strong>Classe :</strong> {{ $etudiant->classe->nom_classe ?? '-' }}</p>
            <p class="mb-1"><strong>Frais scolaire ({{ $anneeScolaire }}) :</strong> {{ number_format($frais, 2) }}</p>
            <p class="mb-1"><strong>Déjà payé :</strong> {{ number_format($frais - $reste, 2) }}</p>
            <p class="mb-0 fs-5"><strong>Reste à payer :</strong>
                <span class="badge {{ $soldeAtteint ? 'bg-success' : 'bg-warning text-dark' }}">{{ number_format($reste, 2) }}</span>
            </p>
        </div>

        @if ($resteAnneePrecedente)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            Cet étudiant a un reste à payer de <strong>{{ number_format($resteAnneePrecedente, 2) }}</strong>
            pour l'année scolaire précédente ({{ $anneePrecedente }}).
            Le paiement de l'année en cours ({{ $anneeScolaire }}) peut tout de même être effectué.
        </div>
        @endif

        <div class="card p-3">
            <h6>Paiements précédents ({{ $anneeScolaire }})</h6>
            <ul class="list-group list-group-flush">
                @forelse ($historique as $p)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $p->date_paiement }}</span>
                        <span>{{ number_format($p->montant_paye, 2) }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Aucun paiement précédent pour cette année.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card p-4">
            @if ($soldeAtteint)
                <div class="alert alert-success mb-0">
                    <i class="bi bi-check-circle"></i> Les frais scolaires de {{ $anneeScolaire }} sont entièrement payés pour cet étudiant.
                    Aucun nouveau paiement n'est possible avant l'année scolaire suivante.
                </div>
            @else
                <form method="POST" action="{{ route('paiements.store') }}">
                    @csrf
                    <input type="hidden" name="etudiant_id" value="{{ $etudiant->id }}">
                    <div class="mb-3">
                        <label class="form-label">Année scolaire</label>
                        <input type="text" name="annee_scolaire" class="form-control" value="{{ old('annee_scolaire', $anneeScolaire) }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de paiement</label>
                            <input type="date" name="date_paiement" class="form-control" value="{{ old('date_paiement', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Heure de paiement</label>
                            <input type="time" name="heure_paiement" class="form-control" value="{{ old('heure_paiement', date('H:i')) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant payé</label>
                        <input type="number" step="0.01" min="0.01" max="{{ $reste }}" name="montant_paye" class="form-control" value="{{ old('montant_paye') }}" required>
                        <small class="text-muted">Maximum : {{ number_format($reste, 2) }} (reste à payer)</small>
                    </div>
                    <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Enregistrer le paiement</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
