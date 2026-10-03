@extends('layouts.app')
@section('title', 'Matières - Configuration des bulletins')
@section('content')
<h3 class="mb-3">Matières et coefficients — Choisir une classe</h3>
<p class="text-muted">Avant de saisir des notes, configurez pour chaque classe la liste des matières et leur coefficient.</p>
<div class="card p-3">
    <table class="table table-hover align-middle">
        <thead><tr><th>Classe</th><th>Matières configurées</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @forelse ($classes as $classe)
            <tr>
                <td>{{ $classe->nom_classe }}</td>
                <td>{{ $classe->matieres_count }}</td>
                <td class="text-end">
                    <a href="{{ route('matieres.show', $classe) }}" class="btn btn-sm btn-primary"><i class="bi bi-gear"></i> Configurer</a>
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
