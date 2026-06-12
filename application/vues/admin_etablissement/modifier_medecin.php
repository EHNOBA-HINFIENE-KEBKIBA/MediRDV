<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?> : Dr. <?= htmlspecialchars($medecin['nom'] ?? '').' '.htmlspecialchars($medecin['prenom'] ?? '') ?></h2>
<form method="post" action="<?= BASE_URL ?>/admin-etablissement/enregistrer-medecin">
    <input type="hidden" name="id_medecin" value="<?= $medecin['id_medecin'] ?>">
    <h4>Disponibilités</h4>
    <div id="disponibilites">
        <?php if (!empty($disponibilites)): ?>
            <?php foreach ($disponibilites as $d): ?>
            <div class="row mb-2">
                <div class="col-md-3"><select name="dispo_jour[]" class="form-select"><option><?= $d['jour'] ?></option>...</select></div>
                <div class="col-md-3"><input type="time" name="dispo_debut[]" value="<?= $d['heure_debut'] ?>" class="form-control"></div>
                <div class="col-md-3"><input type="time" name="dispo_fin[]" value="<?= $d['heure_fin'] ?>" class="form-control"></div>
                <div class="col-md-3"><button type="button" class="btn btn-danger supprimer-dispo">X</button></div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" id="ajouter-dispo" class="btn btn-sm btn-outline-primary mb-3">+ Ajouter un créneau</button><br>
    <button type="submit" class="btn btn-success">Enregistrer</button>
    <a href="<?= BASE_URL ?>/admin-etablissement/medecins" class="btn btn-secondary">Annuler</a>
</form>
<script>
document.getElementById('ajouter-dispo').addEventListener('click', function(){
    var div = document.createElement('div');
    div.className = 'row mb-2';
    div.innerHTML = '<div class="col-md-3"><select name="dispo_jour[]" class="form-select"><option>Lundi</option><option>Mardi</option>...></select></div>'+
    '<div class="col-md-3"><input type="time" name="dispo_debut[]" class="form-control"></div>'+
    '<div class="col-md-3"><input type="time" name="dispo_fin[]" class="form-control"></div>'+
    '<div class="col-md-3"><button type="button" class="btn btn-danger supprimer-dispo">X</button></div>';
    document.getElementById('disponibilites').appendChild(div);
    div.querySelector('.supprimer-dispo').addEventListener('click', function(){ div.remove(); });
});
</script>