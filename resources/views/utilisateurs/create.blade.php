@extends('layouts.app')
@section('title', 'Nouvel utilisateur')
@section('content')
<h3 class="mb-3">Nouvel utilisateur</h3>
<div class="card p-4" style="max-width: 550px;">
    <form method="POST" action="{{ route('utilisateurs.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Rôle</label>
            <select name="role_id" class="form-select" required>
                <option value="">-- Choisir --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->nom_role }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Tableaux de bord accessibles</label>
            <div class="form-text mb-2">Précisez sur quels modules cet utilisateur peut travailler. (Un administrateur a automatiquement accès à tout.)</div>
            @foreach ($modules as $key => $label)
                <div class="form-check">
                    <input type="checkbox" name="permissions[]" value="{{ $key }}" class="form-check-input" id="perm_{{ $key }}"
                        {{ in_array($key, old('permissions', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="perm_{{ $key }}">{{ $label }}</label>
                </div>
            @endforeach
        </div>
        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('utilisateurs.index') }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
