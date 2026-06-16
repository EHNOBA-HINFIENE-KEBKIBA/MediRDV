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

    .etablissement-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        margin: 2rem 0 3rem;
    }

    .etablissement-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        transition: transform 0.25s, box-shadow 0.25s;
        border: 1px solid rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .etablissement-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }

    .card-img-wrapper {
        height: 160px;
        background: linear-gradient(135deg, #eef2ff 0%, #f0f4ff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .card-img-wrapper img {
        max-height: 120px;
        max-width: 90%;
        object-fit: contain;
    }
    .card-img-wrapper .no-logo {
        font-size: 3rem;
        color: #cbd5e1;
    }

    .card-body {
        padding: 1.5rem 1.5rem 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .card-body h5 {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }
    .type-badge {
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

    .map-container {
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: 0 8px 28px rgba(0,0,0,0.08);
        margin-bottom: 2.5rem;
        border: 1px solid rgba(0,0,0,0.04);
    }

    @media (max-width: 768px) {
        .page-wrapper { padding: 1.5rem 0; }
        .etablissement-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="page-wrapper">
    <div class="container">
        <h2 class="fw-bold mb-4"><i class="bi bi-building me-2 text-primary"></i><?= $titre ?? 'Établissements de santé' ?></h2>

        <!-- Filtres -->
        <div class="filter-section">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="type" class="form-label fw-semibold">Type d'établissement</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Tous les types</option>
                        <?php foreach ($types as $type): ?>
                        <option value="<?= $type ?>" <?= ($filtres['type'] ?? '') == $type ? 'selected' : '' ?>><?= $type ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="ville" class="form-label fw-semibold">Ville</label>
                    <select class="form-select" id="ville" name="ville">
                        <option value="">Toutes les villes</option>
                        <?php foreach ($villes as $v): ?>
                        <option value="<?= $v['id_ville'] ?>" <?= ($filtres['ville'] ?? '') == $v['id_ville'] ? 'selected' : '' ?>><?= htmlspecialchars($v['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filtrer</button>
                </div>
                <div class="col-md-2">
                    <a href="<?= BASE_URL ?>/etablissements" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-clockwise me-1"></i>Réinitialiser</a>
                </div>
            </form>
        </div>

        <!-- Carte Leaflet -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <div class="map-container">
            <div id="map" style="height: 380px;"></div>
        </div>
        <script>
            var map = L.map('map').setView([3.848, 11.502], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var etablissements = <?= json_encode($etablissements) ?>;
            var bounds = [];

            etablissements.forEach(function(e) {
                if (e.coord_gps) {
                    var coords = e.coord_gps.split(',');
                    var lat = parseFloat(coords[0]);
                    var lng = parseFloat(coords[1]);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        var popupContent = `<strong>${e.nom}</strong><br>${e.adresse || ''}<br>${e.telephone ? '<i class="bi bi-telephone"></i> ' + e.telephone : ''}<br>
                            <a href="https://www.openstreetmap.org/directions?from=&to=${lat},${lng}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">🗺️ Itinéraire</a>`;
                        L.marker([lat, lng], {
                            icon: L.icon({
                                iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34]
                            })
                        }).addTo(map).bindPopup(popupContent);
                        bounds.push([lat, lng]);
                    }
                }
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [30, 30] });
            }
        </script>

        <!-- Liste des établissements -->
        <?php if (empty($etablissements)): ?>
            <div class="text-center py-5">
                <i class="bi bi-emoji-frown fs-1 text-muted"></i>
                <p class="mt-3 text-muted">Aucun établissement trouvé avec ces critères.</p>
            </div>
        <?php else: ?>
            <div class="etablissement-grid">
                <?php foreach ($etablissements as $etab): ?>
                <div class="etablissement-card">
                    <div class="card-img-wrapper">
                        <?php if (!empty($etab['logo'])): ?>
                            <img src="<?= BASE_URL . '/' . $etab['logo'] ?>" alt="Logo de <?= htmlspecialchars($etab['nom']) ?>">
                        <?php else: ?>
                            <i class="bi bi-hospital no-logo"></i>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h5><?= htmlspecialchars($etab['nom']) ?></h5>
                        <div><span class="type-badge"><?= $etab['type'] ?></span></div>
                        <div class="info-item"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($etab['ville_nom'] ?? '') ?></div>
                        <?php if (!empty($etab['adresse'])): ?>
                            <div class="info-item"><i class="bi bi-house"></i> <?= htmlspecialchars($etab['adresse']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($etab['services_noms'])): ?>
                            <div class="info-item"><i class="bi bi-tags"></i> <?= htmlspecialchars($etab['services_noms']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($etab['telephone'])): ?>
                            <div class="info-item"><i class="bi bi-telephone"></i> <?= htmlspecialchars($etab['telephone']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($etab['email'])): ?>
                            <div class="info-item"><i class="bi bi-envelope"></i> <?= htmlspecialchars($etab['email']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($etab['horaires'])): ?>
                            <div class="info-item"><i class="bi bi-clock"></i> <?= htmlspecialchars($etab['horaires']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($etab['coord_gps'])): ?>
                            <div class="mt-2">
                                <a href="https://www.openstreetmap.org/directions?from=&to=<?= urlencode($etab['coord_gps']) ?>" target="_blank" class="btn btn-sm btn-outline-primary btn-action">
                                    <i class="bi bi-map"></i> Itinéraire
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="mt-2">
                            <a href="<?= BASE_URL ?>/medecins?ville=<?= $etab['id_ville'] ?>" class="btn btn-sm btn-primary btn-action w-100">
                                <i class="bi bi-people me-1"></i> Voir les médecins
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>