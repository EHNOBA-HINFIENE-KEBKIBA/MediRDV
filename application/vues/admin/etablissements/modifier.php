<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Modifier un établissement' ?></h2>

<form action="<?= BASE_URL ?>/admin/enregistrer-modification-etablissement" method="post">
    <input type="hidden" name="id_etablissement" value="<?= $etablissement['id_etablissement'] ?>">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($etablissement['nom']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <select class="form-select" id="type" name="type" required>
            <?php foreach ($types as $type): ?>
            <option value="<?= $type ?>" <?= $etablissement['type'] == $type ? 'selected' : '' ?>><?= $type ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($etablissement['description']) ?></textarea>
    </div>
    <div class="mb-3">
        <label for="adresse" class="form-label">Adresse</label>
        <input type="text" class="form-control" id="adresse" name="adresse" value="<?= htmlspecialchars($etablissement['adresse']) ?>">
    </div>
    <div class="mb-3">
        <label for="telephone" class="form-label">Téléphone</label>
        <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($etablissement['telephone']) ?>">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($etablissement['email']) ?>">
    </div>
    <div class="mb-3">
        <label for="coord_gps" class="form-label">Coordonnées GPS</label>
        <input type="text" class="form-control" id="coord_gps" name="coord_gps" value="<?= htmlspecialchars($etablissement['coord_gps']) ?>">
    </div>
    <div class="mb-3">
        <label for="horaires" class="form-label">Horaires</label>
        <input type="text" class="form-control" id="horaires" name="horaires" value="<?= htmlspecialchars($etablissement['horaires']) ?>">
    </div>
    <div class="mb-3">
        <label for="id_ville" class="form-label">Ville</label>
        <select class="form-select" id="id_ville" name="id_ville" required>
            <?php foreach ($villes as $ville): ?>
            <option value="<?= $ville['id_ville'] ?>" <?= $etablissement['id_ville'] == $ville['id_ville'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($ville['nom']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Enregistrer</button>
    <a href="<?= BASE_URL ?>/admin/etablissements" class="btn btn-secondary">Annuler</a>
</form>