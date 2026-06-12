<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <h2 class="mb-4">Connexion</h2>

        <?php if (isset($erreur) && $erreur == 'identifiants'): ?>
            <div class="alert alert-danger">Email ou mot de passe incorrect.</div>
        <?php endif; ?>

        <?php if (isset($_SESSION['succes_inscription'])): ?>
            <div class="alert alert-success"><?= $_SESSION['succes_inscription'] ?></div>
            <?php unset($_SESSION['succes_inscription']); ?>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/traiter-connexion" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
        <p class="mt-3 text-center">Pas encore de compte ? <a href="<?= BASE_URL ?>/inscription">Inscrivez-vous</a></p>
    </div>
</div>