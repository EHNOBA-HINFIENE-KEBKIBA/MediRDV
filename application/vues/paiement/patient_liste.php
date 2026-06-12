<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2 class="fw-bold mb-4">💳 Mes paiements</h2>

<?php if (!empty($_SESSION['message_paiement'])): ?>
    <div class="alert alert-success"><?= $_SESSION['message_paiement'] ?></div>
    <?php unset($_SESSION['message_paiement']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['erreur_paiement'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['erreur_paiement'] ?></div>
    <?php unset($_SESSION['erreur_paiement']); ?>
<?php endif; ?>

<?php if (empty($paiements)): ?>
    <p>Aucun paiement enregistré.</p>
<?php else: ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Date</th>
                <th>Rendez‑vous</th>
                <th>Médecin</th>
                <th>Montant</th>
                <th>Mode</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paiements as $p): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($p['date_paiement'])) ?></td>
                <td><?= $p['rdv_reference'] ?><br><small><?= date('d/m/Y', strtotime($p['date_rdv'])) ?> à <?= substr($p['heure_rdv'],0,5) ?></small></td>
                <td>Dr. <?= htmlspecialchars($p['medecin_nom'].' '.$p['medecin_prenom']) ?></td>
                <td class="fw-bold"><?= number_format($p['montant'], 0, ',', ' ') ?> FCFA</td>
                <td><span class="badge bg-info"><?= $p['mode'] ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<a href="<?= BASE_URL ?>/mes-rendezvous" class="btn btn-outline-primary">Retour aux rendez‑vous</a>