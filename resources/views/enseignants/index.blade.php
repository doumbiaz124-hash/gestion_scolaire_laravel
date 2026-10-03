@extends('layouts.app')
@section('title', 'Enseignants')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Enseignants</h3>
    <div>
        <a href="{{ route('enseignants.imprimer') }}" class="btn btn-outline-secondary" target="_blank"><i class="bi bi-printer"></i> Imprimer la liste</a>
        <a href="{{ route('enseignants.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nouvel enseignant</a>
    </div>
</div>
<div class="card p-3">
    <table class="table table-hover align-middle">
        <thead>
        <tr>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'nom', 'dir' => $sort == 'nom' && $dir == 'asc' ? 'desc' : 'asc']) }}">Nom @if($sort=='nom') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'prenom', 'dir' => $sort == 'prenom' && $dir == 'asc' ? 'desc' : 'asc']) }}">Prénom @if($sort=='prenom') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="sortable-th"><a href="{{ request()->fullUrlWithQuery(['sort' => 'matiere', 'dir' => $sort == 'matiere' && $dir == 'asc' ? 'desc' : 'asc']) }}">Matière @if($sort=='matiere') <i class="bi bi-caret-{{ $dir=='asc'?'up':'down' }}-fill"></i> @endif</a></th>
            <th class="text-end">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($enseignants as $enseignant)
            <tr>
                <td>{{ $enseignant->nom_enseignant }}</td>
                <td>{{ $enseignant->prenom_enseignant }}</td>
                <td>{{ $enseignant->matiere_enseignee }}</td>
                <td class="text-end">
                    <a href="{{ route('enseignants.edit', $enseignant) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('enseignants.destroy', $enseignant) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet enseignant ?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">Aucun enseignant.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $enseignants->links() }}
</div>
@endsection
