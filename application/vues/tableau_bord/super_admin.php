<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2 class="fw-bold mb-4">Bonjour <?= htmlspecialchars($nom) ?> 👋</h2>

<div class="row g-4">
    <div class="col-md-3">
        <a href="<?= BASE_URL ?>/admin/etablissements" class="text-decoration-none">
            <div class="card card-stat primary p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Établissements</h6>
                        <h3 class="fw-bold"><?= $stats['etablissements'] ?? 0 ?></h3>
                    </div>
                    <i class="bi bi-building fs-1 text-primary"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?= BASE_URL ?>/admin/utilisateurs" class="text-decoration-none">
            <div class="card card-stat success p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Utilisateurs</h6>
                        <h3 class="fw-bold"><?= ($stats['medecins'] + $stats['patients'] + 1) ?? 0 ?></h3>
                    </div>
                    <i class="bi bi-people fs-1 text-success"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?= BASE_URL ?>/admin/statistiques" class="text-decoration-none">
            <div class="card card-stat warning p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Rendez-vous</h6>
                        <h3 class="fw-bold"><?= $stats['rendezvous'] ?? 0 ?></h3>
                    </div>
                    <i class="bi bi-calendar-check fs-1 text-warning"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?= BASE_URL ?>/admin/paiements" class="text-decoration-none">
            <div class="card card-stat danger p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Paiements</h6>
                        <h3 class="fw-bold"><?= $stats['paiements'] ?? 0 ?></h3>
                    </div>
                    <i class="bi bi-cash-stack fs-1 text-danger"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <a href="<?= BASE_URL ?>/admin/statistiques" class="btn btn-primary w-100 py-3">📊 Voir les statistiques détaillées</a>
    </div>
    <div class="col-md-6">
        <a href="<?= BASE_URL ?>/admin/etablissements" class="btn btn-success w-100 py-3">🏥 Gérer les établissements</a>
    </div>
</div>