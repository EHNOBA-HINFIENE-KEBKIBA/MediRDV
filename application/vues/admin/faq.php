<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?></h2>
<form method="post" action="<?= BASE_URL ?>/admin/faq/ajouter" class="mb-4">
    <input type="text" name="question" class="form-control mb-2" placeholder="Question" required>
    <textarea name="reponse" class="form-control mb-2" rows="3" placeholder="Réponse" required></textarea>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>
<table class="table table-hover">
    <thead><tr><th>Question</th><th>Réponse</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($faqs as $f): ?>
        <tr>
            <td><?= htmlspecialchars($f['question']) ?></td>
            <td><?= htmlspecialchars($f['reponse']) ?></td>
            <td><a href="<?= BASE_URL ?>/admin/faq/supprimer/<?= $f['id_faq'] ?>" class="btn btn-sm btn-danger">Supprimer</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>