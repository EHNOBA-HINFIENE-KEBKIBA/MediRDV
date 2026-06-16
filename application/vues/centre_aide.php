<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .aide-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        padding: 2rem 1.5rem;
        transition: transform 0.2s;
        height: 100%;
    }
    .aide-card:hover {
        transform: translateY(-3px);
    }
    .accordion-button {
        font-weight: 600;
    }
    .accordion-item {
        border: none;
        margin-bottom: 0.5rem;
        border-radius: 12px !important;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
</style>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold"><i class="bi bi-life-preserver me-2 text-primary"></i>Centre d'aide</h1>
        <p class="lead text-muted">Comment pouvons-nous vous aider ?</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="aide-card text-center">
                <i class="bi bi-calendar-check fs-1 text-primary"></i>
                <h4 class="mt-3">Prendre un rendez-vous</h4>
                <p>Apprenez à rechercher un médecin et réserver un créneau.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="aide-card text-center">
                <i class="bi bi-camera-video fs-1 text-success"></i>
                <h4 class="mt-3">Téléconsultation</h4>
                <p>Guide complet pour rejoindre une consultation vidéo.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="aide-card text-center">
                <i class="bi bi-credit-card fs-1 text-warning"></i>
                <h4 class="mt-3">Paiement</h4>
                <p>Informations sur les paiements et les remboursements.</p>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <h3 class="mb-4">Questions fréquentes</h3>
    <div class="accordion" id="faqAide">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#aide1">
                    Comment créer un compte ?
                </button>
            </h2>
            <div id="aide1" class="accordion-collapse collapse show">
                <div class="accordion-body">Cliquez sur "Inscription" puis remplissez le formulaire.</div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#aide2">
                    Comment annuler un rendez-vous ?
                </button>
            </h2>
            <div id="aide2" class="accordion-collapse collapse">
                <div class="accordion-body">Accédez à votre espace personnel puis sélectionnez le rendez-vous.</div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <p>Vous n'avez pas trouvé votre réponse ?</p>
        <a href="<?= BASE_URL ?>/contact" class="btn btn-primary"><i class="bi bi-headset me-1"></i>Contacter le support</a>
    </div>
</div>