@extends('layouts.app')
@section('title', 'Nouvel enseignant')
@section('content')
<h3 class="mb-3">Nouvel enseignant</h3>
<div class="card p-4" style="max-width: 650px;">
    <form method="POST" action="{{ route('enseignants.store') }}">
        @csrf
        @include('enseignants._form')
        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('enseignants.index') }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
