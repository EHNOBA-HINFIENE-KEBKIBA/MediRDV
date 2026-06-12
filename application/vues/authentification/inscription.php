<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <h2 class="mb-4">Inscription</h2>

        <?php if (!empty($erreurs)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($erreurs as $err): ?>
                        <li><?= $err ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/traiter-inscription" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($anciennes['nom'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($anciennes['prenom'] ?? '') ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($anciennes['email'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($anciennes['telephone'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required minlength="6">
            </div>
            <div class="mb-3">
                <label for="confirmation" class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="confirmation" name="confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
        </form>
        <p class="mt-3 text-center">Déjà un compte ? <a href="<?= BASE_URL ?>/connexion">Connectez-vous</a></p>
    </div>
</div>