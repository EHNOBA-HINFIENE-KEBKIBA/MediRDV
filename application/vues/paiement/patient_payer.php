<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2 class="fw-bold mb-4">💳 Payer un rendez‑vous</h2>

<div class="card p-4">
    <h5>Rendez‑vous : <?= $rdv['reference'] ?></h5>
    <p>Date : <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'],0,5) ?></p>

    <form action="<?= BASE_URL ?>/paiement/traiter" method="post">
        <input type="hidden" name="id_rdv" value="<?= $rdv['id_rdv'] ?>">
        <div class="mb-3">
            <label for="montant" class="form-label">Montant (FCFA)</label>
            <input type="number" name="montant" id="montant" class="form-control" value="5000" min="1" required>
        </div>
        <div class="mb-3">
            <label for="mode" class="form-label">Mode de paiement</label>
            <select name="mode" id="mode" class="form-select">
                <option value="Espèces">Espèces</option>
                <option value="Mobile Money">Mobile Money</option>
                <option value="Carte bancaire">Carte bancaire</option>
                <option value="Autre">Autre</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Payer maintenant</button>
        <a href="<?= BASE_URL ?>/mes-rendezvous" class="btn btn-outline-secondary">Annuler</a>
    </form>
</div>