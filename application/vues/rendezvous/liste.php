<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2>Mes rendez-vous</h2>
<?php if (empty($rdvs)): ?>
    <p>Vous n'avez aucun rendez-vous.</p>
    <a href="<?= BASE_URL ?>/prendre-rdv" class="btn btn-primary">Prendre un rendez-vous</a>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Médecin</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Statut</th>
                    <th>QR Code</th>
                    <th>Téléconsultation</th>
                    <th>Paiement</th>
                    <th>Consultation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rdvs as $rdv): ?>
                <tr>
                    <td><?= $rdv['reference'] ?></td>
                    <td>Dr. <?= $rdv['medecin_nom'] . ' ' . $rdv['medecin_prenom'] ?></td>
                    <td><?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?></td>
                    <td><?= substr($rdv['heure_rdv'], 0, 5) ?></td>
                    <td>
                        <span class="badge bg-<?= $rdv['statut'] == 'Confirmé' ? 'success' : ($rdv['statut'] == 'En attente' ? 'warning' : 'secondary') ?>">
                            <?= $rdv['statut'] ?>
                        </span>
                    </td>
<td>
    <?php
    // Texte à encoder dans le QR code (identique à celui généré côté serveur)
    $qrTexte = "RDV:" . $rdv['reference'] . " - Dr " . $rdv['medecin_nom'] . " " . $rdv['medecin_prenom'] . " le " . date('d/m/Y', strtotime($rdv['date_rdv'])) . " à " . substr($rdv['heure_rdv'], 0, 5);
    ?>
    <div class="qr-code-container" data-qr="<?= htmlspecialchars($qrTexte) ?>"></div>
</td>
                    <td>
                        <?php if (!empty($rdv['teleconsultation']) && $rdv['statut'] == 'Confirmé'): ?>
                            <a href="<?= BASE_URL ?>/teleconsultation/rejoindre/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-success">Rejoindre</a>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($rdv['statut'] == 'Confirmé'): ?>
                            <a href="<?= BASE_URL ?>/paiement/payer/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-outline-primary">💳 Payer</a>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($rdv['statut'] == 'Terminé'): ?>
                            <a href="<?= BASE_URL ?>/consultation/voir/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-outline-info">Voir</a>
                        <?php else: ?>
                            <span class="text-muted">En attente</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.qr-code-container').forEach(function(container) {
        var texte = container.getAttribute('data-qr');
        if (texte) {
            new QRCode(container, {
                text: texte,
                width: 80,
                height: 80,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.L
            });
        } else {
            container.innerHTML = '<span class="text-muted">-</span>';
        }
    });
});
</script>
<?php endif; ?>&