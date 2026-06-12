<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Gestion des villes' ?></h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<form action="<?= BASE_URL ?>/admin/ajouter-ville" method="post" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text" name="nom" class="form-control" placeholder="Nom de la ville" required>
    </div>
    <div class="col-md-5">
        <input type="text" name="pays" class="form-control" value="Cameroun" placeholder="Pays">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
    </div>
</form>

<table class="table table-striped">
    <thead><tr><th>Ville</th><th>Pays</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($villes as $v): ?>
        <tr id="ville-<?= $v['id_ville'] ?>">
            <td><?= htmlspecialchars($v['nom']) ?></td>
            <td><?= htmlspecialchars($v['pays']) ?></td>
            <td>
                <button class="btn btn-sm btn-danger btn-supprimer" data-id="<?= $v['id_ville'] ?>" data-nom="<?= htmlspecialchars($v['nom']) ?>">Supprimer</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Script AJAX + Toast -->
<script>
document.querySelectorAll('.btn-supprimer').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const nom = this.dataset.nom;
        if (!confirm(`Voulez-vous vraiment supprimer la ville "${nom}" ?`)) return;

        fetch('<?= BASE_URL ?>/admin/supprimer-ville/' + id, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Création d'un toast Bootstrap
            const toastHTML = `
            <div class="toast align-items-center text-bg-${data.success ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${data.message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;
            const container = document.getElementById('toastContainer');
            container.insertAdjacentHTML('beforeend', toastHTML);
            const toastEl = container.lastElementChild;
            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();
            // Supprimer la ligne du tableau si succès
            if (data.success) {
                document.getElementById('ville-' + id).remove();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            // Afficher un toast d'erreur générique
            const toastHTML = `
            <div class="toast align-items-center text-bg-danger border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">Une erreur est survenue.</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;
            document.getElementById('toastContainer').insertAdjacentHTML('beforeend', toastHTML);
            const toastEl = container.lastElementChild;
            new bootstrap.Toast(toastEl).show();
        });
    });
});
</script>