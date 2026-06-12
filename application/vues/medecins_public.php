<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Trouver un médecin' ?></h2>

<!-- Filtres -->
<form method="get" class="row g-3 mb-4">
    <div class="col-md-3">
        <label for="specialite" class="form-label">Spécialité</label>
        <select class="form-select" id="specialite" name="specialite">
            <option value="">Toutes</option>
            <?php foreach ($specialites as $spe): ?>
            <option value="<?= $spe['id_specialite'] ?>" <?= ($filtres['specialite'] ?? '') == $spe['id_specialite'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($spe['nom']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label for="ville" class="form-label">Ville</label>
        <select class="form-select" id="ville" name="ville">
            <option value="">Toutes</option>
            <?php foreach ($villes as $v): ?>
            <option value="<?= $v['id_ville'] ?>" <?= ($filtres['ville'] ?? '') == $v['id_ville'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($v['nom']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <label for="nom" class="form-label">Nom du médecin</label>
        <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($filtres['nom'] ?? '') ?>">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
    </div>
</form>

<!-- Résultats -->
<div class="row">
    <?php if (empty($medecins)): ?>
        <p>Aucun médecin trouvé.</p>
    <?php else: ?>
        <?php foreach ($medecins as $med): ?>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Dr. <?= htmlspecialchars($med['nom'] . ' ' . $med['prenom']) ?></h5>
                    <p class="card-text">
                        <strong>Spécialité :</strong> <?= htmlspecialchars($med['specialite_nom']) ?><br>
                        <strong>Établissement :</strong> <?= htmlspecialchars($med['etablissement_nom'] ?? 'Non renseigné') ?><br>
                        <strong>Ville :</strong> <?= htmlspecialchars($med['ville_nom'] ?? '') ?>
                    </p>
                    <?php if (isset($_SESSION['utilisateur_id'])): ?>
                        <a href="<?= BASE_URL ?>/prendre-rdv/choisir/<?= $med['id_medecin'] ?>" class="btn btn-sm btn-primary">Prendre rendez-vous</a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/connexion" class="btn btn-sm btn-outline-primary">Connectez-vous pour prendre RDV</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>