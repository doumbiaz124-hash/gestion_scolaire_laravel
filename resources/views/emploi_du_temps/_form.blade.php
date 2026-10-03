@php $c = $creneau ?? null; @endphp
<div class="mb-3">
    <label class="form-label">Jour</label>
    <select name="jour" class="form-select" required>
        @foreach ($jours as $j)
            <option value="{{ $j }}" {{ old('jour', $c->jour ?? '') == $j ? 'selected' : '' }}>{{ $j }}</option>
        @endforeach
    </select>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Heure de début</label>
        <input type="time" name="heure_debut" class="form-control" value="{{ old('heure_debut', $c->heure_debut ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Heure de fin</label>
        <input type="time" name="heure_fin" class="form-control" value="{{ old('heure_fin', $c->heure_fin ?? '') }}" required>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Matière</label>
    <input type="text" name="matiere" class="form-control" value="{{ old('matiere', $c->matiere ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Enseignant</label>
    <select name="enseignant_id" class="form-select">
        <option value="">--</option>
        @foreach ($enseignants as $enseignant)
            <option value="{{ $enseignant->id }}" {{ old('enseignant_id', $c->enseignant_id ?? '') == $enseignant->id ? 'selected' : '' }}>{{ $enseignant->prenom_enseignant }} {{ $enseignant->nom_enseignant }}</option>
        @endforeach
    </select>
    <small class="text-muted">Le nom de l'enseignant apparaîtra entre parenthèses sous la matière.</small>
</div>
