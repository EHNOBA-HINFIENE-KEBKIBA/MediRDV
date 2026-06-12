<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2 class="fw-bold mb-4">🏥 Tableau de bord</h2>
<div class="row g-4">
    <div class="col-md-3">
        <div class="card card-stat primary p-3">
            <div class="d-flex justify-content-between"><div><h6>Médecins</h6><h3><?= $stats['medecins'] ?></h3></div><i class="bi bi-person-badge fs-1"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat success p-3">
            <div class="d-flex justify-content-between"><div><h6>Réceptionnistes</h6><h3><?= $stats['receptionnistes'] ?></h3></div><i class="bi bi-person-lines-fill fs-1"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat warning p-3">
            <div class="d-flex justify-content-between"><div><h6>Rendez-vous</h6><h3><?= $stats['rendezvous'] ?></h3></div><i class="bi bi-calendar-check fs-1"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat danger p-3">
            <div class="d-flex justify-content-between"><div><h6>Paiements</h6><h3><?= $stats['paiements'] ?></h3></div><i class="bi bi-cash-stack fs-1"></i></div>
        </div>
    </div>
</div>