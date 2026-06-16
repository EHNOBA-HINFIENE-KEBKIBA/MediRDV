<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    body {
        background-color: #f4f6f9;
    }
    .card-inscription {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        /* Dégradé de fond : blanc vers un bleu très pâle */
        background: linear-gradient(135deg, #ffffffee 0%, #2fb61e81 100%);
        padding: 2rem;
    }
    .form-control, .form-select {
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
</style>

<div class="page-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-inscription">
                <h2 class="mb-4 text-center"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Créer un compte</h2>

                <?php if (!empty($erreurs)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            <?php foreach ($erreurs as $err): ?>
                                <li><?= $err ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($succes)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $succes ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/traiter-inscription" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($anciennes['nom'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($anciennes['prenom'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($anciennes['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label fw-semibold">Téléphone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($anciennes['telephone'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="date_naissance" class="form-label fw-semibold">Date de naissance</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($anciennes['date_naissance'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sexe" class="form-label fw-semibold">Sexe</label>
                            <select class="form-select" id="sexe" name="sexe">
                                <option value="">Choisir...</option>
                                <option value="M" <?= ($anciennes['sexe'] ?? '') == 'M' ? 'selected' : '' ?>>Homme</option>
                                <option value="F" <?= ($anciennes['sexe'] ?? '') == 'F' ? 'selected' : '' ?>>Femme</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pays" class="form-label fw-semibold">Pays</label>
                            <input type="text" class="form-control" id="pays" name="pays" value="<?= htmlspecialchars($anciennes['pays'] ?? '') ?>" placeholder="Ex: Cameroun, Tchad...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ville" class="form-label fw-semibold">Ville</label>
                            <input type="text" class="form-control" id="ville" name="ville" value="<?= htmlspecialchars($anciennes['ville'] ?? '') ?>" placeholder="Votre ville">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mot_de_passe" class="form-label fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required minlength="6">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirmation" class="form-label fw-semibold">Confirmer le mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirmation" name="confirmation" required>
                        </div>
                    </div>
                    <?= Securite::csrfField() ?>
                    <button type="submit" class="btn btn-primary w-100 mt-2">S'inscrire</button>
                </form>
                <p class="mt-3 text-center">Déjà un compte ? <a href="<?= BASE_URL ?>/connexion">Connectez-vous</a></p>
            </div>
        </div>
    </div>
</div>