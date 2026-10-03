@extends('layouts.app')
@section('title', 'Rôles')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Rôles</h3>
    <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nouveau rôle</a>
</div>
<div class="card p-3">
    <table class="table table-hover align-middle">
        <thead><tr><th>#</th><th>Nom du rôle</th><th>Utilisateurs</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @forelse ($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->nom_role }}</td>
                <td>{{ $role->utilisateurs_count }}</td>
                <td class="text-end">
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce rôle ?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">Aucun rôle.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $roles->links() }}
</div>
@endsection
