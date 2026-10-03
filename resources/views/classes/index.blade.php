@extends('layouts.app')
@section('title', 'Classes')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Classes</h3>
    <a href="{{ route('classes.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nouvelle classe</a>
</div>
<div class="card p-3">
    <table class="table table-hover align-middle">
        <thead><tr><th>#</th><th>Nom</th><th>Effectif</th><th>Frais scolaire</th><th>Étudiants inscrits</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @forelse ($classes as $classe)
            <tr>
                <td>{{ $classe->id }}</td>
                <td><a href="{{ route('classes.show', $classe) }}">{{ $classe->nom_classe }}</a></td>
                <td>{{ $classe->effectif_classe }}</td>
                <td>{{ number_format($classe->frais_scolaire, 2) }}</td>
                <td>{{ $classe->etudiants_count }}</td>
                <td class="text-end">
                    <a href="{{ route('classes.show', $classe) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('classes.edit', $classe) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('classes.destroy', $classe) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette classe ?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted">Aucune classe.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $classes->links() }}
</div>
@endsection
