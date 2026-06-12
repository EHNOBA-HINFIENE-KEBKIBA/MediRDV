<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?></h2>

<div class="card mb-4">
    <div class="card-body">
        <h5>Rendez-vous du <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'], 0, 5) ?></h5>
        <p>Patient : <strong><?= htmlspecialchars($patient['nom'] . ' ' . $patient['prenom']) ?></strong></p>
        <p>Motif : <?= htmlspecialchars($rdv['motif']) ?></p>
    </div>
</div>

<form method="post">
    <div class="mb-3">
        <label for="diagnostic" class="form-label fw-bold">Diagnostic</label>
        <textarea name="diagnostic" id="diagnostic" class="form-control" rows="4" required><?= htmlspecialchars($consultation['diagnostic'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
        <label for="prescription" class="form-label fw-bold">Prescription</label>
        <textarea name="prescription" id="prescription" class="form-control" rows="4"><?= htmlspecialchars($consultation['prescription'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
        <label for="notes" class="form-label fw-bold">Notes médicales</label>
        <textarea name="notes" id="notes" class="form-control" rows="3"><?= htmlspecialchars($consultation['notes_medicales'] ?? '') ?></textarea>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Enregistrer la consultation</button>
        <a href="<?= BASE_URL ?>/medecin/agenda" class="btn btn-secondary">Annuler</a>
    </div>
</form>