<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2 class="fw-bold mb-4">📊 Tableau de bord</h2>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card card-stat primary p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Rendez‑vous aujourd'hui</h6>
                    <h3 class="fw-bold"><?= $totalAujourdHui ?></h3>
                </div>
                <i class="bi bi-calendar-check fs-1 text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-stat success p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Patients en attente</h6>
                    <h3 class="fw-bold">--</h3>
                </div>
                <i class="bi bi-people fs-1 text-success"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <a href="<?= BASE_URL ?>/receptionniste/tableau-bord" class="btn btn-primary w-100 py-3">📋 Voir la file d'attente</a>
    </div>
</div>