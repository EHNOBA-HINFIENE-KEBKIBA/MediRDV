<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<div class="row mt-4">
    <div class="col-12">
        <h2 class="fw-bold mb-4">Bonjour, <?= htmlspecialchars($nom) ?> 👋</h2>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card card-stat primary p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Rendez-vous à venir</h6>
                    <h3 class="fw-bold"><?= $rdvAVenir ?? 0 ?></h3>
                </div>
                <i class="bi bi-calendar-check fs-1 text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat success p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Consultations terminées</h6>
                    <h3 class="fw-bold"><?= $rdvTermines ?? 0 ?></h3>
                </div>
                <i class="bi bi-clipboard-check fs-1 text-success"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat warning p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Paiements effectués</h6>
                    <h3 class="fw-bold"><?= $totalPaiements ?? 0 ?></h3>
                </div>
                <i class="bi bi-credit-card fs-1 text-warning"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat danger p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Documents</h6>
                    <h3 class="fw-bold"><?= $documents ?? 0 ?></h3>
                </div>
                <i class="bi bi-file-earmark-text fs-1 text-danger"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <a href="<?= BASE_URL ?>/mes-rendezvous" class="btn btn-primary w-100 py-3">📅 Mes rendez-vous</a>
    </div>
    <div class="col-md-6">
        <a href="<?= BASE_URL ?>/prendre-rdv" class="btn btn-success w-100 py-3">➕ Prendre un rendez-vous</a>
    </div>
</div>