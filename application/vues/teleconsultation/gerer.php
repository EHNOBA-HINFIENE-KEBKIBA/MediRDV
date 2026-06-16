<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Gérer la téléconsultation' ?></h2>
<p>Rendez-vous n° <?= $rdv['reference'] ?> du <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'],0,5) ?></p>

<div id="blocTele">
    <?php if ($statut == 'active' && !empty($lien)): ?>
        <div class="alert alert-success">
            <i class="bi bi-camera-video me-2"></i> Téléconsultation active.<br>
            Lien : <a href="<?= htmlspecialchars($lien) ?>" target="_blank" id="lienTele"><?= htmlspecialchars($lien) ?></a>
            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copierLien()">📋 Copier</button>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i> La téléconsultation n'est pas encore activée.
        </div>
        <button class="btn btn-primary" id="btnActiver">✅ Activer la téléconsultation</button>
    <?php endif; ?>
</div>

<a href="<?= BASE_URL ?>/medecin/agenda" class="btn btn-secondary mt-3">Retour à l'agenda</a>

<script>
function copierLien() {
    const lien = document.getElementById('lienTele').href;
    navigator.clipboard.writeText(lien).then(() => {
        showToast('Lien copié !', true);
    });
}

document.getElementById('btnActiver')?.addEventListener('click', function() {
    this.disabled = true;
    this.textContent = 'Activation...';

    fetch('<?= BASE_URL ?>/teleconsultation/activer/<?= $id_rdv ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('blocTele').innerHTML = `
                <div class="alert alert-success">
                    <i class="bi bi-camera-video me-2"></i> Téléconsultation active.<br>
                    Lien : <a href="${data.lien}" target="_blank" id="lienTele">${data.lien}</a>
                    <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copierLien()">📋 Copier</button>
                </div>`;
            showToast(data.message, true);
        } else {
            showToast(data.message, false);
            this.disabled = false;
            this.textContent = '✅ Activer la téléconsultation';
        }
    })
    .catch(err => {
        showToast('Erreur réseau', false);
        this.disabled = false;
        this.textContent = '✅ Activer la téléconsultation';
    });
});

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
    new bootstrap.Toast(container.lastElementChild, { delay: 3000 }).show();
}
</script>