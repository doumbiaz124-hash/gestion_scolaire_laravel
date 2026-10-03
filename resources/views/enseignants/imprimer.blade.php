@extends('layouts.app')
@section('title', 'Liste des enseignants')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h3>Liste des enseignants</h3>
    <div>
        <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Imprimer</button>
        <a href="{{ route('enseignants.index') }}" class="btn btn-light">Retour</a>
    </div>
</div>

<div class="card p-4">
    <div class="text-center mb-4">
        <h4>Liste des enseignants</h4>
        <p class="text-muted">{{ $enseignants->count() }} enseignant(s)</p>
    </div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Matière enseignée</th>
            <th>Téléphone</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($enseignants as $i => $enseignant)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $enseignant->nom_enseignant }}</td>
                <td>{{ $enseignant->prenom_enseignant }}</td>
                <td>{{ $enseignant->matiere_enseignee }}</td>
                <td>{{ $enseignant->tel_enseignant }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted">Aucun enseignant.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
