<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Gérer la téléconsultation' ?></h2>
<p>Rendez-vous n° <?= $rdv['reference'] ?> du <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'],0,5) ?></p>

<form method="post">
    <div class="mb-3">
        <label for="lien" class="form-label">Lien de la réunion (Jitsi Meet, Zoom…)</label>
        <input type="url" class="form-control" id="lien" name="lien" value="<?= htmlspecialchars($lien ?? '') ?>" placeholder="https://meet.jit.si/..." required>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="<?= BASE_URL ?>/medecin/agenda" class="btn btn-secondary">Retour</a>
</form>