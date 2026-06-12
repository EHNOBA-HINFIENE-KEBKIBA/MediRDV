<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2 class="fw-bold mb-4">Bonjour <?= htmlspecialchars($nom) ?> 👋</h2>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card card-stat primary p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">RDV aujourd'hui</h6>
                    <h3 class="fw-bold"><?= $rdvAujourdhui ?></h3>
                </div>
                <i class="bi bi-calendar-check fs-1 text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat warning p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">En attente</h6>
                    <h3 class="fw-bold"><?= $enAttente ?></h3>
                </div>
                <i class="bi bi-hourglass-split fs-1 text-warning"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat success p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Confirmés</h6>
                    <h3 class="fw-bold"><?= $confirmes ?></h3>
                </div>
                <i class="bi bi-check-circle fs-1 text-success"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat danger p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Terminés</h6>
                    <h3 class="fw-bold"><?= $termines ?></h3>
                </div>
                <i class="bi bi-flag fs-1 text-danger"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <a href="<?= BASE_URL ?>/receptionniste/tableau-bord" class="btn btn-primary w-100 py-3">📋 File d'attente</a>
    </div>
    <div class="col-md-6">
        <a href="<?= BASE_URL ?>/receptionniste/creer-rdv" class="btn btn-success w-100 py-3">➕ Nouveau RDV</a>
    </div>
</div>