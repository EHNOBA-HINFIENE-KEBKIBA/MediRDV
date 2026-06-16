<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .card-connexion {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        background: linear-gradient(135deg, #ffffff 0%, #2fb61e81 100%);
        padding: 2rem;
    }
    .form-control {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.15);
    }
    .btn-primary {
        border-radius: 8px;
        padding: 0.75rem;
        font-weight: 600;
        transition: transform 0.2s;
    }
    .btn-primary:hover {
        transform: scale(1.02);
    }
    .page-wrapper {
        background-color: #f4f6f9;
        padding: 2rem 0;
        min-height: 100vh;
    }
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 38px;
        cursor: pointer;
        color: #6c757d;
        z-index: 10;
    }
    .password-wrapper {
        position: relative;
    }
</style>

<div class="page-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-connexion">
                <h2 class="mb-4 text-center"><i class="bi bi-box-arrow-in-right me-2 text-primary"></i>Connexion</h2>

                <?php if (isset($erreur) && $erreur == 'identifiants'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        Email ou mot de passe incorrect.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($erreur) && $erreur == 'csrf'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        Session expirée, veuillez réessayer.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['succes_inscription'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $_SESSION['succes_inscription'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['succes_inscription']); ?>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/traiter-connexion" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3 password-wrapper">
                        <label for="mot_de_passe" class="form-label fw-semibold">Mot de passe</label>
                        <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                        <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                    </div>
                    <?= Securite::csrfField() ?>
                    <button type="submit" class="btn btn-primary w-100 mt-2">Se connecter</button>
                </form>
                <p class="mt-3 text-center">
                    <a href="#" class="text-muted small">Mot de passe oublié ?</a>
                </p>
                <p class="text-center">Pas encore de compte ? <a href="<?= BASE_URL ?>/inscription">Inscrivez-vous</a></p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('mot_de_passe');
    const icon = this;
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    }
});
</script>