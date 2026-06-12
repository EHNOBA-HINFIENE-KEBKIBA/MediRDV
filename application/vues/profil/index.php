<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2 class="fw-bold mb-4">👤 Mon profil</h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<div class="card p-4">
    <div class="row">
        <div class="col-md-4 text-center">
            <?php if (!empty($utilisateur['photo'])): ?>
                <img src="<?= BASE_URL . '/' . $utilisateur['photo'] ?>" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
            <?php else: ?>
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px; font-size: 4rem;">
                    <i class="bi bi-person-circle text-secondary"></i>
                </div>
            <?php endif; ?>
            <h5 class="mt-3"><?= htmlspecialchars($utilisateur['nom'] . ' ' . $utilisateur['prenom']) ?></h5>
        </div>
        <div class="col-md-8">
            <dl class="row">
                <dt class="col-sm-4">Email</dt>
                <dd class="col-sm-8"><?= htmlspecialchars($utilisateur['email']) ?></dd>
                <dt class="col-sm-4">Téléphone</dt>
                <dd class="col-sm-8"><?= htmlspecialchars($utilisateur['telephone'] ?? 'Non renseigné') ?></dd>
                <dt class="col-sm-4">Rôle</dt>
                <dd class="col-sm-8"><?= htmlspecialchars($utilisateur['role_libelle'] ?? '') ?></dd>
            </dl>
            <div class="mt-3">
                <a href="<?= BASE_URL ?>/profil/modifier" class="btn btn-primary me-2">✏️ Modifier le profil</a>
                <a href="<?= BASE_URL ?>/profil/changer-mot-de-passe" class="btn btn-outline-warning">🔒 Changer le mot de passe</a>
            </div>
        </div>
    </div>
</div>