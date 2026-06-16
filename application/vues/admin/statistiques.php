<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Statistiques globales' ?></h2>


<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-stat primary p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Médecins</h6>
                    <h3 class="fw-bold"><?= $stats['medecins'] ?? 0 ?></h3>
                </div>
                <i class="bi bi-person-badge fs-1 text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat success p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Patients</h6>
                    <h3 class="fw-bold"><?= $stats['patients'] ?? 0 ?></h3>
                </div>
                <i class="bi bi-people fs-1 text-success"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat warning p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Établissements</h6>
                    <h3 class="fw-bold"><?= $stats['etablissements'] ?? 0 ?></h3>
                </div>
                <i class="bi bi-building fs-1 text-warning"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat danger p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Rendez-vous</h6>
                    <h3 class="fw-bold"><?= $stats['rendezvous'] ?? 0 ?></h3>
                </div>
                <i class="bi bi-calendar-check fs-1 text-danger"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat info p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Paiements</h6>
                    <h3 class="fw-bold"><?= $stats['paiements'] ?? 0 ?></h3>
                </div>
                <i class="bi bi-cash-stack fs-1 text-info"></i>
            </div>
        </div>
    </div>
    <div><a href="<?= BASE_URL ?>/admin/rapport-pdf" class="btn btn-outline-danger mt-3">📄 Télécharger le rapport PDF</a></div>
</div>