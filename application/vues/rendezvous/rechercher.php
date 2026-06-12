<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2>Prendre un rendez-vous</h2>
<p>Sélectionnez un médecin :</p>
<div class="row">
    <?php foreach ($medecins as $medecin): ?>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Dr. <?= htmlspecialchars($medecin['nom'] . ' ' . $medecin['prenom']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($medecin['specialite_nom'] ?? '') ?></p>
                <a href="<?= BASE_URL ?>/prendre-rdv/choisir/<?= $medecin['id_medecin'] ?>" class="btn btn-primary">Choisir</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>