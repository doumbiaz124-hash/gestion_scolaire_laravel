@extends('layouts.app')
@section('title', 'Modifier le créneau')
@section('content')
<h3 class="mb-3">Modifier le créneau — {{ $classe->nom_classe }}</h3>
<div class="card p-4" style="max-width: 550px;">
    <form method="POST" action="{{ route('emploi-du-temps.update', [$classe, $creneau]) }}">
        @csrf @method('PUT')
        @include('emploi_du_temps._form')
        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('emploi-du-temps.show', $classe) }}" class="btn btn-light">Annuler</a>
    </form>
</div>
@endsection
