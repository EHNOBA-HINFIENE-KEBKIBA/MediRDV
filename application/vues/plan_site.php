<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .list-group-item {
        border: none;
        border-radius: 8px !important;
        margin-bottom: 0.5rem;
        background: #f8f9fa;
        transition: background 0.2s;
    }
    .list-group-item:hover {
        background: #e9ecef;
    }
    .list-group-item a {
        text-decoration: none;
        color: #0d6efd;
        font-weight: 500;
    }
        .page-content {
        padding-top: 1.5rem;
        padding-bottom: 3rem;
        padding-left: 1.5rem;
        padding-right: 1.5rem;}
</style>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold"><i class="bi bi-map me-2 text-primary"></i>Plan du site</h1>
        <p class="text-muted">Retrouvez rapidement toutes les pages de MediRDV.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h4 class="mb-3"><i class="bi bi-globe me-2 text-primary"></i>Pages publiques</h4>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="<?= BASE_URL ?>"><i class="bi bi-house me-2"></i>Accueil</a></li>
                    <li class="list-group-item"><a href="<?= BASE_URL ?>/a-propos"><i class="bi bi-info-circle me-2"></i>À propos</a></li>
                    <li class="list-group-item"><a href="<?= BASE_URL ?>/services"><i class="bi bi-grid me-2"></i>Services</a></li>
                    <li class="list-group-item"><a href="<?= BASE_URL ?>/medecins"><i class="bi bi-person-badge me-2"></i>Médecins</a></li>
                    <li class="list-group-item"><a href="<?= BASE_URL ?>/etablissements"><i class="bi bi-building me-2"></i>Établissements</a></li>
                    <li class="list-group-item"><a href="<?= BASE_URL ?>/blog"><i class="bi bi-journal-text me-2"></i>Blog Santé</a></li>
                    <li class="list-group-item"><a href="<?= BASE_URL ?>/faq"><i class="bi bi-question-circle me-2"></i>FAQ</a></li>
                    <li class="list-group-item"><a href="<?= BASE_URL ?>/contact"><i class="bi bi-envelope me-2"></i>Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h4 class="mb-3"><i class="bi bi-shield-lock me-2 text-primary"></i>Informations légales</h4>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="<?= BASE_URL ?>/mentions-legales"><i class="bi bi-file-text me-2"></i>Mentions légales</a></li>
                    <li class="list-group-item"><a href="<?= BASE_URL ?>/confidentialite"><i class="bi bi-lock me-2"></i>Politique de confidentialité</a></li>
                    <li class="list-group-item"><a href="<?= BASE_URL ?>/conditions"><i class="bi bi-check2-square me-2"></i>Conditions d'utilisation</a></li>
                    <li class="list-group-item"><a href="<?= BASE_URL ?>/cookies"><i class="bi bi-cookie me-2"></i>Politique des cookies</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>