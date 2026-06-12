<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?></h2>

<?php if (empty($consultations)): ?>
    <div class="alert alert-info">Aucune consultation enregistrée.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Médecin</th>
                    <th>Référence RDV</th>
                    <th>Diagnostic</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($consultations as $c): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($c['date_rdv'])) ?></td>
                    <td>Dr. <?= htmlspecialchars($c['medecin_nom'] . ' ' . $c['medecin_prenom']) ?></td>
                    <td><?= $c['reference'] ?></td>
                    <td><?= substr(htmlspecialchars($c['diagnostic'] ?? ''), 0, 80) ?>...</td>
                    <td><a href="<?= BASE_URL ?>/consultation/voir/<?= $c['id_rdv'] ?>" class="btn btn-sm btn-outline-primary">Voir</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>