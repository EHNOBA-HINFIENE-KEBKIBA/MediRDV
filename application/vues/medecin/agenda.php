<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2>Mon agenda</h2>
<form method="get" class="mb-4">
    <label for="date">Date :</label>
    <input type="date" id="date" name="date" value="<?= $date ?>" class="form-control d-inline w-auto">
    <button type="submit" class="btn btn-outline-primary">Filtrer</button>
    <a href="<?= BASE_URL ?>/medecin/agenda" class="btn btn-outline-secondary">Aujourd'hui</a>
</form>

<?php if (empty($rdvs)): ?>
    <p>Aucun rendez-vous pour cette date.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Heure</th>
                    <th>Patient</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th>Observations</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rdvs as $rdv): ?>
                <tr id="rdv-<?= $rdv['id_rdv'] ?>">
                    <td><?= substr($rdv['heure_rdv'], 0, 5) ?></td>
                    <td><?= htmlspecialchars($rdv['patient_nom'] . ' ' . $rdv['patient_prenom']) ?></td>
                    <td><?= htmlspecialchars($rdv['motif']) ?></td>
                    <td>
                        <form method="post" action="<?= BASE_URL ?>/medecin/changer-statut" style="display:inline;">
                            <input type="hidden" name="id_rdv" value="<?= $rdv['id_rdv'] ?>">
                            <select name="statut" class="form-select form-select-sm d-inline w-auto">
                                <option value="Confirmé" <?= $rdv['statut']=='Confirmé'?'selected':'' ?>>Confirmer</option>
                                <option value="Terminé" <?= $rdv['statut']=='Terminé'?'selected':'' ?>>Terminé</option>
                                <option value="Reporté" <?= $rdv['statut']=='Reporté'?'selected':'' ?>>Reporter</option>
                                <option value="Annulé" <?= $rdv['statut']=='Annulé'?'selected':'' ?>>Annuler</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Appliquer</button>
                        </form>
                    </td>
                    <td>
                        <div class="input-group">
                            <textarea class="form-control form-control-sm observation-text" data-id="<?= $rdv['id_rdv'] ?>" style="min-width: 150px;"><?= htmlspecialchars($rdv['observations'] ?? '') ?></textarea>
                            <button class="btn btn-sm btn-outline-success btn-save-obs" data-id="<?= $rdv['id_rdv'] ?>">✔</button>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <?php if ($rdv['statut'] == 'Confirmé'): ?>
                                <a href="<?= BASE_URL ?>/teleconsultation/gerer/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-outline-success">Téléconsultation</a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/medecin/modifier-rdv/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-outline-warning">Modifier</a>
                            <a href="<?= BASE_URL ?>/consultation/gerer/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-outline-info">Consultation</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<a href="<?= BASE_URL ?>/tableau-bord" class="btn btn-secondary">Retour au tableau de bord</a>

<script>
function showToast(message, success) {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    const toastHTML = `
    <div class="toast align-items-center text-bg-${success ? 'success' : 'danger'} border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>`;
    container.insertAdjacentHTML('beforeend', toastHTML);
    const toastEl = container.lastElementChild;
    new bootstrap.Toast(toastEl, { delay: 2000 }).show();
}

// Sauvegarde AJAX des observations
document.querySelectorAll('.btn-save-obs').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const textarea = document.querySelector(`.observation-text[data-id="${id}"]`);
        const observations = textarea.value;
        const url = `<?= BASE_URL ?>/medecin/sauvegarder-observations`;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `id_rdv=${id}&observations=${encodeURIComponent(observations)}`
        })
        .then(response => response.json())
        .then(data => {
            showToast(data.message, data.success);
        })
        .catch(err => {
            showToast('Erreur réseau', false);
        });
    });
});
</script>