<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?></h2>

<div class="card mb-3">
    <div class="card-body">
        <h5>Rendez-vous du <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'], 0, 5) ?></h5>
        <p>Référence : <span class="badge bg-secondary"><?= $rdv['reference'] ?></span></p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="border-bottom pb-2">📋 Diagnostic</h4>
        <div class="p-3 bg-light rounded mb-4">
            <?= nl2br(htmlspecialchars($consultation['diagnostic'] ?? 'Non renseigné')) ?>
        </div>

        <h4 class="border-bottom pb-2">💊 Prescription</h4>
        <div class="p-3 bg-light rounded mb-4">
            <?= nl2br(htmlspecialchars($consultation['prescription'] ?? 'Aucune prescription')) ?>
        </div>

        <h4 class="border-bottom pb-2">📝 Notes médicales</h4>
        <div class="p-3 bg-light rounded">
            <?= nl2br(htmlspecialchars($consultation['notes_medicales'] ?? 'Aucune note')) ?>
        </div>
    </div>
    <div class="card-footer text-muted">
        Consultation enregistrée le <?= date('d/m/Y à H:i', strtotime($consultation['created_at'])) ?>
        <?= !empty($consultation['updated_at']) && $consultation['updated_at'] != $consultation['created_at'] ? '(modifiée le ' . date('d/m/Y à H:i', strtotime($consultation['updated_at'])) . ')' : '' ?>
    </div>
</div>

<a href="<?= BASE_URL ?>/mes-rendezvous" class="btn btn-outline-primary mt-3">← Retour aux rendez-vous</a>