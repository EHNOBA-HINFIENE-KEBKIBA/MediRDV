<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Ajouter un établissement' ?></h2>

<form action="<?= BASE_URL ?>/admin/enregistrer-ajout-etablissement" method="post">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" required>
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <select class="form-select" id="type" name="type" required>
            <option value="">Choisir...</option>
            <?php foreach ($types as $type): ?>
            <option value="<?= $type ?>"><?= $type ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <label for="adresse" class="form-label">Adresse</label>
        <input type="text" class="form-control" id="adresse" name="adresse">
    </div>
    <div class="mb-3">
        <label for="telephone" class="form-label">Téléphone</label>
        <input type="text" class="form-control" id="telephone" name="telephone">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="coord_gps" class="form-label">Coordonnées GPS</label>
        <input type="text" class="form-control" id="coord_gps" name="coord_gps" placeholder="lat,lng">
    </div>
    <div class="mb-3">
        <label for="horaires" class="form-label">Horaires</label>
        <input type="text" class="form-control" id="horaires" name="horaires" placeholder="ex: Lun-Ven 8h-18h">
    </div>
    <div class="mb-3">
        <label for="id_ville" class="form-label">Ville</label>
        <select class="form-select" id="id_ville" name="id_ville" required>
            <option value="">Choisir...</option>
            <?php foreach ($villes as $ville): ?>
            <option value="<?= $ville['id_ville'] ?>"><?= htmlspecialchars($ville['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <hr>
<h5>Compte Administrateur de l'établissement</h5>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="admin_nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="admin_nom" name="admin_nom" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="admin_prenom" class="form-label">Prénom</label>
        <input type="text" class="form-control" id="admin_prenom" name="admin_prenom" required>
    </div>
</div>
<div class="mb-3">
    <label for="admin_email" class="form-label">Email</label>
    <input type="email" class="form-control" id="admin_email" name="admin_email" required>
</div>
<div class="mb-3">
    <label for="admin_mot_de_passe" class="form-label">Mot de passe</label>
    <input type="password" class="form-control" id="admin_mot_de_passe" name="admin_mot_de_passe" required>
</div>
<div class="mb-3">
    <label for="admin_telephone" class="form-label">Téléphone</label>
    <input type="text" class="form-control" id="admin_telephone" name="admin_telephone">
</div>
    <button type="submit" class="btn btn-success">Enregistrer</button>
    <a href="<?= BASE_URL ?>/admin/etablissements" class="btn btn-secondary">Annuler</a>
</form>