<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2>Blog Santé</h2>
<div class="row">
    <?php if (empty($articles)): ?>
        <p>Aucun article pour le moment.</p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($article['titre']) ?></h5>
                    <p class="card-text text-muted"><?= date('d/m/Y', strtotime($article['date_publication'])) ?></p>
                    <p><?= substr(strip_tags($article['contenu']), 0, 150) ?>...</p>
                    <a href="<?= BASE_URL ?>/blog/article/<?= $article['id_article'] ?>" class="btn btn-sm btn-outline-primary">Lire plus</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>