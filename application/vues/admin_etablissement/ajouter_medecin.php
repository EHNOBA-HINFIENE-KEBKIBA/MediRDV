<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Ajouter un médecin' ?></h2>

<div class="card p-4">
    <form action="<?= BASE_URL ?>/admin-etablissement/enregistrer-ajout-medecin" method="post">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="telephone" name="telephone">
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="sexe" class="form-label">Sexe</label>
                <select class="form-select" id="sexe" name="sexe">
                    <option value="M">Homme</option>
                    <option value="F">Femme</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="id_specialite" class="form-label">Spécialité</label>
                <select class="form-select" id="id_specialite" name="id_specialite">
                    <option value="">Aucune</option>
                    <?php foreach ($specialites as $spe): ?>
                    <option value="<?= $spe['id_specialite'] ?>"><?= htmlspecialchars($spe['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="experience" class="form-label">Expérience (années)</label>
                <input type="number" class="form-control" id="experience" name="experience" value="0" min="0">
            </div>
        </div>
        <div class="mb-3">
            <label for="diplomes" class="form-label">Diplômes</label>
            <textarea class="form-control" id="diplomes" name="diplomes" rows="2"></textarea>
        </div>
        <div class="mb-3">
            <label for="mot_de_passe" class="form-label">Mot de passe <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required minlength="6">
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="<?= BASE_URL ?>/admin-etablissement/medecins" class="btn btn-secondary">Annuler</a>
    </form>
</div>