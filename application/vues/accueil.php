<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<!-- Section Héro -->
<section class="hero text-center">
    <div class="container">
        <h1>Bienvenue sur MediRDV</h1>
        <p>Votre santé, votre rendez-vous, partout et à tout moment.</p>
        <a href="<?= BASE_URL ?>/prendre-rdv" class="btn btn-primary btn-lg px-5 py-3 fw-bold">Prendre rendez-vous</a>
    </div>
</section>

<!-- Pourquoi choisir MediRDV ? -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Pourquoi choisir MediRDV ?</h2>
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="p-3">
                    <div class="display-6 text-primary">⚡</div>
                    <h5>Réservation rapide</h5>
                    <p class="text-muted">Prenez rendez-vous en quelques clics, 24h/24 et 7j/7.</p>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="p-3">
                    <div class="display-6 text-primary">📹</div>
                    <h5>Téléconsultation</h5>
                    <p class="text-muted">Consultez votre médecin à distance par vidéo.</p>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="p-3">
                    <div class="display-6 text-primary">💳</div>
                    <h5>Paiement sécurisé</h5>
                    <p class="text-muted">Payez en ligne en toute sécurité (carte, mobile money).</p>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="p-3">
                    <div class="display-6 text-primary">🔔</div>
                    <h5>Notifications</h5>
                    <p class="text-muted">Recevez des rappels par SMS, email ou WhatsApp.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistiques -->
<section class="stats-section py-5">
    <div class="container text-center">
        <h2 class="mb-5">Nos chiffres</h2>
        <div class="row">
            <div class="col-md-3"><span class="stat-box"><?= $stats['medecins'] ?>+</span><br>Médecins</div>
            <div class="col-md-3"><span class="stat-box"><?= $stats['etablissements'] ?>+</span><br>Établissements</div>
            <div class="col-md-3"><span class="stat-box"><?= $stats['patients'] ?>+</span><br>Patients</div>
            <div class="col-md-3"><span class="stat-box"><?= $stats['rendezvous'] ?>+</span><br>Rendez-vous</div>
        </div>
    </div>
</section>

<!-- Témoignages -->
<section class="py-5 bg-white">
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-5">Ce que nos patients disent</h2>
        <div class="row">
            <?php if (!empty($temoignages)): ?>
                <?php foreach ($temoignages as $t): ?>
                <div class="col-md-4">
                    <blockquote class="blockquote text-center">
                        <p>"<?= htmlspecialchars($t['contenu']) ?>"</p>
                        <footer class="blockquote-footer"><?= htmlspecialchars($t['nom']) ?>, <?= htmlspecialchars($t['profession'] ?? '') ?></footer>
                    </blockquote>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Aucun témoignage pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Appel à l'action -->
<section class="bg-primary text-white text-center py-5">
    <div class="container">
        <h2>Prêt à prendre votre santé en main ?</h2>
        <p class="lead">Rejoignez MediRDV dès aujourd'hui.</p>
        <a href="<?= BASE_URL ?>/inscription" class="btn btn-light btn-lg">Créer un compte</a>
    </div>
</section>
// Partenaires
<section class="py-5">
    <div class="container text-center">
        <h2 class="mb-4">Nos Partenaires</h2>
        <div class="row justify-content-center align-items-center">
            <?php foreach ($partenaires as $p): ?>
                <div class="col-6 col-md-3 mb-3">
                    <?php if (!empty($p['logo'])): ?>
                        <img src="<?= BASE_URL . '/' . $p['logo'] ?>" alt="<?= htmlspecialchars($p['nom']) ?>" class="img-fluid" style="max-height: 80px;">
                    <?php else: ?>
                        <span class="fw-bold"><?= htmlspecialchars($p['nom']) ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>