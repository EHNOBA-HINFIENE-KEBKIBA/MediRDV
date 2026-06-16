<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>

<!-- Styles spécifiques à cette page (déjà inclus ou à ajouter dans style.css) -->
<style>
.hero {
    background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
}
</style>

<!-- HERO AMÉLIORÉ -->
<section class="hero py-5 text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Bienvenue sur MediRDV</h1>
                <p class="lead mb-3">La plateforme qui simplifie votre parcours de santé.</p>
                <p class="mb-4">
                    MediRDV vous permet de rechercher des médecins, cliniques et hôpitaux,
                    de prendre rendez-vous en ligne 24h/24, de consulter à distance par vidéo
                    et de gérer vos documents médicaux en toute sécurité.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="<?= BASE_URL ?>/inscription" class="btn btn-light btn-lg">
                        <i class="bi bi-person-plus"></i> S'inscrire gratuitement
                    </a>
                    <a href="<?= BASE_URL ?>/a-propos" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-info-circle"></i> En savoir plus
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0">
                <img src="<?= BASE_URL ?>/public/assets/images/hero-medical.png"
                     class="img-fluid" alt="MediRDV">
            </div>
        </div>
    </div>
</section>

<!-- STATISTIQUES DYNAMIQUES -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="text-primary fw-bold"><?= $stats['medecins'] ?? 0 ?>+</h2>
                        <p>Médecins</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="text-success fw-bold"><?= $stats['etablissements'] ?? 0 ?>+</h2>
                        <p>Établissements</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="text-warning fw-bold"><?= $stats['patients'] ?? 0 ?>+</h2>
                        <p>Patients</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="text-danger fw-bold"><?= $stats['rendezvous'] ?? 0 ?>+</h2>
                        <p>Rendez-vous</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Nos Services</h2>
            <p>Une plateforme complète pour votre santé.</p>
        </div>
        <div class="row g-4">
            <?php
            $services = [
                ['calendar-check', 'Prise de rendez-vous'],
                ['camera-video', 'Téléconsultation'],
                ['credit-card', 'Paiement en ligne'],
                ['qr-code', 'QR Code'],
                ['bell', 'Notifications SMS'],
                ['folder2-open', 'Documents médicaux']
            ];
            foreach($services as $service):
            ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body">
                        <i class="bi bi-<?= $service[0] ?> fs-1 text-primary"></i>
                        <h5 class="mt-3"><?= $service[1] ?></h5>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SPÉCIALITÉS DYNAMIQUES -->
<?php if (!empty($specialites)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Spécialités</h2>
        </div>
        <div class="row g-4">
            <?php foreach($specialites as $spe): ?>
            <div class="col-md-2 col-6">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-heart-pulse fs-1 text-primary"></i>
                        <p class="mt-2 mb-0"><?= htmlspecialchars($spe['nom']) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- TÉLÉCONSULTATION -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <img src="<?= BASE_URL ?>/public/assets/images/teleconsultation.jpg" class="img-fluid rounded shadow" alt="Téléconsultation">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold">Consultez votre médecin depuis chez vous</h2>
                <p class="lead">Grâce à notre système de téléconsultation sécurisé, échangez avec votre médecin en toute simplicité.</p>
                <a href="#" class="btn btn-primary">En savoir plus</a>
            </div>
        </div>
    </div>
</section>

<!-- COMMENT ÇA MARCHE -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Comment ça marche ?</h2>
        </div>
        <div class="row text-center">
            <div class="col-md-3"><i class="bi bi-person-plus fs-1 text-primary"></i><h5>Créer un compte</h5></div>
            <div class="col-md-3"><i class="bi bi-search fs-1 text-primary"></i><h5>Choisir un médecin</h5></div>
            <div class="col-md-3"><i class="bi bi-calendar-check fs-1 text-primary"></i><h5>Réserver</h5></div>
            <div class="col-md-3"><i class="bi bi-check-circle fs-1 text-primary"></i><h5>Consulter</h5></div>
        </div>
    </div>
</section>

<!-- TÉMOIGNAGES DYNAMIQUES -->
<?php if (!empty($temoignages)): ?>
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Témoignages</h2>
        </div>
        <div class="row">
            <?php foreach($temoignages as $t): ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3 text-warning">
                            <?= str_repeat('★', $t['note']) ?><?= str_repeat('☆', 5 - $t['note']) ?>
                        </div>
                        <p><?= htmlspecialchars($t['contenu']) ?></p>
                        <strong><?= htmlspecialchars($t['nom']) ?></strong>
                        <?php if (!empty($t['profession'])): ?>
                            <br><small class="text-muted"><?= htmlspecialchars($t['profession']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- BLOG DYNAMIQUE -->
<?php if (!empty($articles)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Derniers Articles</h2>
        </div>
        <div class="row">
            <?php foreach($articles as $article): ?>
            <div class="col-md-4">
                <div class="card border-0 shadow h-100">
                    <?php if (!empty($article['image'])): ?>
                        <img src="<?= BASE_URL . '/' . $article['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($article['titre']) ?>">
                    <?php else: ?>
                        <img src="<?= BASE_URL ?>/public/assets/images/blog.jpg" class="card-img-top" alt="Blog">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($article['titre']) ?></h5>
                        <p class="card-text flex-grow-1"><?= substr(strip_tags($article['contenu']), 0, 100) ?>...</p>
                        <a href="<?= BASE_URL ?>/blog/article/<?= $article['id_article'] ?>" class="btn btn-outline-primary mt-auto">Lire</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- FAQ DYNAMIQUE -->
<?php if (!empty($faqs)): ?>
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Questions Fréquentes</h2>
        </div>
        <div class="accordion" id="faq">
            <?php foreach($faqs as $index => $faq): ?>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button <?= $index==0?'':'collapsed' ?>" data-bs-toggle="collapse" data-bs-target="#faq<?= $index ?>">
                        <?= htmlspecialchars($faq['question']) ?>
                    </button>
                </h2>
                <div id="faq<?= $index ?>" class="accordion-collapse collapse <?= $index==0?'show':'' ?>" data-bs-parent="#faq">
                    <div class="accordion-body"><?= nl2br(htmlspecialchars($faq['reponse'])) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA FINAL -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <h2 class="fw-bold">Commencez dès aujourd'hui avec MediRDV</h2>
        <p class="lead">Prenez rendez-vous en ligne avec les meilleurs professionnels de santé.</p>
        <a href="<?= BASE_URL ?>/inscription" class="btn btn-light btn-lg">Créer un compte</a>
    </div>
</section>