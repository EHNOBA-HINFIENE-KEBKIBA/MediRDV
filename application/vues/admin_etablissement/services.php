<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Gérer les services' ?></h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<p>Associez ou dissociez les services proposés par votre établissement.</p>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Service</th>
            <th>Description</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($services as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['nom']) ?></td>
            <td><?= htmlspecialchars($s['description'] ?? '') ?></td>
            <td>
                <?php if ($s['associe']): ?>
                    <span class="badge bg-success">Associé</span>
                <?php else: ?>
                    <span class="badge bg-secondary">Non associé</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($s['associe']): ?>
                    <a href="<?= BASE_URL ?>/admin-etablissement/dissocier-service/<?= $s['id_service'] ?>" class="btn btn-sm btn-outline-danger">Dissocier</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/admin-etablissement/associer-service/<?= $s['id_service'] ?>" class="btn btn-sm btn-outline-success">Associer</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>