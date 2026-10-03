@extends('layouts.app')
@section('title', 'Emploi du temps - ' . $classe->nom_classe)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Emploi du temps — {{ $classe->nom_classe }}</h3>
    <div>
        <a href="{{ route('emploi-du-temps.imprimer', $classe) }}" class="btn btn-outline-secondary" target="_blank"><i class="bi bi-printer"></i> Imprimer</a>
        <a href="{{ route('emploi-du-temps.create', $classe) }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajouter un créneau</a>
        <a href="{{ route('emploi-du-temps.index') }}" class="btn btn-light">Retour</a>
    </div>
</div>

<div class="row g-3">
    @foreach ($jours as $jour)
        <div class="col-md-6">
            <div class="card p-3">
                <h6>{{ $jour }}</h6>
                <ul class="list-group list-group-flush">
                    @forelse ($grille[$jour] as $creneau)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <strong>{{ substr($creneau->heure_debut, 0, 5) }} - {{ substr($creneau->heure_fin, 0, 5) }}</strong>
                                — {{ $creneau->matiere }}
                                @if ($creneau->enseignant)
                                    <span class="text-muted">({{ $creneau->enseignant->prenom_enseignant }} {{ $creneau->enseignant->nom_enseignant }})</span>
                                @endif
                            </span>
                            <span>
                                <a href="{{ route('emploi-du-temps.edit', [$classe, $creneau]) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('emploi-du-temps.destroy', [$classe, $creneau]) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce créneau ?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Aucun cours.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    @endforeach
</div>
@endsection
