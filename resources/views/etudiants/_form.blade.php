@php $e = $etudiant ?? null; @endphp
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nom</label>
        <input type="text" name="nom_etudiant" class="form-control" value="{{ old('nom_etudiant', $e->nom_etudiant ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Prénom</label>
        <input type="text" name="prenom_etudiant" class="form-control" value="{{ old('prenom_etudiant', $e->prenom_etudiant ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Date de naissance</label>
        <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $e->date_naissance ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Sexe</label>
        <select name="sexe_etudiant" class="form-select">
            <option value="">--</option>
            <option value="M" {{ old('sexe_etudiant', $e->sexe_etudiant ?? '') == 'M' ? 'selected' : '' }}>Masculin</option>
            <option value="F" {{ old('sexe_etudiant', $e->sexe_etudiant ?? '') == 'F' ? 'selected' : '' }}>Féminin</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Téléphone du tuteur</label>
        <input type="text" name="tel_tuteur" class="form-control" value="{{ old('tel_tuteur', $e->tel_tuteur ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Année scolaire</label>
        <input type="text" name="annee_scolaire" class="form-control" placeholder="2025-2026" value="{{ old('annee_scolaire', $e->annee_scolaire ?? '') }}">
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Adresse</label>
        <input type="text" name="adresse" class="form-control" value="{{ old('adresse', $e->adresse ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Classe</label>
        <select name="classe_id" class="form-select">
            <option value="">--</option>
            @foreach ($classes as $classe)
                <option value="{{ $classe->id }}" {{ old('classe_id', $e->classe_id ?? '') == $classe->id ? 'selected' : '' }}>{{ $classe->nom_classe }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Série</label>
        <select name="serie_id" class="form-select">
            <option value="">--</option>
            @foreach ($series as $serie)
                <option value="{{ $serie->id }}" {{ old('serie_id', $e->serie_id ?? '') == $serie->id ? 'selected' : '' }}>{{ $serie->nom_serie }}</option>
            @endforeach
        </select>
    </div>
</div>
