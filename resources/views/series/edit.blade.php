@extends('layouts.app')
@section('title', 'Modifier la série')
@section('content')
<h3 class="mb-3">Modifier la série</h3>
<div class="card p-4" style="max-width: 500px;">
    <form method="POST" action="{{ route('series.update', $serie) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nom de la série</label>
            <input type="text" name="nom_serie" class="form-control" value="{{ old('nom_serie', $serie->nom_serie) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Classe</label>
            <select name="classe_id" class="form-select" required>
                @foreach ($classes as $classe)
                    <option value="{{ $classe->id }}" {{ old('classe_id', $serie->classe_id) == $classe->id ? 'selected' : '' }}>{{ $classe->nom_classe }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('series.index') }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
