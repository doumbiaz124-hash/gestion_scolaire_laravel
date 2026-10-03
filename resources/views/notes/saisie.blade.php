@extends('layouts.app')
@section('title', 'Saisie des notes')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Saisie des notes — {{ $etudiant->prenom_etudiant }} {{ $etudiant->nom_etudiant }}</h3>
    <a href="{{ route('notes.choisir-etudiant') }}" class="btn btn-light">Retour</a>
</div>
<p class="text-muted">Classe : {{ $etudiant->classe->nom_classe ?? '-' }} — Année scolaire : {{ $anneeScolaire }}</p>

@if ($matieres->isEmpty())
    <div class="alert alert-warning">
        Aucune matière n'est configurée pour la classe {{ $etudiant->classe->nom_classe ?? '' }}.
        <a href="{{ route('matieres.show', $etudiant->classe) }}">Configurez d'abord les matières et coefficients de cette classe</a>.
    </div>
@else
<div class="row g-3">
    <div class="col-md-5">
        <div class="card p-4">
            <h6>Saisir une note</h6>
            <form method="POST" action="{{ route('notes.store') }}">
                @csrf
                <input type="hidden" name="etudiant_id" value="{{ $etudiant->id }}">
                <input type="hidden" name="annee_scolaire" value="{{ $anneeScolaire }}">
                <div class="mb-3">
                    <label class="form-label">Matière</label>
                    <select name="matiere_id" class="form-select" required>
                        <option value="">-- Choisir la matière --</option>
                        @foreach ($matieres as $matiere)
                            <option value="{{ $matiere->id }}">{{ $matiere->nom_matiere }} (coef. {{ $matiere->coefficient }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Trimestre</label>
                    <select name="trimestre" class="form-select" required>
                        <option value="1">1er trimestre</option>
                        <option value="2">2e trimestre</option>
                        <option value="3">3e trimestre</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Note de devoir</label>
                        <input type="number" step="0.01" min="0" max="20" name="note_devoir" class="form-control">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Note de composition</label>
                        <input type="number" step="0.01" min="0" max="20" name="note_composition" class="form-control">
                    </div>
                </div>
                <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Enregistrer la note</button>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card p-3">
            <h6>Notes déjà saisies</h6>
            <table class="table table-sm align-middle">
                <thead>
                <tr>
                    <th>Matière</th>
                    <th>Trim.</th>
                    <th>Devoir</th>
                    <th>Composition</th>
                    <th>Moyenne</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($matieres as $matiere)
                    @forelse (($notes[$matiere->id] ?? collect()) as $note)
                        <tr>
                            <td>{{ $matiere->nom_matiere }}</td>
                            <td>T{{ $note->trimestre }}</td>
                            <td>{{ $note->note_devoir ?? '-' }}</td>
                            <td>{{ $note->note_composition ?? '-' }}</td>
                            <td>{{ $note->moyenne() }}</td>
                            <td>
                                <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Supprimer cette note ?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                @empty
                @endforelse
                @if (collect($notes)->flatten()->isEmpty())
                    <tr><td colspan="6" class="text-center text-muted">Aucune note saisie pour le moment.</td></tr>
                @endif
                </tbody>
            </table>
            <a href="{{ route('bulletins.etudiant', $etudiant) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-journal-text"></i> Voir le bulletin</a>
        </div>
    </div>
</div>
@endif
@endsection
