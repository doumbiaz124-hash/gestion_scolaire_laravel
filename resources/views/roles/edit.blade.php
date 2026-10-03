@extends('layouts.app')
@section('title', 'Modifier le rôle')
@section('content')
<h3 class="mb-3">Modifier le rôle</h3>
<div class="card p-4" style="max-width: 500px;">
    <form method="POST" action="{{ route('roles.update', $role) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nom du rôle</label>
            <input type="text" name="nom_role" class="form-control" value="{{ old('nom_role', $role->nom_role) }}" required>
        </div>
        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('roles.index') }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
