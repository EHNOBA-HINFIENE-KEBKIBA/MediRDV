<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .contact-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        padding: 2rem;
    }
    .info-contact i {
        color: #0d6efd;
        width: 24px;
    }
</style>

<div class="container py-5">
    <h1 class="fw-bold mb-2 text-center"><i class="bi bi-envelope-paper me-2 text-primary"></i><?= $titre ?? 'Contactez-nous' ?></h1>
    <p class="text-muted text-center mb-5">Une question ? N'hésitez pas à nous écrire.</p>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="contact-card h-100">
                <h4 class="mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Nos coordonnées</h4>
                <p class="info-contact"><i class="bi bi-telephone-fill me-2"></i> +235 XX XX XX XX</p>
                <p class="info-contact"><i class="bi bi-envelope-fill me-2"></i> contact@medirdv.com</p>
                <p class="info-contact"><i class="bi bi-geo-alt-fill me-2"></i> N'Djamena, Tchad</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="btn btn-outline-primary btn-sm"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-outline-primary btn-sm"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" class="btn btn-outline-primary btn-sm"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="contact-card">
                <?php if (!empty($succes)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $succes ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($erreurs)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            <?php foreach ($erreurs as $err): ?>
                                <li><?= $err ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/traiter-contact" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($anciens['nom'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($anciens['email'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="sujet" class="form-label fw-semibold">Sujet <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sujet" name="sujet" value="<?= htmlspecialchars($anciens['sujet'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="message" name="message" rows="5" required><?= htmlspecialchars($anciens['message'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send me-1"></i>Envoyer</button>
                </form>
            </div>
        </div>
    </div>
</div>