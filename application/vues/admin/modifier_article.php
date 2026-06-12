<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2>Modifier l'article</h2>
<form method="post">
    <input type="text" name="titre" class="form-control mb-2" value="<?= htmlspecialchars($article['titre']) ?>" required>
    <textarea name="contenu" class="form-control mb-2" rows="10" required><?= htmlspecialchars($article['contenu']) ?></textarea>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="<?= BASE_URL ?>/admin/blog" class="btn btn-secondary">Annuler</a>
</form>