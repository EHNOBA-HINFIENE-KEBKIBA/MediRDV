<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Modifier le rendez-vous' ?></h2>

<div class="card p-4">
    <form action="<?= BASE_URL ?>/medecin/modifier-rdv/<?= $rdv['id_rdv'] ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Patient</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($patient['nom'].' '.$patient['prenom']) ?>" readonly>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="date_rdv" class="form-label">Date</label>
                <input type="date" class="form-control" id="date_rdv" name="date_rdv" value="<?= $rdv['date_rdv'] ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="heure_rdv" class="form-label">Heure</label>
                <input type="time" class="form-control" id="heure_rdv" name="heure_rdv" value="<?= $rdv['heure_rdv'] ?>" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="motif" class="form-label">Motif</label>
            <textarea class="form-control" id="motif" name="motif" rows="2"><?= htmlspecialchars($rdv['motif']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="statut" class="form-label">Statut</label>
            <select class="form-select" id="statut" name="statut">
                <option value="En attente" <?= $rdv['statut'] == 'En attente' ? 'selected' : '' ?>>En attente</option>
                <option value="Confirmé" <?= $rdv['statut'] == 'Confirmé' ? 'selected' : '' ?>>Confirmé</option>
                <option value="Reporté" <?= $rdv['statut'] == 'Reporté' ? 'selected' : '' ?>>Reporté</option>
                <option value="Annulé" <?= $rdv['statut'] == 'Annulé' ? 'selected' : '' ?>>Annulé</option>
                <option value="Terminé" <?= $rdv['statut'] == 'Terminé' ? 'selected' : '' ?>>Terminé</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="<?= BASE_URL ?>/medecin/agenda" class="btn btn-secondary">Annuler</a>
    </form>
</div>