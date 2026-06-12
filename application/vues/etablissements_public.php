<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Établissements de santé' ?></h2>

<form method="get" class="row g-3 mb-4">
    <div class="col-md-4">
        <label for="type" class="form-label">Type</label>
        <select class="form-select" id="type" name="type">
            <option value="">Tous</option>
            <?php foreach ($types as $type): ?>
            <option value="<?= $type ?>" <?= ($filtres['type'] ?? '') == $type ? 'selected' : '' ?>><?= $type ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <label for="ville" class="form-label">Ville</label>
        <select class="form-select" id="ville" name="ville">
            <option value="">Toutes</option>
            <?php foreach ($villes as $v): ?>
            <option value="<?= $v['id_ville'] ?>" <?= ($filtres['ville'] ?? '') == $v['id_ville'] ? 'selected' : '' ?>><?= htmlspecialchars($v['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
    </div>
</form>

<!-- Carte Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<div id="map" style="height: 300px; margin-bottom: 30px;"></div>
<script>
    var map = L.map('map').setView([3.848, 11.502], 12); // Douala par défaut
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var etablissements = <?= json_encode($etablissements) ?>;
    etablissements.forEach(function(e) {
        if (e.coord_gps) {
            var coords = e.coord_gps.split(',');
            var lat = parseFloat(coords[0]);
            var lng = parseFloat(coords[1]);
            if (!isNaN(lat) && !isNaN(lng)) {
                L.marker([lat, lng]).addTo(map)
                    .bindPopup('<strong>' + e.nom + '</strong><br>' + (e.adresse || ''));
            }
        }
    });
</script>

<div class="row">
    <?php if (empty($etablissements)): ?>
        <p>Aucun établissement trouvé.</p>
    <?php else: ?>
        <?php foreach ($etablissements as $etab): ?>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($etab['nom']) ?></h5>
                    <p class="card-text">
                        <strong>Type :</strong> <?= $etab['type'] ?><br>
                        <strong>Ville :</strong> <?= htmlspecialchars($etab['ville_nom'] ?? '') ?><br>
                        <strong>Services :</strong> <?= htmlspecialchars($etab['services_noms'] ?? 'Non renseignés') ?><br>
                        <strong>Téléphone :</strong> <?= htmlspecialchars($etab['telephone'] ?? '') ?>
                    </p>
                    <a href="<?= BASE_URL ?>/medecins?ville=<?= $etab['id_ville'] ?>" class="btn btn-sm btn-outline-primary">Voir les médecins</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>