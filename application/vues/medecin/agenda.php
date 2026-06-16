<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .agenda-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .statut-select {
        font-size: 0.8rem;
        padding: 0.35rem 0.5rem;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        background: white;
        cursor: pointer;
    }
    .btn-action {
        border-radius: 8px;
        margin: 0.1rem;
        font-size: 0.8rem;
        padding: 0.35rem 0.5rem;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-action:hover { transform: translateY(-1px); }
    .observation-text {
        border-radius: 8px;
        resize: vertical;
        min-height: 36px;
        font-size: 0.85rem;
    }
    .text-truncate {
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
    }
</style>

<h2 class="fw-bold mb-4"><i class="bi bi-calendar-week me-2 text-primary"></i>Mon agenda</h2>

<!-- Filtre par date -->
<div class="agenda-card">
    <form method="get" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="date" class="form-label fw-semibold">Date</label>
            <input type="date" id="date" name="date" value="<?= $date ?>" class="form-control">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filtrer</button>
        </div>
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>/medecin/agenda" class="btn btn-outline-secondary w-100"><i class="bi bi-calendar-check me-1"></i>Aujourd'hui</a>
        </div>
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>/tableau-bord" class="btn btn-outline-primary w-100"><i class="bi bi-speedometer2 me-1"></i>Tableau de bord</a>
        </div>
    </form>
</div>

<?php if (empty($rdvs)): ?>
    <div class="text-center py-5">
        <i class="bi bi-emoji-smile fs-1 text-muted"></i>
        <p class="mt-3 text-muted">Aucun rendez-vous pour cette date.</p>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:60px;">Heure</th>
                    <th>Patient</th>
                    <th>Motif</th>
                    <th style="width:140px;">Statut</th>
                    <th style="width:160px;">Observations</th>
                    <th style="width:240px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rdvs as $rdv): ?>
                <tr id="rdv-<?= $rdv['id_rdv'] ?>">
                    <td><span class="badge bg-primary"><?= substr($rdv['heure_rdv'], 0, 5) ?></span></td>
                    <td class="text-truncate" title="<?= htmlspecialchars($rdv['patient_nom'] . ' ' . $rdv['patient_prenom']) ?>">
                        <?= htmlspecialchars($rdv['patient_nom'] . ' ' . $rdv['patient_prenom']) ?>
                    </td>
                    <td class="text-truncate" title="<?= htmlspecialchars($rdv['motif']) ?>">
                        <?= htmlspecialchars($rdv['motif']) ?>
                    </td>
                    <td>
                        <!-- Liste déroulante simple pour changer le statut -->
                        <select class="statut-select" data-id="<?= $rdv['id_rdv'] ?>" onchange="changerStatut(this.dataset.id, this.value)">
                            <option value="En attente" <?= $rdv['statut'] == 'En attente' ? 'selected' : '' ?>>⏳ En attente</option>
                            <option value="Confirmé" <?= $rdv['statut'] == 'Confirmé' ? 'selected' : '' ?>>✅ Confirmé</option>
                            <option value="Terminé" <?= $rdv['statut'] == 'Terminé' ? 'selected' : '' ?>>✔️ Terminé</option>
                            <option value="Reporté" <?= $rdv['statut'] == 'Reporté' ? 'selected' : '' ?>>🔄 Reporter</option>
                            <option value="Annulé" <?= $rdv['statut'] == 'Annulé' ? 'selected' : '' ?>>❌ Annuler</option>
                        </select>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <textarea class="form-control observation-text" data-id="<?= $rdv['id_rdv'] ?>" rows="1"><?= htmlspecialchars($rdv['observations'] ?? '') ?></textarea>
                            <button class="btn btn-outline-success btn-save-obs" data-id="<?= $rdv['id_rdv'] ?>" title="Enregistrer"><i class="bi bi-check-lg"></i></button>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <!-- Téléconsultation -->
                            <?php $tele = $rdv['teleconsultation'] ?? null; ?>
                            <?php if ($tele && $tele['statut'] == 'active'): ?>
                                <a href="<?= BASE_URL ?>/teleconsultation/gerer/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-outline-success btn-action" title="Téléconsultation active"><i class="bi bi-camera-video"></i></a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/teleconsultation/gerer/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-outline-secondary btn-action" title="Gérer la téléconsultation"><i class="bi bi-camera-video-off"></i></a>
                            <?php endif; ?>

                            <!-- Documents joints -->
                            <?php if (in_array($rdv['id_rdv'], $docIds ?? [])): ?>
                                <a href="<?= BASE_URL ?>/medecin/documents-rdv/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-outline-info btn-action" title="Documents joints"><i class="bi bi-paperclip"></i></a>
                            <?php endif; ?>

                            <!-- Modifier / Consultation -->
                            <a href="<?= BASE_URL ?>/medecin/modifier-rdv/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-outline-warning btn-action" title="Modifier"><i class="bi bi-pencil-square"></i></a>
                            <a href="<?= BASE_URL ?>/consultation/gerer/<?= $rdv['id_rdv'] ?>" class="btn btn-sm btn-outline-primary btn-action" title="Consultation"><i class="bi bi-clipboard2-pulse"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

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
    new bootstrap.Toast(container.lastElementChild, { delay: 2000 }).show();
}

function changerStatut(idRdv, nouveauStatut) {
    // Pour "Reporté", on redirige vers la page dédiée
    if (nouveauStatut === 'Reporté') {
        window.location.href = '<?= BASE_URL ?>/medecin/reporter-rdv/' + idRdv;
        return;
    }

    const formData = new FormData();
    formData.append('id_rdv', idRdv);
    formData.append('statut', nouveauStatut);

    fetch('<?= BASE_URL ?>/medecin/changer-statut', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        showToast(data.message || 'Statut mis à jour', data.success);
        if (!data.success) {
            // Revenir à l'ancienne valeur en cas d'échec
            const select = document.querySelector(`select[data-id="${idRdv}"]`);
            if (select) {
                // on pourrait remettre l'ancienne valeur, mais pour rester simple on recharge
                location.reload();
            }
        }
        // Succès : le <select> garde déjà la nouvelle valeur, pas besoin de recharger
    })
    .catch(err => {
        showToast('Erreur réseau', false);
    });
}

document.querySelectorAll('.btn-save-obs').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const textarea = document.querySelector(`.observation-text[data-id="${id}"]`);
        const observations = textarea.value;
        fetch(`<?= BASE_URL ?>/medecin/sauvegarder-observations`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `id_rdv=${id}&observations=${encodeURIComponent(observations)}`
        })
        .then(r => r.json())
        .then(data => showToast(data.message, data.success))
        .catch(err => showToast('Erreur réseau', false));
    });
});
</script>