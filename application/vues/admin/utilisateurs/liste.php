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
    .badge-status {
        font-size: 0.8rem;
        padding: 0.35rem 0.65rem;
        border-radius: 20px;
    }
    .btn-action {
        border-radius: 8px;
        font-size: 0.8rem;
        padding: 0.35rem 0.65rem;
        transition: transform 0.15s;
        margin: 0.1rem;
    }
    .btn-action:hover { transform: scale(1.05); }
    .user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        background: #e9ecef;
        margin-right: 0.5rem;
    }
</style>

<h2 class="fw-bold mb-4"><i class="bi bi-people-fill me-2 text-primary"></i><?= $titre ?? 'Gestion des utilisateurs' ?></h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Filtres -->
<div class="filter-bar">
    <form method="get" class="row g-3 align-items-end">
        <div class="col-md-6">
            <label for="id_etablissement" class="form-label fw-semibold">Établissement</label>
            <select class="form-select" id="id_etablissement" name="id_etablissement" onchange="this.form.submit()">
                <option value="">Tous les établissements</option>
                <?php foreach ($etablissements as $etab): ?>
                <option value="<?= $etab['id_etablissement'] ?>" <?= ($filtreEtab ?? '') == $etab['id_etablissement'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($etab['nom']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= BASE_URL ?>/admin/ajouter-utilisateur" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajouter un utilisateur</a>
        </div>
    </form>
</div>

<!-- Tableau -->
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Établissement</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $u): ?>
                <tr id="user-<?= $u['id_utilisateur'] ?>">
                    <td>
                        <div class="d-flex align-items-center">
                            <?php if (!empty($u['photo'])): ?>
                                <img src="<?= BASE_URL . '/' . $u['photo'] ?>" class="user-avatar" alt="Photo">
                            <?php else: ?>
                                <div class="user-avatar d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-fill text-secondary"></i>
                                </div>
                            <?php endif; ?>
                            <?= htmlspecialchars($u['nom'] . ' ' . $u['prenom']) ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><span class="badge bg-info text-dark badge-status"><?= $u['role_libelle'] ?></span></td>
                    <td><?= htmlspecialchars($u['etablissement_nom'] ?? '-') ?></td>
                    <td>
                        <span class="badge bg-<?= $u['actif'] ? 'success' : 'danger' ?> badge-status statut-badge">
                            <?= $u['actif'] ? 'Actif' : 'Bloqué' ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-warning btn-action btn-basculer" 
                                data-id="<?= $u['id_utilisateur'] ?>" 
                                data-action="<?= $u['actif'] ? 'bloquer' : 'debloquer' ?>"
                                title="<?= $u['actif'] ? 'Bloquer' : 'Débloquer' ?>">
                            <i class="bi bi-<?= $u['actif'] ? 'lock' : 'unlock' ?>"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary btn-action btn-reset-mdp" 
                                data-id="<?= $u['id_utilisateur'] ?>" 
                                data-nom="<?= htmlspecialchars($u['nom'] . ' ' . $u['prenom']) ?>"
                                title="Réinitialiser mot de passe">
                            <i class="bi bi-key"></i>
                        </button>
                        <a href="<?= BASE_URL ?>/admin/modifier-utilisateur/<?= $u['id_utilisateur'] ?>" class="btn btn-sm btn-outline-primary btn-action" title="Modifier">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-danger btn-action btn-supprimer" data-id="<?= $u['id_utilisateur'] ?>" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modale réinitialisation mot de passe -->
<div class="modal fade" id="modalResetMdp" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form id="formResetMdp">
                <div class="modal-header">
                    <h5 class="modal-title">Réinitialiser le mot de passe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Nouveau mot de passe pour <strong id="resetUserName"></strong></p>
                    <input type="password" class="form-control" id="nouveauMdp" name="nouveau_mdp" required minlength="6" placeholder="Minimum 6 caractères">
                    <input type="hidden" id="resetUserId" name="id_utilisateur">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
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
    const toastEl = container.lastElementChild;
    new bootstrap.Toast(toastEl, { delay: 3000 }).show();
}

// Blocage / déblocage
document.querySelectorAll('.btn-basculer').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const action = this.dataset.action;
        const url = `<?= BASE_URL ?>/admin/${action}-utilisateur/${id}`;

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById('user-' + id);
                    const badge = row.querySelector('.statut-badge');
                    const btnBasculer = row.querySelector('.btn-basculer');

                    if (action === 'bloquer') {
                        badge.textContent = 'Bloqué';
                        badge.className = 'badge bg-danger badge-status statut-badge';
                        btnBasculer.innerHTML = '<i class="bi bi-unlock"></i>';
                        btnBasculer.dataset.action = 'debloquer';
                    } else {
                        badge.textContent = 'Actif';
                        badge.className = 'badge bg-success badge-status statut-badge';
                        btnBasculer.innerHTML = '<i class="bi bi-lock"></i>';
                        btnBasculer.dataset.action = 'bloquer';
                    }
                }
                showToast(data.message, data.success);
            })
            .catch(err => showToast('Erreur réseau', false));
    });
});

// Suppression
document.querySelectorAll('.btn-supprimer').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        if (!confirm('Confirmer la suppression de cet utilisateur ?')) return;

        fetch(`<?= BASE_URL ?>/admin/supprimer-utilisateur/${id}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('user-' + id).remove();
            }
            showToast(data.message, data.success);
        })
        .catch(err => showToast('Erreur réseau', false));
    });
});

// Réinitialisation mot de passe
document.querySelectorAll('.btn-reset-mdp').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const nom = this.dataset.nom;
        document.getElementById('resetUserId').value = id;
        document.getElementById('resetUserName').textContent = nom;
        document.getElementById('nouveauMdp').value = '';
        new bootstrap.Modal(document.getElementById('modalResetMdp')).show();
    });
});

document.getElementById('formResetMdp').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('resetUserId').value;
    const mdp = document.getElementById('nouveauMdp').value;
    if (mdp.length < 6) {
        showToast('Le mot de passe doit contenir au moins 6 caractères.', false);
        return;
    }

    fetch(`<?= BASE_URL ?>/admin/reinitialiser-mot-de-passe/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'nouveau_mdp=' + encodeURIComponent(mdp)
    })
    .then(r => r.json())
    .then(data => {
        showToast(data.message, data.success);
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalResetMdp')).hide();
        }
    })
    .catch(err => showToast('Erreur réseau', false));
});
</script>