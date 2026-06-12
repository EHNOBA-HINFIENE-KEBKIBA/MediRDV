<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Gestion des services' ?></h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<form action="<?= BASE_URL ?>/admin/ajouter-service" method="post" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text" name="nom" class="form-control" placeholder="Nom du service" required>
    </div>
    <div class="col-md-5">
        <input type="text" name="description" class="form-control" placeholder="Description (optionnelle)">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
    </div>
</form>

<table class="table table-striped">
    <thead><tr><th>Service</th><th>Description</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($services as $srv): ?>
        <tr>
            <td><?= htmlspecialchars($srv['nom']) ?></td>
            <td><?= htmlspecialchars($srv['description'] ?? '') ?></td>
            <td>
                <a href="<?= BASE_URL ?>/admin/supprimer-service/<?= $srv['id_service'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>