@php $en = $enseignant ?? null; @endphp
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nom</label>
        <input type="text" name="nom_enseignant" class="form-control" value="{{ old('nom_enseignant', $en->nom_enseignant ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Prénom</label>
        <input type="text" name="prenom_enseignant" class="form-control" value="{{ old('prenom_enseignant', $en->prenom_enseignant ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Téléphone</label>
        <input type="text" name="tel_enseignant" class="form-control" value="{{ old('tel_enseignant', $en->tel_enseignant ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Matière enseignée</label>
        <input type="text" name="matiere_enseignee" class="form-control" value="{{ old('matiere_enseignee', $en->matiere_enseignee ?? '') }}">
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Adresse</label>
        <input type="text" name="adresse_enseignant" class="form-control" value="{{ old('adresse_enseignant', $en->adresse_enseignant ?? '') }}">
    </div>
</div>
