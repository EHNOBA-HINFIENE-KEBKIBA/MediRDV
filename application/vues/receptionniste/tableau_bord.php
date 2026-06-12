<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2 class="fw-bold mb-4">📋 File d'attente</h2>

<form method="get" class="row g-2 mb-4">
    <div class="col-md-3">
        <input type="date" name="date" value="<?= $date ?>" class="form-control">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
    </div>
    <div class="col-md-2">
        <a href="<?= BASE_URL ?>/receptionniste/tableau-bord" class="btn btn-outline-secondary w-100">Aujourd'hui</a>
    </div>
    <div class="col-md-5 text-end">
        <a href="<?= BASE_URL ?>/receptionniste/creer-rdv" class="btn btn-success">➕ Nouveau RDV</a>
    </div>
</form>

<?php if (!empty($_SESSION['message_reception'])): ?>
    <div class="alert alert-success"><?= $_SESSION['message_reception'] ?></div>
    <?php unset($_SESSION['message_reception']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['erreur_reception'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['erreur_reception'] ?></div>
    <?php unset($_SESSION['erreur_reception']); ?>
<?php endif; ?>

<?php if (empty($rdvs)): ?>
    <div class="alert alert-info">Aucun rendez-vous pour cette date.</div>
<?php else: ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Heure</th>
                <th>Patient</th>
                <th>Médecin</th>
                <th>Motif</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rdvs as $rdv): ?>
            <tr>
                <td><span class="badge bg-primary"><?= substr($rdv['heure_rdv'], 0, 5) ?></span></td>
                <td><?= htmlspecialchars($rdv['patient_nom'] . ' ' . $rdv['patient_prenom']) ?></td>
                <td>Dr. <?= htmlspecialchars($rdv['medecin_nom'] . ' ' . $rdv['medecin_prenom']) ?></td>
                <td><?= htmlspecialchars($rdv['motif']) ?></td>
                <td><span class="badge bg-<?= $rdv['statut'] == 'Confirmé' ? 'success' : ($rdv['statut'] == 'En attente' ? 'warning' : 'secondary') ?>"><?= $rdv['statut'] ?></span></td>
                <td>
                    <a href="<?= BASE_URL ?>/receptionniste/modifier-rdv/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-outline-primary">✏️</a>
                    <a href="<?= BASE_URL ?>/receptionniste/annuler-rdv/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Annuler ?')">❌</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>