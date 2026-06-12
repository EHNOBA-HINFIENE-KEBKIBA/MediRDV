<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Mes documents' ?></h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<!-- Formulaire d'upload -->
<div class="card mb-4">
    <div class="card-body">
        <h5>Ajouter un document</h5>
        <form action="<?= BASE_URL ?>/mes-documents/uploader" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <input type="file" name="document" class="form-control" required>
                <button type="submit" class="btn btn-primary">Uploader</button>
            </div>
        </form>
    </div>
</div>

<!-- Liste des documents -->
<?php if (empty($documents)): ?>
    <p>Aucun document.</p>
<?php else: ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nom du fichier</th>
                <th>Date d'upload</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($documents as $doc): ?>
            <tr>
                <td><?= htmlspecialchars($doc['nom_fichier']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($doc['date_upload'])) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/mes-documents/telecharger/<?= $doc['id_document'] ?>" class="btn btn-sm btn-outline-primary">Télécharger</a>
                    <a href="<?= BASE_URL ?>/mes-documents/supprimer/<?= $doc['id_document'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>