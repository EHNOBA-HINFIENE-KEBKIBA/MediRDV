<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .info-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 28px rgba(0,0,0,0.06);
        padding: 2rem;
        height: 100%;
    }
    .info-card h4 {
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1e293b;
    }
    .info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        color: #475569;
    }
    .info-item i {
        color: #0d6efd;
        width: 20px;
        text-align: center;
    }
    .admin-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #0d6efd;
        margin-bottom: 0.75rem;
    }
    .admin-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #0d6efd;
        margin-bottom: 0.75rem;
        font-size: 2rem;
        color: #6c757d;
    }
    .logo-img {
        max-width: 200px;
        max-height: 150px;
        object-fit: contain;
    }
</style>

<h2 class="fw-bold mb-4"><i class="bi bi-building me-2 text-primary"></i><?= $titre ?? 'Mon établissement' ?></h2>

<div class="row g-4">
    <!-- Bloc Établissement -->
    <div class="col-lg-7">
        <div class="info-card">
            <h4><i class="bi bi-hospital me-2"></i>Informations de l'établissement</h4>
            <?php if (!empty($etablissement['logo'])): ?>
                <img src="<?= BASE_URL . '/' . $etablissement['logo'] ?>" class="logo-img mb-3" alt="Logo">
            <?php else: ?>
                <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="width:200px; height:120px;">
                    <i class="bi bi-building fs-1 text-secondary"></i>
                </div>
            <?php endif; ?>
            <div class="info-item"><i class="bi bi-tag"></i> <strong><?= htmlspecialchars($etablissement['nom']) ?></strong> (<?= $etablissement['type'] ?>)</div>
            <div class="info-item"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($etablissement['adresse'] ?? 'Non renseignée') ?></div>
            <div class="info-item"><i class="bi bi-telephone"></i> <?= htmlspecialchars($etablissement['telephone'] ?? 'Non renseigné') ?></div>
            <div class="info-item"><i class="bi bi-envelope"></i> <?= htmlspecialchars($etablissement['email'] ?? 'Non renseigné') ?></div>
            <div class="info-item"><i class="bi bi-clock"></i> <?= htmlspecialchars($etablissement['horaires'] ?? 'Non renseignés') ?></div>
            <div class="info-item"><i class="bi bi-map"></i> <?= htmlspecialchars($etablissement['coord_gps'] ?? 'Non renseignées') ?></div>
        </div>
    </div>

    <!-- Bloc Administrateur -->
    <div class="col-lg-5">
        <div class="info-card text-center">
            <h4><i class="bi bi-person-badge me-2"></i>Administrateur</h4>
            <?php if (!empty($admin)): ?>
                <div class="mt-3">
                    <?php if (!empty($admin['photo'])): ?>
                        <img src="<?= BASE_URL . '/' . $admin['photo'] ?>" class="admin-avatar" alt="Photo admin">
                    <?php else: ?>
                        <div class="admin-placeholder"><i class="bi bi-person-fill"></i></div>
                    <?php endif; ?>
                    <h5 class="mb-1"><?= htmlspecialchars($admin['nom'] . ' ' . $admin['prenom']) ?></h5>
                    <div class="info-item justify-content-center"><i class="bi bi-envelope"></i> <?= htmlspecialchars($admin['email']) ?></div>
                    <div class="info-item justify-content-center"><i class="bi bi-telephone"></i> <?= htmlspecialchars($admin['telephone'] ?? 'Non renseigné') ?></div>
                </div>
            <?php else: ?>
                <p class="text-muted mt-3">Aucun administrateur trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
</div>