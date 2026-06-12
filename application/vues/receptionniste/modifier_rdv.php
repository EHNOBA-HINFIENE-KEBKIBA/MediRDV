<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Modifier le rendez-vous' ?></h2>
<form action="<?= BASE_URL ?>/receptionniste/enregistrer-modification-rdv" method="post">
    <input type="hidden" name="id_rdv" value="<?= $rdv['id_rdv'] ?>">
    <div class="mb-3">
        <label for="id_medecin" class="form-label">Médecin</label>
        <select name="id_medecin" id="id_medecin" class="form-select">
            <?php foreach ($medecins as $med): ?>
            <option value="<?= $med['id_medecin'] ?>" <?= $rdv['id_medecin'] == $med['id_medecin'] ? 'selected' : '' ?>>
                Dr. <?= htmlspecialchars($med['nom'] . ' ' . $med['prenom']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="<?= $rdv['date_rdv'] ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="heure" class="form-label">Heure</label>
            <input type="time" name="heure" id="heure" class="form-control" value="<?= $rdv['heure_rdv'] ?>">
        </div>
    </div>
    <div class="mb-3">
        <label for="motif" class="form-label">Motif</label>
        <textarea name="motif" id="motif" class="form-control" rows="2"><?= htmlspecialchars($rdv['motif']) ?></textarea>
    </div>
    <div class="mb-3">
        <label for="statut" class="form-label">Statut</label>
        <select name="statut" id="statut" class="form-select">
            <option value="En attente" <?= $rdv['statut'] == 'En attente' ? 'selected' : '' ?>>En attente</option>
            <option value="Confirmé" <?= $rdv['statut'] == 'Confirmé' ? 'selected' : '' ?>>Confirmé</option>
            <option value="Reporté" <?= $rdv['statut'] == 'Reporté' ? 'selected' : '' ?>>Reporté</option>
            <option value="Annulé" <?= $rdv['statut'] == 'Annulé' ? 'selected' : '' ?>>Annulé</option>
            <option value="Terminé" <?= $rdv['statut'] == 'Terminé' ? 'selected' : '' ?>>Terminé</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="<?= BASE_URL ?>/receptionniste/tableau-bord" class="btn btn-secondary">Annuler</a>
</form>