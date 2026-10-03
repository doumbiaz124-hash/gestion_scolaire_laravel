@extends('layouts.app')
@section('title', 'Matières - ' . $classe->nom_classe)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Matières et coefficients — {{ $classe->nom_classe }}</h3>
    <a href="{{ route('matieres.index') }}" class="btn btn-light">Retour</a>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card p-3">
            <h6>Matières configurées</h6>
            <table class="table table-sm align-middle">
                <thead><tr><th>Matière</th><th>Coefficient</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse ($matieres as $matiere)
                    <tr>
                        <form method="POST" action="{{ route('matieres.update', [$classe, $matiere]) }}" style="display: contents;">
                        @csrf @method('PUT')
                        <td><input type="text" name="nom_matiere" class="form-control form-control-sm" value="{{ $matiere->nom_matiere }}" required></td>
                        <td style="width: 120px;"><input type="number" step="0.01" min="0.1" name="coefficient" class="form-control form-control-sm" value="{{ $matiere->coefficient }}" required></td>
                        <td class="text-end" style="white-space: nowrap;">
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-check-lg"></i></button>
                            </form>
                            <form action="{{ route('matieres.destroy', [$classe, $matiere]) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette matière ?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted">Aucune matière configurée pour l'instant.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card p-3">
            <h6>Ajouter une matière</h6>
            <form method="POST" action="{{ route('matieres.store', $classe) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nom de la matière</label>
                    <input type="text" name="nom_matiere" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Coefficient</label>
                    <input type="number" step="0.01" min="0.1" name="coefficient" class="form-control" value="1" required>
                </div>
                <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajouter</button>
            </form>
        </div>
    </div>
</div>
@endsection
