<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Gestion des spécialités' ?></h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<form action="<?= BASE_URL ?>/admin/ajouter-specialite" method="post" class="row g-2 mb-4">
    <div class="col-md-8">
        <input type="text" name="nom" class="form-control" placeholder="Nouvelle spécialité" required>
    </div>
    <div class="col-md-4">
        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
    </div>
</form>

<table class="table table-striped">
    <thead><tr><th>Spécialité</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($specialites as $spe): ?>
        <tr>
            <td><?= htmlspecialchars($spe['nom']) ?></td>
            <td>
                <a href="<?= BASE_URL ?>/admin/supprimer-specialite/<?= $spe['id_specialite'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>