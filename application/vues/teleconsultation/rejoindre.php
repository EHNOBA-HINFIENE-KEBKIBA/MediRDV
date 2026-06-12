<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Téléconsultation' ?></h2>
<p>Rendez-vous du <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'],0,5) ?></p>
<div class="ratio ratio-16x9">
    <iframe src="<?= htmlspecialchars($lien) ?>" allow="camera;microphone;fullscreen" allowfullscreen></iframe>
</div>
<a href="<?= BASE_URL ?>/mes-rendezvous" class="btn btn-secondary mt-3">Retour</a>