@extends('layouts.app')
@section('title', 'Modifier le paiement')
@section('content')
<h3 class="mb-3">Modifier le paiement — {{ $paiement->etudiant->prenom_etudiant }} {{ $paiement->etudiant->nom_etudiant }}</h3>
<div class="card p-4" style="max-width: 550px;">
    <p class="text-muted">Classe : {{ $paiement->etudiant->classe->nom_classe ?? '-' }} — Année : {{ $paiement->annee_scolaire }}</p>
    <form method="POST" action="{{ route('paiements.update', $paiement) }}">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Date de paiement</label>
                <input type="date" name="date_paiement" class="form-control" value="{{ old('date_paiement', $paiement->date_paiement) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Heure de paiement</label>
                <input type="time" name="heure_paiement" class="form-control" value="{{ old('heure_paiement', $paiement->heure_paiement) }}">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Montant payé</label>
            <input type="number" step="0.01" min="0.01" name="montant_paye" class="form-control" value="{{ old('montant_paye', $paiement->montant_paye) }}" required>
        </div>
        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('paiements.index') }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
