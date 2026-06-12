<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Gestion des établissements' ?></h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<a href="<?= BASE_URL ?>/admin/ajouter-etablissement" class="btn btn-primary mb-3">Ajouter un établissement</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Type</th>
            <th>Ville</th>
            <th>Téléphone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($etablissements as $etab): ?>
        <tr>
            <td><?= htmlspecialchars($etab['nom']) ?></td>
            <td><?= $etab['type'] ?></td>
            <td><?= htmlspecialchars($etab['ville_nom'] ?? '') ?></td>
            <td><?= htmlspecialchars($etab['telephone']) ?></td>
            <td>
                <a href="<?= BASE_URL ?>/admin/modifier-etablissement/<?= $etab['id_etablissement'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                <a href="<?= BASE_URL ?>/admin/supprimer-etablissement/<?= $etab['id_etablissement'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>