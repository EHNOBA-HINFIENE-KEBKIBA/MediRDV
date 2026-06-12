<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Mes patients' ?></h2>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Date naissance</th>
                <th>Groupe sanguin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($patients as $pat): ?>
            <tr>
                <td><?= htmlspecialchars($pat['nom'] . ' ' . $pat['prenom']) ?></td>
                <td><?= htmlspecialchars($pat['email']) ?></td>
                <td><?= htmlspecialchars($pat['telephone'] ?? '-') ?></td>
                <td><?= $pat['date_naissance'] ? date('d/m/Y', strtotime($pat['date_naissance'])) : '-' ?></td>
                <td><?= htmlspecialchars($pat['groupe_sanguin'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>