<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?></h2>
<form method="post" action="<?= BASE_URL ?>/admin/blog/ajouter" class="mb-4">
    <input type="text" name="titre" class="form-control mb-2" placeholder="Titre de l'article" required>
    <textarea name="contenu" class="form-control mb-2" rows="5" placeholder="Contenu..." required></textarea>
    <button type="submit" class="btn btn-primary">Publier</button>
</form>
<table class="table table-hover">
    <thead><tr><th>Titre</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($articles as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['titre']) ?></td>
            <td><?= date('d/m/Y', strtotime($a['date_publication'])) ?></td>
            <td>
                <a href="<?= BASE_URL ?>/admin/blog/modifier/<?= $a['id_article'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                <a href="<?= BASE_URL ?>/admin/blog/supprimer/<?= $a['id_article'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>