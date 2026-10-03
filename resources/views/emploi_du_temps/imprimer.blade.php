@extends('layouts.app')
@section('title', 'Emploi du temps - ' . $classe->nom_classe)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h3>Emploi du temps — {{ $classe->nom_classe }}</h3>
    <div>
        <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Imprimer</button>
        <a href="{{ route('emploi-du-temps.show', $classe) }}" class="btn btn-light">Retour</a>
    </div>
</div>

<div class="card p-4">
    <div class="text-center mb-4">
        <h4>Emploi du temps de la classe {{ $classe->nom_classe }}</h4>
    </div>
    <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
        <tr>
            @foreach ($jours as $jour)
                <th>{{ $jour }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        <tr>
            @foreach ($jours as $jour)
                <td style="vertical-align: top;">
                    @forelse ($grille[$jour] as $creneau)
                        <div class="mb-2 pb-2 border-bottom">
                            <div class="small text-muted">{{ substr($creneau->heure_debut, 0, 5) }} - {{ substr($creneau->heure_fin, 0, 5) }}</div>
                            <div class="fw-bold">{{ $creneau->matiere }}</div>
                            @if ($creneau->enseignant)
                                <div class="small">({{ $creneau->enseignant->prenom_enseignant }} {{ $creneau->enseignant->nom_enseignant }})</div>
                            @endif
                        </div>
                    @empty
                        <span class="text-muted small">—</span>
                    @endforelse
                </td>
            @endforeach
        </tr>
        </tbody>
    </table>
</div>
@endsection
