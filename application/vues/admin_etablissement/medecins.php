<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Médecins de l\'établissement' ?></h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<!-- Barre de recherche + bouton ajout -->
<div class="row mb-3">
    <div class="col-md-8">
        <form method="get" class="d-flex">
            <input type="text" name="recherche" class="form-control me-2" placeholder="Rechercher par nom, prénom ou email" value="<?= htmlspecialchars($filtre ?? '') ?>">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
            <?php if (!empty($filtre)): ?>
                <a href="<?= BASE_URL ?>/admin-etablissement/medecins" class="btn btn-outline-secondary ms-2">Annuler</a>
            <?php endif; ?>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?= BASE_URL ?>/admin-etablissement/ajouter-medecin" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i>Ajouter un médecin</a>
    </div>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Spécialité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($medecins as $med): ?>
        <tr>
            <td>Dr. <?= htmlspecialchars($med['nom'] . ' ' . $med['prenom']) ?></td>
            <td><?= htmlspecialchars($med['email']) ?></td>
            <td><?= htmlspecialchars($med['specialite_nom'] ?? '') ?></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="<?= BASE_URL ?>/admin-etablissement/modifier-medecin/<?= $med['id_medecin'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier disponibilités"><i class="bi bi-clock"></i></a>
                    <a href="<?= BASE_URL ?>/admin-etablissement/supprimer-medecin/<?= $med['id_medecin'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce médecin ?')" title="Supprimer"><i class="bi bi-trash"></i></a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>