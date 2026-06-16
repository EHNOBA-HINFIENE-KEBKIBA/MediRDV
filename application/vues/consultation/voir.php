<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?></h2>
<div class="card mb-3">
    <div class="card-body">
        <h5>Rendez-vous du <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'], 0, 5) ?></h5>
        <p>Référence : <span class="badge bg-secondary"><?= $rdv['reference'] ?></span></p>
        <p>Médecin : <strong>Dr. <?= htmlspecialchars($rdv['medecin_nom'] . ' ' . $rdv['medecin_prenom'] ?? '') ?></strong></p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="border-bottom pb-2">📋 Diagnostic</h4>
        <div class="p-3 bg-light rounded mb-4"><?= nl2br(htmlspecialchars($consultation['diagnostic'] ?? '')) ?></div>

        <h4 class="border-bottom pb-2">💊 Prescription</h4>
        <div class="p-3 bg-light rounded mb-4"><?= nl2br(htmlspecialchars($consultation['prescription'] ?? '')) ?></div>

        <h4 class="border-bottom pb-2">📝 Notes médicales</h4>
        <div class="p-3 bg-light rounded mb-4"><?= nl2br(htmlspecialchars($consultation['notes_medicales'] ?? '')) ?></div>

        <?php if ($consultation['signature']): ?>
            <div class="alert alert-success">
                <?php if (!empty($consultation['signature_image'])): ?>
                    <img src="<?= BASE_URL . '/' . $consultation['signature_image'] ?>" style="max-width:200px;" alt="Signature du médecin">
                <?php else: ?>
                    <i class="bi bi-check-circle me-2"></i> <strong>Signature électronique :</strong> Dr. <?= htmlspecialchars($rdv['medecin_nom'] . ' ' . $rdv['medecin_prenom'] ?? '') ?> – Validée le <?= date('d/m/Y à H:i', strtotime($consultation['updated_at'] ?? $consultation['created_at'])) ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($consultation['pdf_chemin'])): ?>
            <a href="<?= BASE_URL . '/' . $consultation['pdf_chemin'] ?>" class="btn btn-outline-primary" target="_blank">📄 Télécharger l'ordonnance PDF</a>
        <?php endif; ?>
    </div>
    <?php if ($consultation['signature']): ?>
    <a href="<?= BASE_URL ?>/consultation/ordonnance/<?= $rdv['id_rdv'] ?>" class="btn btn-outline-primary" target="_blank">📄 Voir l'ordonnance</a>
<?php endif; ?>
    <div class="card-footer text-muted">
        Consultation enregistrée le <?= date('d/m/Y à H:i', strtotime($consultation['created_at'])) ?>
    </div>
</div>

<a href="<?= BASE_URL ?>/mes-rendezvous" class="btn btn-outline-primary mt-3">← Retour aux rendez-vous</a>