<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?></h2>
<?php if ($message): ?><div class="alert alert-info"><?= $message ?></div><?php endif; ?>

<form action="<?= BASE_URL ?>/admin/temoignages/ajouter" method="post" class="row g-2 mb-4">
    <div class="col-md-3"><input type="text" name="nom" class="form-control" placeholder="Nom" required></div>
    <div class="col-md-3"><input type="text" name="profession" class="form-control" placeholder="Profession"></div>
    <div class="col-md-1"><input type="number" name="note" class="form-control" min="1" max="5" value="5"></div>
    <div class="col-md-3"><textarea name="contenu" class="form-control" placeholder="Témoignage" required></textarea></div>
    <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Ajouter</button></div>
</form>

<table class="table table-hover">
    <thead><tr><th>Nom</th><th>Profession</th><th>Note</th><th>Actif</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($temoignages as $t): ?>
        <tr>
            <td><?= htmlspecialchars($t['nom']) ?></td>
            <td><?= htmlspecialchars($t['profession'] ?? '') ?></td>
            <td><?= $t['note'] ?>/5</td>
            <td><?= $t['actif'] ? '✅' : '❌' ?></td>
            <td>
                <a href="<?= BASE_URL ?>/admin/temoignages/basculer/<?= $t['id_temoignage'] ?>" class="btn btn-sm btn-warning">Basculer</a>
                <a href="<?= BASE_URL ?>/admin/temoignages/supprimer/<?= $t['id_temoignage'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>