@extends('layouts.app')
@section('title', 'Ajouter un créneau')
@section('content')
<h3 class="mb-3">Ajouter un créneau — {{ $classe->nom_classe }}</h3>
<div class="card p-4" style="max-width: 550px;">
    <form method="POST" action="{{ route('emploi-du-temps.store', $classe) }}">
        @csrf
        @include('emploi_du_temps._form')
        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('emploi-du-temps.show', $classe) }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
