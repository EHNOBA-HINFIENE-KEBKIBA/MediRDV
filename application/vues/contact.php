<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?? 'Contactez-nous' ?></h2>

<?php if (!empty($succes)): ?>
    <div class="alert alert-success"><?= $succes ?></div>
<?php endif; ?>

<?php if (!empty($erreurs)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($erreurs as $err): ?>
                <li><?= $err ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= BASE_URL ?>/traiter-contact" method="post">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($anciens['nom'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($anciens['email'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="sujet" class="form-label">Sujet</label>
        <input type="text" class="form-control" id="sujet" name="sujet" value="<?= htmlspecialchars($anciens['sujet'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="message" class="form-label">Message</label>
        <textarea class="form-control" id="message" name="message" rows="5" required><?= htmlspecialchars($anciens['message'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
</form>