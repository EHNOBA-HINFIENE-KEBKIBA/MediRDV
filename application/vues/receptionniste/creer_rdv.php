<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Nouveau rendez-vous' ?></h2>

<!-- Recherche de patient -->
<div class="card mb-4">
    <div class="card-body">
        <h5>Rechercher un patient</h5>
        <form method="get" class="row g-2">
            <div class="col-md-8">
                <input type="text" name="recherche" class="form-control" placeholder="Nom, prénom ou email" value="<?= htmlspecialchars($recherche) ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-primary w-100">Rechercher</button>
            </div>
        </form>
        <?php if (!empty($patients)): ?>
            <table class="table table-sm mt-3">
                <thead><tr><th>Nom</th><th>Email</th><th>Téléphone</th><th>Sélectionner</th></tr></thead>
                <tbody>
                    <?php foreach ($patients as $pat): ?>
                    <tr>
                        <td><?= htmlspecialchars($pat['nom'] . ' ' . $pat['prenom']) ?></td>
                        <td><?= htmlspecialchars($pat['email']) ?></td>
                        <td><?= htmlspecialchars($pat['telephone'] ?? '') ?></td>
                        <td><button type="button" class="btn btn-sm btn-success" onclick="selectionnerPatient('<?= $pat['id_utilisateur'] ?>', '<?= htmlspecialchars($pat['nom'] . ' ' . $pat['prenom']) ?>')">Choisir</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (!empty($recherche)): ?>
            <p class="text-muted">Aucun patient trouvé.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Formulaire de rendez-vous -->
<form action="<?= BASE_URL ?>/receptionniste/enregistrer-rdv" method="post">
    <input type="hidden" name="id_patient" id="id_patient">
    <div class="mb-3">
        <label for="patient_affiche" class="form-label">Patient sélectionné</label>
        <input type="text" id="patient_affiche" class="form-control" readonly placeholder="Aucun patient sélectionné">
    </div>
    <div class="mb-3">
        <label for="id_medecin" class="form-label">Médecin</label>
        <select name="id_medecin" id="id_medecin" class="form-select" required>
            <option value="">Choisir...</option>
            <?php foreach ($medecins as $med): ?>
            <option value="<?= $med['id_medecin'] ?>">Dr. <?= htmlspecialchars($med['nom'] . ' ' . $med['prenom']) ?> (<?= htmlspecialchars($med['specialite_nom']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="heure" class="form-label">Heure</label>
            <input type="time" name="heure" id="heure" class="form-control" required>
        </div>
    </div>
    <div class="mb-3">
        <label for="motif" class="form-label">Motif</label>
        <textarea name="motif" id="motif" class="form-control" rows="2" required></textarea>
    </div>
    <button type="submit" class="btn btn-success">Enregistrer le rendez-vous</button>
    <a href="<?= BASE_URL ?>/receptionniste/tableau-bord" class="btn btn-secondary">Annuler</a>
</form>

<script>
function selectionnerPatient(id, nom) {
    document.getElementById('id_patient').value = id;
    document.getElementById('patient_affiche').value = nom;
}
</script>