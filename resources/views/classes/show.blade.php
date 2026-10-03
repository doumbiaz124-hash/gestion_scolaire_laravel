@extends('layouts.app')
@section('title', $classe->nom_classe)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Classe : {{ $classe->nom_classe }}</h3>
        <span class="text-muted small">Frais scolaire : {{ number_format($classe->frais_scolaire, 2) }} — Effectif prévu : {{ $classe->effectif_classe }}</span>
    </div>
    <div>
        @if (auth()->user()->hasModule('etudiants'))
        <a href="{{ route('etudiants.liste-classe', $classe) }}" class="btn btn-outline-secondary" target="_blank"><i class="bi bi-printer"></i> Imprimer la liste</a>
        <a href="{{ route('etudiants.export-classe', $classe) }}" class="btn btn-outline-secondary"><i class="bi bi-download"></i> CSV</a>
        @endif
        @if (auth()->user()->hasModule('emploi_du_temps'))
        <a href="{{ route('emploi-du-temps.show', $classe) }}" class="btn btn-outline-secondary"><i class="bi bi-calendar3"></i> Emploi du temps</a>
        @endif
        <a href="{{ route('classes.index') }}" class="btn btn-light">Retour</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card p-3">
            <h6>Séries</h6>
            <ul class="list-group list-group-flush">
                @forelse ($classe->series as $serie)
                    <li class="list-group-item">{{ $serie->nom_serie }}</li>
                @empty
                    <li class="list-group-item text-muted">Aucune série.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h6>Enseignants</h6>
            <ul class="list-group list-group-flush">
                @forelse ($classe->enseignants as $enseignant)
                    <li class="list-group-item">{{ $enseignant->prenom_enseignant }} {{ $enseignant->nom_enseignant }}</li>
                @empty
                    <li class="list-group-item text-muted">Aucun enseignant.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h6>Étudiants ({{ $classe->etudiants->count() }})</h6>
            <ul class="list-group list-group-flush">
                @forelse ($classe->etudiants as $etudiant)
                    <li class="list-group-item">{{ $etudiant->prenom_etudiant }} {{ $etudiant->nom_etudiant }}</li>
                @empty
                    <li class="list-group-item text-muted">Aucun étudiant.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
