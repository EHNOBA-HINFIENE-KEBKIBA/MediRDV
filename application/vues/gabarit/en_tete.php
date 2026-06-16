<?php
if(!defined('BASE_URL')){
    define('BASE_URL','/MediRDV');
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['langue'] ?? 'fr' ?>"
dir="<?= ($_SESSION['langue'] ?? 'fr') == 'ar' ? 'rtl':'ltr' ?>">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>MediRDV</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">

<link href="<?= BASE_URL ?>/public/assets/css/style.css"
rel="stylesheet">

</head>

<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top">

<div class="container">

<a class="navbar-brand fw-bold text-primary"
href="<?= BASE_URL ?>">

<i class="bi bi-heart-pulse-fill"></i>

MediRDV

</a>

<button class="navbar-toggler"
data-bs-toggle="collapse"
data-bs-target="#menu">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse"
id="menu">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link"
href="<?= BASE_URL ?>">
Accueil
</a>
</li>

<li class="nav-item">
<a class="nav-link"
href="<?= BASE_URL ?>/medecins">
Médecins
</a>
</li>

<li class="nav-item">
<a class="nav-link"
href="<?= BASE_URL ?>/etablissements">
Établissements
</a>
</li>

<li class="nav-item">
<a class="nav-link"
href="<?= BASE_URL ?>/services">
Services
</a>
</li>

<li class="nav-item">
<a class="nav-link"
href="<?= BASE_URL ?>/teleconsultation">
Téléconsultation
</a>
</li>

<li class="nav-item">
<a class="nav-link"
href="<?= BASE_URL ?>/blog">
Blog
</a>
</li>

<li class="nav-item">
<a class="nav-link"
href="<?= BASE_URL ?>/contact">
Contact
</a>
</li>

</ul>

<div class="d-flex align-items-center gap-2 ms-3">

<?php if(isset($_SESSION['utilisateur_id'])): ?>

<a href="<?= BASE_URL ?>/tableau-bord"
class="btn btn-primary">

Mon espace

</a>

<?php else: ?>

<a href="<?= BASE_URL ?>/connexion"
class="btn btn-outline-primary">

Connexion

</a>

<a href="<?= BASE_URL ?>/inscription"
class="btn btn-primary">

Inscription

</a>

<?php endif; ?>

</div>

</div>

</div>

</nav>