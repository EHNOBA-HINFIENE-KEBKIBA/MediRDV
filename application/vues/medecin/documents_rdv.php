<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Documents du rendez-vous' ?></h2>

<div class="card mb-4">
    <div class="card-body">
        <h5>Rendez-vous du <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'], 0, 5) ?></h5>
        <p>Motif : <?= htmlspecialchars($rdv['motif']) ?></p>
    </div>
</div>

<?php if (empty($documents)): ?>
    <p class="text-muted">Aucun document joint.</p>
<?php else: ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nom du fichier</th>
                <th>Date d'upload</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($documents as $doc): ?>
            <tr>
                <td><?= htmlspecialchars($doc['nom_fichier']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($doc['date_upload'])) ?></td>
                <td>
                    <a href="<?= BASE_URL . '/' . $doc['chemin'] ?>" class="btn btn-sm btn-outline-primary" target="_blank">Télécharger</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="<?= BASE_URL ?>/medecin/agenda" class="btn btn-secondary">Retour à l'agenda</a>