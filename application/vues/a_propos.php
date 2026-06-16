<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    :root {
        --card-shadow: 0 8px 28px rgba(0,0,0,0.06);
        --border-radius: 20px;
        --primary: #0d6efd;
        --primary-light: #eef2ff;
    }
    body { background-color: #f4f6f9; }

    .page-wrapper {
        padding: 3rem 0;
        min-height: 100vh;
    }

    .section-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(0,0,0,0.03);
        transition: transform 0.2s;
    }
    .section-card:hover {
        transform: translateY(-2px);
    }

    .section-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .value-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.25rem;
        margin-top: 1.5rem;
    }
    .value-item {
        background: white;
        border-radius: 14px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.2s;
    }
    .value-item:hover {
        background: var(--primary-light);
        border-color: var(--primary);
    }
    .value-item strong {
        display: block;
        margin-bottom: 0.35rem;
        color: #1e293b;
    }
    .value-item p {
        color: #475569;
        font-size: 0.95rem;
        margin: 0;
    }

    @media (max-width: 768px) {
        .page-wrapper { padding: 1.5rem 0; }
        .value-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="page-wrapper">
    <div class="container">
        <h1 class="fw-bold mb-2"><i class="bi bi-info-circle me-2 text-primary"></i>À propos de MediRDV</h1>
        <p class="text-muted mb-5">Découvrez notre histoire, notre mission et nos valeurs.</p>

        <!-- Histoire -->
        <div class="section-card">
            <div class="section-icon"><i class="bi bi-clock-history"></i></div>
            <h3>Notre Histoire</h3>
            <p>MediRDV est né en 2026 de la volonté de simplifier l’accès aux soins pour tous. Fondée par une équipe passionnée de professionnels de santé et d’ingénieurs, la plateforme s’est rapidement imposée comme un acteur incontournable de la digitalisation médicale au Cameroun et en Afrique.</p>
        </div>

        <!-- Mission -->
        <div class="section-card">
            <div class="section-icon"><i class="bi bi-bullseye"></i></div>
            <h3>Notre Mission</h3>
            <p>Faciliter la prise de rendez-vous médicaux, réduire les files d’attente et offrir à chaque patient un parcours de soin fluide, transparent et sécurisé.</p>
        </div>

        <!-- Vision -->
        <div class="section-card">
            <div class="section-icon"><i class="bi bi-eye"></i></div>
            <h3>Notre Vision</h3>
            <p>Devenir la plateforme de référence en Afrique pour la gestion des rendez-vous médicaux et la télémédecine, en connectant patients, médecins et établissements de santé.</p>
        </div>

        <!-- Valeurs -->
        <h3 class="mt-4 mb-3"><i class="bi bi-heart me-2 text-primary"></i>Nos Valeurs</h3>
        <div class="value-grid">
            <div class="value-item">
                <strong>Accessibilité</strong>
                <p>La santé pour tous, partout.</p>
            </div>
            <div class="value-item">
                <strong>Innovation</strong>
                <p>Utiliser les technologies les plus récentes pour simplifier la vie des patients.</p>
            </div>
            <div class="value-item">
                <strong>Confiance</strong>
                <p>Garantir la sécurité et la confidentialité des données médicales.</p>
            </div>
            <div class="value-item">
                <strong>Proximité</strong>
                <p>Un service client réactif et à l’écoute.</p>
            </div>
        </div>

        <!-- Équipe -->
        <div class="section-card mt-4">
            <div class="section-icon"><i class="bi bi-people"></i></div>
            <h3>Notre Équipe</h3>
            <p>Une équipe pluridisciplinaire de développeurs, médecins, et spécialistes de l’expérience utilisateur, unie par la même volonté d’améliorer l’accès aux soins.</p>
        </div>
    </div>
</div>