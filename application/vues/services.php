<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .service-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        padding: 2rem 1.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
        text-align: center;
        height: 100%;
    }
    .service-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .service-icon {
        font-size: 2.5rem;
        color: #0d6efd;
        margin-bottom: 1rem;
    }
        .page-content {
        padding-top: 1.5rem;
        padding-bottom: 3rem;
        padding-left: 1.5rem;
        padding-right: 1.5rem;}
</style>

<div class="container py-5">
    <h1 class="fw-bold mb-2 text-center"><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Nos Services</h1>
    <p class="text-muted text-center mb-5">Une plateforme complète pour votre santé.</p>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="service-card">
                <div class="service-icon"><i class="bi bi-calendar-check"></i></div>
                <h4>Rendez-vous médicaux</h4>
                <p>Réservez en ligne 24h/24 et 7j/7. Choisissez votre médecin, un créneau et recevez une confirmation immédiate.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="service-card">
                <div class="service-icon"><i class="bi bi-camera-video"></i></div>
                <h4>Téléconsultation</h4>
                <p>Consultez votre médecin à distance par vidéo, sans vous déplacer. Un lien sécurisé vous est fourni.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="service-card">
                <div class="service-icon"><i class="bi bi-credit-card"></i></div>
                <h4>Paiement en ligne</h4>
                <p>Réglez vos consultations en ligne par carte bancaire, mobile money ou espèces. Recevez une facture.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="service-card">
                <div class="service-icon"><i class="bi bi-hospital"></i></div>
                <h4>Gestion hospitalière</h4>
                <p>Les établissements de santé peuvent gérer leurs médecins, agendas et files d'attente simplement.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="service-card">
                <div class="service-icon"><i class="bi bi-bell"></i></div>
                <h4>Notifications</h4>
                <p>Soyez rappelé par SMS ou email de vos rendez-vous à venir.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="service-card">
                <div class="service-icon"><i class="bi bi-qr-code"></i></div>
                <h4>QR Code</h4>
                <p>À l'accueil, présentez simplement votre QR code pour valider votre présence.</p>
            </div>
        </div>
    </div>
</div>