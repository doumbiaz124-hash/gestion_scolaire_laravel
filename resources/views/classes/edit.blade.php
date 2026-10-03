@extends('layouts.app')
@section('title', 'Modifier la classe')
@section('content')
<h3 class="mb-3">Modifier la classe</h3>
<div class="card p-4" style="max-width: 500px;">
    <form method="POST" action="{{ route('classes.update', $classe) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nom de la classe</label>
            <input type="text" name="nom_classe" class="form-control" value="{{ old('nom_classe', $classe->nom_classe) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Effectif</label>
            <input type="number" name="effectif_classe" class="form-control" value="{{ old('effectif_classe', $classe->effectif_classe) }}" min="0">
        </div>
        <div class="mb-3">
            <label class="form-label">Frais scolaire de la classe</label>
            <input type="number" step="0.01" name="frais_scolaire" class="form-control" value="{{ old('frais_scolaire', $classe->frais_scolaire) }}" min="0">
            <small class="text-muted">Montant total dû par chaque étudiant de cette classe pour l'année.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Cycle</label>
            <select name="cycle" class="form-select" required>
                @foreach (\App\Models\Classe::CYCLES as $key => $label)
                    <option value="{{ $key }}" {{ old('cycle', $classe->cycle) == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <small class="text-muted">Détermine la moyenne de passage utilisée lors du passage en classe supérieure.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Classe supérieure (passage en fin d'année)</label>
            <select name="classe_suivante_id" class="form-select">
                <option value="">-- Aucune (classe terminale) --</option>
                @foreach ($classes as $c)
                    <option value="{{ $c->id }}" {{ old('classe_suivante_id', $classe->classe_suivante_id) == $c->id ? 'selected' : '' }}>{{ $c->nom_classe }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('classes.index') }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
