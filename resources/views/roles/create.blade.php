@extends('layouts.app')
@section('title', 'Nouveau rôle')
@section('content')
<h3 class="mb-3">Nouveau rôle</h3>
<div class="card p-4" style="max-width: 500px;">
    <form method="POST" action="{{ route('roles.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nom du rôle</label>
            <input type="text" name="nom_role" class="form-control" value="{{ old('nom_role') }}" required>
        </div>
        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('roles.index') }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
