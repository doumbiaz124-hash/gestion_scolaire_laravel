@extends('layouts.app')
@section('title', 'Reçu de paiement')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h3>Reçu de paiement</h3>
    <div>
        <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Imprimer</button>
        <a href="{{ route('paiements.index') }}" class="btn btn-light">Retour</a>
    </div>
</div>

<div class="card p-5" style="max-width: 700px; margin: 0 auto;">
    <div class="text-center mb-4">
        <h4><i class="bi bi-mortarboard-fill"></i> Gestion Scolaire</h4>
        <p class="text-muted mb-0">Reçu de paiement</p>
        <p class="text-muted small">N° {{ str_pad($paiement->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

    <table class="table table-borderless">
        <tr>
            <th style="width: 40%;">Étudiant</th>
            <td>{{ $paiement->etudiant->prenom_etudiant }} {{ $paiement->etudiant->nom_etudiant }}</td>
        </tr>
        <tr>
            <th>Classe</th>
            <td>{{ $paiement->etudiant->classe->nom_classe ?? '-' }}</td>
        </tr>
        <tr>
            <th>Année scolaire</th>
            <td>{{ $paiement->annee_scolaire }}</td>
        </tr>
        <tr>
            <th>Date de paiement</th>
            <td>{{ $paiement->date_paiement }} {{ $paiement->heure_paiement }}</td>
        </tr>
        <tr>
            <th>Montant payé</th>
            <td class="fs-5 fw-bold">{{ number_format($paiement->montant_paye, 2) }}</td>
        </tr>
        <tr>
            <th>Reste à payer</th>
            <td>{{ number_format($paiement->reste_paiement, 2) }}</td>
        </tr>
    </table>

    <div class="row mt-5 pt-4">
        <div class="col-6"></div>
        <div class="col-6 text-center">
            <p class="mb-5">Signature</p>
            <p class="mb-0 border-top pt-2">
                {{ $paiement->utilisateur->email ?? 'Utilisateur' }}
                @if ($paiement->utilisateur && $paiement->utilisateur->role)
                    <br><span class="text-muted small">{{ $paiement->utilisateur->role->nom_role }}</span>
                @endif
            </p>
        </div>
    </div>
</div>
@endsection
