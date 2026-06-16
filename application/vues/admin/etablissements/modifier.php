<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .logo-preview {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        object-fit: contain;
        border: 2px solid #dee2e6;
        background: #f8f9fa;
        margin-bottom: 0.5rem;
    }
    .logo-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #adb5bd;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
</style>

<h2><?= $titre ?? 'Modifier un établissement' ?></h2>

<div class="card p-4 shadow-sm rounded-4">
    <form action="<?= BASE_URL ?>/admin/enregistrer-modification-etablissement" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_etablissement" value="<?= $etablissement['id_etablissement'] ?>">

        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="nom" class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($etablissement['nom']) ?>" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <?php foreach ($types as $type): ?>
                            <option value="<?= $type ?>" <?= $etablissement['type'] == $type ? 'selected' : '' ?>><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="id_ville" class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_ville" name="id_ville" required>
                            <?php foreach ($villes as $ville): ?>
                            <option value="<?= $ville['id_ville'] ?>" <?= $etablissement['id_ville'] == $ville['id_ville'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ville['nom']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($etablissement['description']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label fw-semibold">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse" value="<?= htmlspecialchars($etablissement['adresse']) ?>">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telephone" class="form-label fw-semibold">Téléphone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($etablissement['telephone']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($etablissement['email']) ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="coord_gps" class="form-label fw-semibold">Coordonnées GPS</label>
                        <input type="text" class="form-control" id="coord_gps" name="coord_gps" value="<?= htmlspecialchars($etablissement['coord_gps']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="horaires" class="form-label fw-semibold">Horaires</label>
                        <input type="text" class="form-control" id="horaires" name="horaires" value="<?= htmlspecialchars($etablissement['horaires']) ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <label class="form-label fw-semibold">Logo de l'établissement</label>
                <div id="logoContainer">
                    <?php if (!empty($etablissement['logo'])): ?>
                        <img src="<?= BASE_URL . '/' . $etablissement['logo'] ?>" class="logo-preview" id="logoPreview" alt="Logo actuel">
                    <?php else: ?>
                        <div class="logo-placeholder" id="logoPlaceholder">
                            <i class="bi bi-building fs-1"></i>
                        </div>
                        <img id="logoPreview" class="logo-preview" style="display:none;" alt="Aperçu du logo">
                    <?php endif; ?>
                </div>
                <input type="file" class="form-control mt-2" id="logo" name="logo" accept="image/*" onchange="previewLogo(event)">
                <small class="text-muted">Laissez vide pour conserver l'actuel</small>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success flex-grow-1"><i class="bi bi-check2-circle me-1"></i>Enregistrer</button>
            <a href="<?= BASE_URL ?>/admin/etablissements" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Annuler</a>
        </div>
    </form>
</div>

<script>
function previewLogo(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const placeholder = document.getElementById('logoPlaceholder');
            if (placeholder) placeholder.style.display = 'none';
            const img = document.getElementById('logoPreview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
</script>