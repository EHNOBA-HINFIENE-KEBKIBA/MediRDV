<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Gestion des utilisateurs' ?></h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<a href="<?= BASE_URL ?>/admin/ajouter-utilisateur" class="btn btn-primary mb-3">Ajouter un utilisateur</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Établissement</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($utilisateurs as $u): ?>
        <tr id="user-<?= $u['id_utilisateur'] ?>">
            <td><?= htmlspecialchars($u['nom'] . ' ' . $u['prenom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role_libelle'] ?></td>
            <td><?= htmlspecialchars($u['etablissement_nom'] ?? '-') ?></td>
            <td>
                <span class="badge bg-<?= $u['actif'] ? 'success' : 'danger' ?> statut-badge">
                    <?= $u['actif'] ? 'Actif' : 'Bloqué' ?>
                </span>
            </td>
            <td>
                <button class="btn btn-sm btn-warning btn-basculer" 
                        data-id="<?= $u['id_utilisateur'] ?>" 
                        data-action="<?= $u['actif'] ? 'bloquer' : 'debloquer' ?>">
                    <?= $u['actif'] ? 'Bloquer' : 'Débloquer' ?>
                </button>
                <a href="<?= BASE_URL ?>/admin/modifier-utilisateur/<?= $u['id_utilisateur'] ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                <button class="btn btn-sm btn-danger btn-supprimer" data-id="<?= $u['id_utilisateur'] ?>">
                    Supprimer
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
function showToast(message, success) {
    const container = document.getElementById('toastContainer');
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

// Gestion du blocage / déblocage
document.querySelectorAll('.btn-basculer').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const action = this.dataset.action; // 'bloquer' ou 'debloquer'
        const url = `<?= BASE_URL ?>/admin/${action}-utilisateur/${id}`;

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Inverser l'état dans la ligne
                    const row = document.getElementById('user-' + id);
                    const badge = row.querySelector('.statut-badge');
                    const btnBasculer = row.querySelector('.btn-basculer');

                    if (action === 'bloquer') {
                        badge.textContent = 'Bloqué';
                        badge.className = 'badge bg-danger statut-badge';
                        btnBasculer.textContent = 'Débloquer';
                        btnBasculer.dataset.action = 'debloquer';
                    } else {
                        badge.textContent = 'Actif';
                        badge.className = 'badge bg-success statut-badge';
                        btnBasculer.textContent = 'Bloquer';
                        btnBasculer.dataset.action = 'bloquer';
                    }
                }
                showToast(data.message, data.success);
            })
            .catch(err => {
                showToast('Erreur réseau', false);
            });
    });
});

// Gestion de la suppression
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
        .catch(err => {
            showToast('Erreur réseau', false);
        });
    });
});
</script>