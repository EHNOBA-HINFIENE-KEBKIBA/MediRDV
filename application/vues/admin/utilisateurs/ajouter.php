<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Ajouter un utilisateur' ?></h2>

<form action="<?= BASE_URL ?>/admin/enregistrer-ajout-utilisateur" method="post">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" required>
        </div>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="mot_de_passe" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
    </div>
    <div class="mb-3">
        <label for="telephone" class="form-label">Téléphone</label>
        <input type="text" class="form-control" id="telephone" name="telephone">
    </div>
    <div class="mb-3">
        <label for="id_role" class="form-label">Rôle</label>
        <select class="form-select" id="id_role" name="id_role" required>
            <option value="">Choisir...</option>
            <?php foreach ($roles as $role): ?>
            <option value="<?= $role['id_role'] ?>"><?= $role['libelle'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="id_etablissement" class="form-label">Établissement</label>
        <select class="form-select" id="id_etablissement" name="id_etablissement">
            <option value="">Aucun</option>
            <?php foreach ($etablissements as $etab): ?>
            <option value="<?= $etab['id_etablissement'] ?>"><?= htmlspecialchars($etab['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Champs spécifiques médecin (affichés conditionnellement via JS) -->
    <div id="champsMedecin" style="display:none;">
        <hr><h5>Informations médecin</h5>
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
                    <option value="">Choisir...</option>
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
    </div>

    <button type="submit" class="btn btn-success">Enregistrer</button>
    <a href="<?= BASE_URL ?>/admin/utilisateurs" class="btn btn-secondary">Annuler</a>
</form>

<script>
    document.getElementById('id_role').addEventListener('change', function() {
        var role = this.options[this.selectedIndex].text;
        document.getElementById('champsMedecin').style.display = (role === 'Médecin') ? 'block' : 'none';
    });
</script>