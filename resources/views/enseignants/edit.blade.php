@extends('layouts.app')
@section('title', 'Modifier l’enseignant')
@section('content')
<h3 class="mb-3">Modifier l’enseignant</h3>
<div class="card p-4" style="max-width: 650px;">
    <form method="POST" action="{{ route('enseignants.update', $enseignant) }}">
        @csrf @method('PUT')
        @include('enseignants._form')
        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('enseignants.index') }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
