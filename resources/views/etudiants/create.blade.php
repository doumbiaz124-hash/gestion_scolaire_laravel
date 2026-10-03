@extends('layouts.app')
@section('title', 'Nouvel étudiant')
@section('content')
<h3 class="mb-3">Nouvel étudiant</h3>
<div class="card p-4" style="max-width: 650px;">
    <form method="POST" action="{{ route('etudiants.store') }}">
        @csrf
        @include('etudiants._form')
        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('etudiants.index') }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
