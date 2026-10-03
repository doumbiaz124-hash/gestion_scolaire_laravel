@extends('layouts.app')
@section('title', 'Emploi du temps')
@section('content')
<h3 class="mb-3">Emploi du temps — Choisir une classe</h3>
<div class="card p-3">
    <table class="table table-hover align-middle">
        <thead><tr><th>Classe</th><th>Créneaux définis</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @forelse ($classes as $classe)
            <tr>
                <td>{{ $classe->nom_classe }}</td>
                <td>{{ $classe->emplois_du_temps_count }}</td>
                <td class="text-end">
                    <a href="{{ route('emploi-du-temps.show', $classe) }}" class="btn btn-sm btn-primary"><i class="bi bi-calendar3"></i> Voir / Gérer</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="text-center text-muted">Aucune classe.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $classes->links() }}
</div>
@endsection
