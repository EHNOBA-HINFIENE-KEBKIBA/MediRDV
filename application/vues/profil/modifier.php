<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2 class="fw-bold mb-4">✏️ Modifier mon profil</h2>

<div class="card p-4">
    <form action="<?= BASE_URL ?>/profil/mettre-a-jour" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($utilisateur['telephone'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Photo de profil</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
            <?php if (!empty($utilisateur['photo'])): ?>
                <small class="text-muted">Actuelle : <?= $utilisateur['photo'] ?></small>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="<?= BASE_URL ?>/profil" class="btn btn-secondary">Annuler</a>
    </form>
</div>