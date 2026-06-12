<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2>Mes disponibilités</h2>
<?php if ($message): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<h4>Ajouter un créneau</h4>
<form action="<?= BASE_URL ?>/medecin/ajouter-disponibilite" method="post" class="row g-3 mb-4">
    <div class="col-md-3">
        <select name="jour" class="form-select" required>
            <option value="">Jour</option>
            <option value="Lundi">Lundi</option>
            <option value="Mardi">Mardi</option>
            <option value="Mercredi">Mercredi</option>
            <option value="Jeudi">Jeudi</option>
            <option value="Vendredi">Vendredi</option>
            <option value="Samedi">Samedi</option>
            <option value="Dimanche">Dimanche</option>
        </select>
    </div>
    <div class="col-md-3">
        <input type="time" name="heure_debut" class="form-control" required>
    </div>
    <div class="col-md-3">
        <input type="time" name="heure_fin" class="form-control" required>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-success">Ajouter</button>
    </div>
</form>

<h4>Créneaux existants</h4>
<?php if (empty($dispos)): ?>
    <p>Aucun créneau défini.</p>
<?php else: ?>
    <table class="table table-sm">
        <thead><tr><th>Jour</th><th>Début</th><th>Fin</th><th>Action</th></tr></thead>
        <tbody>
            <?php foreach ($dispos as $d): ?>
            <tr>
                <td><?= $d['jour'] ?></td>
                <td><?= substr($d['heure_debut'],0,5) ?></td>
                <td><?= substr($d['heure_fin'],0,5) ?></td>
                <td><a href="<?= BASE_URL ?>/medecin/supprimer-disponibilite/<?= $d['id_disponibilite'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<a href="<?= BASE_URL ?>/tableau-bord" class="btn btn-secondary">Retour au tableau de bord</a>