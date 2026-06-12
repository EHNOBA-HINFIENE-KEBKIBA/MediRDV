<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?></h2>
<?php if ($message): ?><div class="alert alert-info"><?= $message ?></div><?php endif; ?>
<table class="table table-hover">
    <thead><tr><th>Nom</th><th>Spécialité</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($medecins as $m): ?>
        <tr>
            <td>Dr. <?= htmlspecialchars($m['nom'].' '.$m['prenom']) ?></td>
            <td><?= htmlspecialchars($m['specialite_nom'] ?? '') ?></td>
            <td><a href="<?= BASE_URL ?>/admin-etablissement/modifier-medecin/<?= $m['id_medecin'] ?>" class="btn btn-sm btn-outline-primary">Gérer disponibilités</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>