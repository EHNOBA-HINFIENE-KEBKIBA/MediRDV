<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2 class="fw-bold mb-4">🔒 Changer mon mot de passe</h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<div class="card p-4">
    <form action="<?= BASE_URL ?>/profil/enregistrer-mot-de-passe" method="post">
        <div class="mb-3">
            <label for="ancien_mot_de_passe" class="form-label">Ancien mot de passe</label>
            <input type="password" class="form-control" id="ancien_mot_de_passe" name="ancien_mot_de_passe" required>
        </div>
        <div class="mb-3">
            <label for="nouveau_mot_de_passe" class="form-label">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required minlength="6">
        </div>
        <div class="mb-3">
            <label for="confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
            <input type="password" class="form-control" id="confirmation" name="confirmation" required>
        </div>
        <button type="submit" class="btn btn-warning">Modifier le mot de passe</button>
        <a href="<?= BASE_URL ?>/profil" class="btn btn-secondary">Annuler</a>
    </form>
</div>