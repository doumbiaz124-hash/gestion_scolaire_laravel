@extends('layouts.app')
@section('title', 'Séries')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Séries</h3>
    <a href="{{ route('series.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nouvelle série</a>
</div>
<div class="card p-3">
    <table class="table table-hover align-middle">
        <thead><tr><th>#</th><th>Nom</th><th>Classe</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @forelse ($series as $serie)
            <tr>
                <td>{{ $serie->id }}</td>
                <td>{{ $serie->nom_serie }}</td>
                <td>{{ $serie->classe->nom_classe ?? '-' }}</td>
                <td class="text-end">
                    <a href="{{ route('series.edit', $serie) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('series.destroy', $serie) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette série ?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">Aucune série.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $series->links() }}
</div>
@endsection
