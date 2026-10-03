@extends('layouts.app')
@section('title', 'Liste - ' . $classe->nom_classe)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h3>Liste des étudiants — {{ $classe->nom_classe }}</h3>
    <div>
        <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Imprimer</button>
        <a href="{{ route('etudiants.export-classe', $classe) }}" class="btn btn-outline-secondary"><i class="bi bi-download"></i> Enregistrer (CSV)</a>
        <a href="{{ route('etudiants.index') }}" class="btn btn-light">Retour</a>
    </div>
</div>

<div class="card p-4">
    <div class="text-center mb-4">
        <h4>Liste des étudiants de la classe {{ $classe->nom_classe }}</h4>
        <p class="text-muted">Ordre alphabétique — {{ $etudiants->count() }} étudiant(s)</p>
    </div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Sexe</th>
            <th>Classe</th>
            <th>Date de naissance</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($etudiants as $i => $etudiant)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $etudiant->nom_etudiant }}</td>
                <td>{{ $etudiant->prenom_etudiant }}</td>
                <td>{{ $etudiant->sexe_etudiant }}</td>
                <td>{{ $classe->nom_classe }}</td>
                <td>{{ $etudiant->date_naissance }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted">Aucun étudiant dans cette classe.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
