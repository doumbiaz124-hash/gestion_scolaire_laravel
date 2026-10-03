@extends('layouts.app')
@section('title', 'Nouvelle série')
@section('content')
<h3 class="mb-3">Nouvelle série</h3>
<div class="card p-4" style="max-width: 500px;">
    <form method="POST" action="{{ route('series.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nom de la série</label>
            <input type="text" name="nom_serie" class="form-control" value="{{ old('nom_serie') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Classe</label>
            <select name="classe_id" class="form-select" required>
                <option value="">-- Choisir --</option>
                @foreach ($classes as $classe)
                    <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>{{ $classe->nom_classe }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('series.index') }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
