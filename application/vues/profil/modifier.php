<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .profil-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        padding: 2rem;
    }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.15);
    }
    .btn-primary {
        border-radius: 8px;
        padding: 0.75rem;
        font-weight: 600;
        transition: transform 0.2s;
    }
    .btn-primary:hover { transform: scale(1.02); }
    .btn-outline-secondary { border-radius: 8px; }

    /* Photo de profil */
    .photo-upload {
        position: relative;
        display: inline-block;
    }
    .photo-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #0d6efd;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .photo-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid #0d6efd;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .photo-camera-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #0d6efd;
        color: white;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        transition: all 0.2s;
    }
    .photo-camera-btn:hover {
        background: #0b5ed7;
        transform: scale(1.1);
    }
    .photo-camera-btn i {
        font-size: 1rem;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="profil-card">
            <h2 class="fw-bold mb-4 text-center"><i class="bi bi-pencil-square me-2 text-primary"></i>Modifier mon profil</h2>

            <form action="<?= BASE_URL ?>/profil/mettre-a-jour" method="post" enctype="multipart/form-data">
                <!-- Photo de profil -->
                <div class="text-center mb-4">
                    <div class="photo-upload">
                        <?php if (!empty($utilisateur['photo'])): ?>
                            <img src="<?= BASE_URL . '/' . $utilisateur['photo'] ?>" class="photo-preview" id="photoPreview" alt="Photo de profil">
                        <?php else: ?>
                            <div class="photo-placeholder" id="photoPreview">
                                <i class="bi bi-person-fill fs-1 text-secondary"></i>
                            </div>
                        <?php endif; ?>
                        <label for="photo" class="photo-camera-btn" title="Changer la photo">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                        <input type="file" class="d-none" id="photo" name="photo" accept="image/*" onchange="previewPhoto(event)">
                    </div>
                    <small class="text-muted d-block mt-2">Cliquez sur l'icône appareil photo pour changer</small>
                </div>

                <!-- Nom & Prénom -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="prenom" class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>
                    </div>
                </div>

                <!-- Email & Téléphone -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telephone" class="form-label fw-semibold">Téléphone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($utilisateur['telephone'] ?? '') ?>">
                    </div>
                </div>

                <!-- Date de naissance, Sexe, Pays -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="date_naissance" class="form-label fw-semibold">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($utilisateur['date_naissance'] ?? '') ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="sexe" class="form-label fw-semibold">Sexe</label>
                        <select class="form-select" id="sexe" name="sexe">
                            <option value="">Choisir...</option>
                            <option value="M" <?= ($utilisateur['sexe'] ?? '') == 'M' ? 'selected' : '' ?>>Homme</option>
                            <option value="F" <?= ($utilisateur['sexe'] ?? '') == 'F' ? 'selected' : '' ?>>Femme</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pays" class="form-label fw-semibold">Pays</label>
                        <input type="text" class="form-control" id="pays" name="pays" value="<?= htmlspecialchars($utilisateur['pays'] ?? '') ?>" placeholder="Ex: Tchad">
                    </div>
                </div>

                <!-- Ville -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="ville" class="form-label fw-semibold">Ville</label>
                        <input type="text" class="form-control" id="ville" name="ville" value="<?= htmlspecialchars($utilisateur['ville'] ?? '') ?>" placeholder="Votre ville">
                    </div>
                </div>

                <!-- Token CSRF -->
                <?= Securite::csrfField() ?>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-check2-circle me-1"></i>Enregistrer</button>
                    <a href="<?= BASE_URL ?>/profil" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Aperçu de la photo avant upload
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'photo-preview';
                img.id = 'photoPreview';
                preview.parentNode.replaceChild(img, preview);
            }
        };
        reader.readAsDataURL(file);
    }
}
</script>