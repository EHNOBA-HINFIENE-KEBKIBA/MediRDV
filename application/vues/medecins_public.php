<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    :root {
        --card-shadow: 0 8px 28px rgba(0,0,0,0.08);
        --card-hover-shadow: 0 18px 40px rgba(0,0,0,0.12);
        --border-radius: 20px;
        --primary: #0d6efd;
        --primary-light: #eef2ff;
        --bg-section: #f8faff;
    }
    body { background-color: #f4f6f9; }

    .page-wrapper {
        padding: 3rem 0;
        min-height: 100vh;
    }

    .filter-section {
        background: #ffffff;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: 0 4px 18px rgba(0,0,0,0.04);
        margin-bottom: 2.5rem;
    }

    .medecin-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        margin: 2rem 0 3rem;
    }

    .medecin-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        transition: transform 0.25s, box-shadow 0.25s;
        border: 1px solid rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .medecin-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }

    .card-img-wrapper {
        height: 160px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .card-img-wrapper::after {
        content: "";
        position: absolute;
        bottom: -30px;
        left: 0;
        width: 100%;
        height: 60px;
        background: white;
        border-radius: 50% 50% 0 0;
    }
    .medecin-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 1;
        background: #e9ecef;
    }
    .medecin-avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 1;
    }
    .medecin-avatar-placeholder i {
        font-size: 3rem;
        color: white;
        opacity: 0.8;
    }

    .card-body {
        padding: 1.5rem 1.5rem 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        text-align: center;
    }
    .card-body h5 {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }
    .specialite-badge {
        background: var(--primary-light);
        color: var(--primary);
        font-weight: 600;
        padding: 0.3rem 1rem;
        border-radius: 30px;
        font-size: 0.8rem;
        display: inline-block;
        margin-bottom: 1rem;
    }
    .info-item {
        font-size: 0.9rem;
        color: #475569;
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .info-item i {
        color: var(--primary);
        width: 20px;
        text-align: center;
    }
    .btn-action {
        border-radius: 30px;
        font-weight: 600;
        padding: 0.5rem 1.2rem;
        transition: all 0.2s;
        margin-top: auto;
    }

    @media (max-width: 768px) {
        .page-wrapper { padding: 1.5rem 0; }
        .medecin-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="page-wrapper">
    <div class="container">
        <h2 class="fw-bold mb-4"><i class="bi bi-search-heart me-2 text-primary"></i><?= $titre ?? 'Trouver un médecin' ?></h2>

        <!-- Filtres -->
        <div class="filter-section">
            <form method="get" class="row g-3">
                <div class="col-md-3">
                    <label for="specialite" class="form-label fw-semibold">Spécialité</label>
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
                    <label for="ville" class="form-label fw-semibold">Ville</label>
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
                    <label for="nom" class="form-label fw-semibold">Nom du médecin</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Ex : Dr. Dupont" value="<?= htmlspecialchars($filtres['nom'] ?? '') ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filtrer</button>
                </div>
            </form>
        </div>

        <!-- Résultats -->
        <?php if (empty($medecins)): ?>
            <div class="text-center py-5">
                <i class="bi bi-emoji-frown fs-1 text-muted"></i>
                <p class="mt-3 text-muted">Aucun médecin trouvé avec ces critères.</p>
            </div>
        <?php else: ?>
            <div class="medecin-grid">
                <?php foreach ($medecins as $med): ?>
                <?php
                // Récupérer les infos complètes du médecin pour l'affichage (photo, expérience, diplômes)
                // Le contrôleur doit passer ces champs. Si ce n'est pas encore fait, on les récupère via le modèle ici,
                // mais pour l'instant on suppose que le contrôleur les a déjà via la méthode `rechercher`.
                $photo = !empty($med['photo']) ? $med['photo'] : null;
                ?>
                <div class="medecin-card">
                    <div class="card-img-wrapper">
                        <?php if ($photo): ?>
                            <img src="<?= BASE_URL . '/' . $photo ?>" class="medecin-avatar" alt="Dr. <?= htmlspecialchars($med['nom']) ?>">
                        <?php else: ?>
                            <div class="medecin-avatar-placeholder">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h5>Dr. <?= htmlspecialchars($med['nom'] . ' ' . $med['prenom']) ?></h5>
                        <div><span class="specialite-badge"><?= htmlspecialchars($med['specialite_nom']) ?></span></div>
                        <div class="info-item"><i class="bi bi-building"></i> <?= htmlspecialchars($med['etablissement_nom'] ?? 'Non renseigné') ?></div>
                        <div class="info-item"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($med['ville_nom'] ?? '') ?></div>
                        <?php if (!empty($med['experience'])): ?>
                            <div class="info-item"><i class="bi bi-clock-history"></i> <?= $med['experience'] ?> ans d'expérience</div>
                        <?php endif; ?>
                        <?php if (!empty($med['diplomes'])): ?>
                            <div class="info-item"><i class="bi bi-mortarboard"></i> <?= htmlspecialchars($med['diplomes']) ?></div>
                        <?php endif; ?>
                        <div class="mt-auto pt-3">
                            <?php if (isset($_SESSION['utilisateur_id'])): ?>
                                <a href="<?= BASE_URL ?>/prendre-rdv/choisir/<?= $med['id_medecin'] ?>" class="btn btn-primary w-100 btn-action">
                                    <i class="bi bi-calendar-check me-1"></i> Prendre rendez-vous
                                </a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/connexion" class="btn btn-outline-primary w-100 btn-action">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Connectez-vous pour prendre RDV
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>