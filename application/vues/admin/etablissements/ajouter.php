<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .logo-preview, .photo-preview {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        object-fit: contain;
        border: 2px solid #dee2e6;
        background: #f8f9fa;
        margin-bottom: 0.5rem;
    }
    .logo-placeholder, .photo-placeholder {
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

<h2><?= $titre ?? 'Ajouter un établissement' ?></h2>

<div class="card p-4 shadow-sm rounded-4">
    <form action="<?= BASE_URL ?>/admin/enregistrer-ajout-etablissement" method="post" enctype="multipart/form-data">
        <div class="row">
            <!-- Informations établissement -->
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="nom" class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Choisir...</option>
                            <?php foreach ($types as $type): ?>
                            <option value="<?= $type ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="id_ville" class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_ville" name="id_ville" required>
                            <option value="">Choisir...</option>
                            <?php foreach ($villes as $ville): ?>
                            <option value="<?= $ville['id_ville'] ?>"><?= htmlspecialchars($ville['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label fw-semibold">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telephone" class="form-label fw-semibold">Téléphone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="coord_gps" class="form-label fw-semibold">Coordonnées GPS</label>
                        <input type="text" class="form-control" id="coord_gps" name="coord_gps" placeholder="lat,lng">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="horaires" class="form-label fw-semibold">Horaires</label>
                        <input type="text" class="form-control" id="horaires" name="horaires" placeholder="ex: Lun-Ven 8h-18h">
                    </div>
                </div>
            </div>
            <!-- Logo établissement -->
            <div class="col-md-4 text-center">
                <label class="form-label fw-semibold">Logo de l'établissement</label>
                <div id="logoContainer">
                    <div class="logo-placeholder" id="logoPlaceholder">
                        <i class="bi bi-building fs-1"></i>
                    </div>
                    <img id="logoPreview" class="logo-preview" style="display:none;" alt="Aperçu du logo">
                </div>
                <input type="file" class="form-control mt-2" id="logo" name="logo" accept="image/*" onchange="previewLogo(event)">
                <small class="text-muted">Formats : JPG, PNG</small>
            </div>
        </div>

        <hr>
        <h5>Compte Administrateur de l'établissement</h5>
        <div class="row">
            <!-- Photo admin -->
            <div class="col-md-3 text-center">
                <label class="form-label fw-semibold">Photo de profil</label>
                <div id="photoContainer">
                    <div class="photo-placeholder" id="photoPlaceholder">
                        <i class="bi bi-person-fill fs-1"></i>
                    </div>
                    <img id="photoPreview" class="photo-preview" style="display:none;" alt="Aperçu photo">
                </div>
                <input type="file" class="form-control mt-2" id="admin_photo" name="admin_photo" accept="image/*" onchange="previewPhoto(event)">
                <small class="text-muted">Optionnelle</small>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="admin_nom" class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="admin_nom" name="admin_nom" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="admin_prenom" class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="admin_prenom" name="admin_prenom" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="admin_email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                </div>
                <div class="mb-3">
                    <label for="admin_mot_de_passe" class="form-label fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="admin_mot_de_passe" name="admin_mot_de_passe" required minlength="6">
                </div>
                <div class="mb-3">
                    <label for="admin_telephone" class="form-label fw-semibold">Téléphone</label>
                    <input type="text" class="form-control" id="admin_telephone" name="admin_telephone">
                </div>
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
            document.getElementById('logoPlaceholder').style.display = 'none';
            const img = document.getElementById('logoPreview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPlaceholder').style.display = 'none';
            const img = document.getElementById('photoPreview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
</script>