<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h1><?= htmlspecialchars($article['titre']) ?></h1>
<p class="text-muted">Publié le <?= date('d/m/Y à H:i', strtotime($article['date_publication'])) ?></p>
<hr>
<div><?= nl2br(htmlspecialchars($article['contenu'])) ?></div>
<a href="<?= BASE_URL ?>/blog" class="btn btn-outline-primary mt-3">Retour au blog</a>