<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    :root {
        --card-shadow: 0 4px 18px rgba(0,0,0,0.04);
        --border-radius: 16px;
    }
    .filter-bar {
        background: #fff;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
    }
    .table-card {
        background: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        padding: 0.5rem 1rem 1rem;
        overflow-x: auto;
    }
    .table > thead { background: #f8fafc; }
    .btn-action {
        border-radius: 8px;
        font-size: 0.8rem;
        padding: 0.35rem 0.65rem;
        transition: transform 0.15s;
        margin: 0.1rem;
    }
    .btn-action:hover { transform: scale(1.05); }
    .etab-logo {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: contain;
        background: #f8f9fa;
        margin-right: 0.75rem;
    }
</style>

<h2 class="fw-bold mb-4"><i class="bi bi-building me-2 text-primary"></i><?= $titre ?? 'Gestion des établissements' ?></h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="filter-bar d-flex justify-content-between align-items-center">
    <span class="text-muted"><i class="bi bi-funnel me-1"></i>Liste des établissements</span>
    <a href="<?= BASE_URL ?>/admin/ajouter-etablissement" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajouter un établissement</a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Établissement</th>
                    <th>Type</th>
                    <th>Ville</th>
                    <th>Téléphone</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etablissements as $etab): ?>
                <tr id="etab-<?= $etab['id_etablissement'] ?>">
                    <td>
                        <div class="d-flex align-items-center">
                            <?php if (!empty($etab['logo'])): ?>
                                <img src="<?= BASE_URL . '/' . $etab['logo'] ?>" class="etab-logo" alt="Logo">
                            <?php else: ?>
                                <div class="etab-logo d-flex align-items-center justify-content-center">
                                    <i class="bi bi-hospital text-secondary"></i>
                                </div>
                            <?php endif; ?>
                            <?= htmlspecialchars($etab['nom']) ?>
                        </div>
                    </td>
                    <td><span class="badge bg-info text-dark"><?= $etab['type'] ?></span></td>
                    <td><?= htmlspecialchars($etab['ville_nom'] ?? '') ?></td>
                    <td><?= htmlspecialchars($etab['telephone'] ?? '-') ?></td>
                    <td class="text-end">
                        <!-- Lien direct vers la page profil -->
                        <a href="<?= BASE_URL ?>/admin/voir-etablissement/<?= $etab['id_etablissement'] ?>" class="btn btn-sm btn-outline-info btn-action" title="Voir le profil">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/admin/modifier-etablissement/<?= $etab['id_etablissement'] ?>" class="btn btn-sm btn-outline-primary btn-action" title="Modifier">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-danger btn-action btn-supprimer" data-id="<?= $etab['id_etablissement'] ?>" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

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
    new bootstrap.Toast(container.lastElementChild, { delay: 3000 }).show();
}

// Suppression AJAX
document.querySelectorAll('.btn-supprimer').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        if (!confirm('Confirmer la suppression de cet établissement ?')) return;

        fetch(`<?= BASE_URL ?>/admin/supprimer-etablissement/${id}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('etab-' + id).remove();
            }
            showToast(data.message, data.success);
        })
        .catch(err => showToast('Erreur réseau', false));
    });
});
</script>