<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?></h2>
<?php if ($message): ?><div class="alert alert-info"><?= $message ?></div><?php endif; ?>
<form method="post" action="<?= BASE_URL ?>/admin-etablissement/creer-receptionniste" class="row g-2 mb-4">
    <div class="col-md-2"><input type="text" name="nom" class="form-control" placeholder="Nom" required></div>
    <div class="col-md-2"><input type="text" name="prenom" class="form-control" placeholder="Prénom" required></div>
    <div class="col-md-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
    <div class="col-md-2"><input type="password" name="mot_de_passe" class="form-control" placeholder="Mot de passe" required></div>
    <div class="col-md-2"><input type="text" name="telephone" class="form-control" placeholder="Téléphone"></div>
    <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">Créer</button></div>
</form>
<table class="table table-hover">
    <thead><tr><th>Nom</th><th>Email</th><th>Téléphone</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($receptionnistes as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['nom'].' '.$r['prenom']) ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
            <td><?= htmlspecialchars($r['telephone'] ?? '') ?></td>
            <td><a href="<?= BASE_URL ?>/admin-etablissement/supprimer-receptionniste/<?= $r['id_utilisateur'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>