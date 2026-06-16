<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<div class="container py-5">
    <h1 class="fw-bold mb-2"><?= htmlspecialchars($article['titre']) ?></h1>
    <p class="text-muted"><i class="bi bi-calendar3 me-1"></i>Publié le <?= date('d/m/Y à H:i', strtotime($article['date_publication'])) ?></p>
    <hr>
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div><?= nl2br(htmlspecialchars($article['contenu'])) ?></div>
    </div>
    <a href="<?= BASE_URL ?>/blog" class="btn btn-outline-primary mt-4"><i class="bi bi-arrow-left me-1"></i>Retour au blog</a>
</div>