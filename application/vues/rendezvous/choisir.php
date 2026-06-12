<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2>Choisir un créneau</h2>
<h4>Dr. <?= htmlspecialchars($medecin['nom'] . ' ' . $medecin['prenom']) ?></h4>

<form method="get" class="mb-4">
    <label for="date">Date :</label>
    <input type="date" id="date" name="date" value="<?= $date ?>" class="form-control d-inline w-auto">
    <button type="submit" class="btn btn-outline-primary">Voir les disponibilités</button>
</form>

<?php if (!empty($creneaux)): ?>
    <h5>Créneaux disponibles le <?= date('d/m/Y', strtotime($date)) ?></h5>
    <form action="<?= BASE_URL ?>/prendre-rdv/reserver" method="post">
        <input type="hidden" name="id_medecin" value="<?= $medecin['id_medecin'] ?>">
        <input type="hidden" name="date" value="<?= $date ?>">
        <div class="mb-3">
            <label for="motif" class="form-label">Motif du rendez-vous</label>
            <textarea name="motif" id="motif" class="form-control" required></textarea>
        </div>
        <div class="row">
            <?php foreach ($creneaux as $creneau): ?>
            <div class="col-md-3 mb-2">
                <button type="submit" name="heure" value="<?= $creneau ?>" class="btn btn-outline-success w-100">
                    <?= substr($creneau, 0, 5) ?>
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </form>
<?php elseif (isset($_GET['date'])): ?>
    <div class="alert alert-warning">Aucun créneau disponible pour cette date.</div>
<?php endif; ?>