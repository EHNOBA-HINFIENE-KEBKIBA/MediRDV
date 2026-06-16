<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    :root {
        --card-shadow: 0 8px 28px rgba(0,0,0,0.06);
        --card-hover-shadow: 0 18px 40px rgba(0,0,0,0.1);
        --border-radius: 20px;
        --primary: #0d6efd;
        --primary-light: #eef2ff;
    }
    body { background-color: #f4f6f9; }

    .page-wrapper {
        padding: 3rem 0;
        min-height: 100vh;
    }

    .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .blog-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        transition: transform 0.25s, box-shadow 0.25s;
        border: 1px solid rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }

    .blog-card .card-img-top {
        height: 180px;
        object-fit: cover;
        background: linear-gradient(135deg, #eef2ff 0%, #f0f4ff 100%);
    }
    .blog-card .no-image {
        height: 180px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .card-body {
        padding: 1.5rem 1.5rem 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .card-body h5 {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }
    .blog-date {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.75rem;
    }
    .blog-excerpt {
        color: #475569;
        margin-bottom: 1rem;
        flex: 1;
    }
    .btn-read {
        border-radius: 30px;
        font-weight: 600;
        padding: 0.5rem 1.2rem;
        transition: all 0.2s;
        align-self: flex-start;
    }
</style>

<div class="page-wrapper">
    <div class="container">
        <h1 class="fw-bold mb-2"><i class="bi bi-journal-text me-2 text-primary"></i>Blog Santé</h1>
        <p class="text-muted mb-5">Conseils, prévention et actualités médicales.</p>

        <?php if (empty($articles)): ?>
            <div class="text-center py-5">
                <i class="bi bi-emoji-frown fs-1 text-muted"></i>
                <p class="mt-3 text-muted">Aucun article pour le moment.</p>
            </div>
        <?php else: ?>
            <div class="blog-grid">
                <?php foreach ($articles as $article): ?>
                    <div class="blog-card">
                        <?php if (!empty($article['image'])): ?>
                            <img src="<?= BASE_URL . '/' . $article['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($article['titre']) ?>">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5><?= htmlspecialchars($article['titre']) ?></h5>
                            <p class="blog-date"><i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y', strtotime($article['date_publication'])) ?></p>
                            <p class="blog-excerpt"><?= substr(strip_tags($article['contenu']), 0, 150) ?>...</p>
                            <a href="<?= BASE_URL ?>/blog/article/<?= $article['id_article'] ?>" class="btn btn-outline-primary btn-read">Lire plus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>