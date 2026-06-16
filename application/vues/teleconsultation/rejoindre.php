<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Téléconsultation' ?></h2>
<p>Rendez-vous du <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'],0,5) ?></p>

<?php
$dateHeureRdv = strtotime($rdv['date_rdv'] . ' ' . $rdv['heure_rdv']);
$maintenant = time();
$peutRejoindre = ($dateHeureRdv <= $maintenant);
?>

<?php if ($statut == 'active' && !empty($lien) && $peutRejoindre): ?>
    <div class="alert alert-success">
        <i class="bi bi-camera-video me-2"></i> La téléconsultation est disponible. Cliquez ci-dessous pour rejoindre.
    </div>
    <a href="<?= htmlspecialchars($lien) ?>" target="_blank" class="btn btn-success btn-lg w-100">🚀 Rejoindre la salle</a>
<?php elseif ($statut == 'active' && !$peutRejoindre): ?>
    <div class="alert alert-info">
        <i class="bi bi-clock me-2"></i> La téléconsultation est active mais vous pourrez rejoindre à partir de <?= date('d/m/Y à H:i', $dateHeureRdv) ?>.
    </div>
<?php else: ?>
    <div class="alert alert-info">
        <i class="bi bi-clock me-2"></i> La téléconsultation n'est pas encore activée par le médecin.
    </div>
<?php endif; ?>

<a href="<?= BASE_URL ?>/mes-rendezvous" class="btn btn-secondary mt-3">Retour aux rendez-vous</a>