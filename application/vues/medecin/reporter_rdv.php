<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Reporter le rendez-vous' ?></h2>

<div class="card mb-4">
    <div class="card-body">
        <h5>Rendez-vous actuel</h5>
        <p><strong>Date :</strong> <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'], 0, 5) ?></p>
        <p><strong>Patient :</strong> <?= htmlspecialchars($patient['nom'] . ' ' . $patient['prenom']) ?></p>
        <p><strong>Motif initial :</strong> <?= htmlspecialchars($rdv['motif']) ?></p>
    </div>
</div>

<form method="post" action="<?= BASE_URL ?>/medecin/enregistrer-report/<?= $rdv['id_rdv'] ?>">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="nouvelle_date" class="form-label fw-bold">Nouvelle date</label>
            <input type="date" class="form-control" id="nouvelle_date" name="nouvelle_date" min="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="nouvelle_heure" class="form-label fw-bold">Nouvelle heure</label>
            <input type="time" class="form-control" id="nouvelle_heure" name="nouvelle_heure" required>
        </div>
    </div>
    <div class="mb-3">
        <label for="message_report" class="form-label fw-bold">Message pour le patient</label>
        <textarea class="form-control" id="message_report" name="message_report" rows="3" placeholder="Raison du report..."></textarea>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-warning">Enregistrer le report</button>
        <a href="<?= BASE_URL ?>/medecin/agenda" class="btn btn-secondary">Annuler</a>
    </div>
</form>