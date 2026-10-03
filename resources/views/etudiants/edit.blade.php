@extends('layouts.app')
@section('title', 'Modifier l’étudiant')
@section('content')
<h3 class="mb-3">Modifier l’étudiant</h3>
<div class="card p-4" style="max-width: 650px;">
    <form method="POST" action="{{ route('etudiants.update', $etudiant) }}">
        @csrf @method('PUT')
        @include('etudiants._form')
        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('etudiants.index') }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
