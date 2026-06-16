<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2 class="fw-bold mb-4"><i class="bi bi-people me-2 text-primary"></i><?= $titre ?? 'Mes patients' ?></h2>

<!-- Barre de recherche -->
<form method="get" class="row g-3 mb-4">
    <div class="col-md-6">
        <input type="text" name="recherche" class="form-control" placeholder="Rechercher par nom, prénom ou email" value="<?= htmlspecialchars($recherche ?? '') ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrer</button>
    </div>
    <?php if (!empty($recherche)): ?>
    <div class="col-md-2">
        <a href="<?= BASE_URL ?>/medecin/patients" class="btn btn-outline-secondary w-100">Annuler</a>
    </div>
    <?php endif; ?>
</form>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Date naissance</th>
                <th>Groupe sanguin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($patients as $pat): ?>
            <tr>
                <td><?= htmlspecialchars($pat['nom'] . ' ' . $pat['prenom']) ?></td>
                <td><?= htmlspecialchars($pat['email']) ?></td>
                <td><?= htmlspecialchars($pat['telephone'] ?? '-') ?></td>
                <td><?= $pat['date_naissance'] ? date('d/m/Y', strtotime($pat['date_naissance'])) : '-' ?></td>
                <td><?= htmlspecialchars($pat['groupe_sanguin'] ?? '-') ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/medecin/dossier-patient/<?= $pat['id_utilisateur'] ?>" class="btn btn-sm btn-outline-primary" title="Voir le dossier médical">
                        <i class="bi bi-folder2-open me-1"></i>Dossier
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>