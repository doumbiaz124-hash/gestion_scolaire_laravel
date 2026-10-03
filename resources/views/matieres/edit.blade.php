@extends('layouts.app')
@section('title', 'Modifier la matière')
@section('content')
<h3 class="mb-3">Modifier la matière — {{ $classe->nom_classe }}</h3>
<div class="card p-4" style="max-width: 500px;">
    <form method="POST" action="{{ route('matieres.update', [$classe, $matiere]) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nom de la matière</label>
            <input type="text" name="nom_matiere" class="form-control" value="{{ old('nom_matiere', $matiere->nom_matiere) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Coefficient</label>
            <input type="number" step="0.01" min="0.1" name="coefficient" class="form-control" value="{{ old('coefficient', $matiere->coefficient) }}" required>
        </div>
        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('matieres.show', $classe) }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
