<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2 class="fw-bold mb-4">📊 Paiements de l'établissement</h2>

<?php if (empty($paiements)): ?>
    <p>Aucun paiement enregistré.</p>
<?php else: ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Date</th>
                <th>Patient</th>
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
                <td><?= htmlspecialchars($p['patient_nom'].' '.$p['patient_prenom']) ?></td>
                <td><?= $p['rdv_reference'] ?></td>
                <td>Dr. <?= htmlspecialchars($p['medecin_nom'].' '.$p['medecin_prenom']) ?></td>
                <td class="fw-bold"><?= number_format($p['montant'], 0, ',', ' ') ?> FCFA</td>
                <td><span class="badge bg-info"><?= $p['mode'] ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>