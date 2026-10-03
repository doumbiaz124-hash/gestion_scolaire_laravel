@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Utilisateurs</h3>
    <a href="{{ route('utilisateurs.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nouvel utilisateur</a>
</div>
<div class="card p-3">
    <table class="table table-hover align-middle">
        <thead><tr><th>#</th><th>Email</th><th>Rôle</th><th>Tableaux de bord</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @forelse ($utilisateurs as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->email }}</td>
                <td><span class="badge bg-secondary">{{ $u->role->nom_role ?? '-' }}</span></td>
                <td>
                    @if ($u->isAdmin())
                        <span class="badge bg-primary">Tous (Administrateur)</span>
                    @elseif (!empty($u->permissions))
                        @foreach ($u->permissions as $perm)
                            <span class="badge bg-light text-dark border">{{ \App\Models\Utilisateur::MODULES[$perm] ?? $perm }}</span>
                        @endforeach
                    @else
                        <span class="text-muted small">Aucun</span>
                    @endif
                </td>
                <td class="text-end">
                    <a href="{{ route('utilisateurs.edit', $u) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('utilisateurs.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted">Aucun utilisateur.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $utilisateurs->links() }}
</div>
@endsection
