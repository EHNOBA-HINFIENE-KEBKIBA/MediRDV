<?php
// configuration/langue.php
$langues_disponibles = ['fr', 'en', 'ar'];

// Détection de la langue via le cookie Google Translate (ou paramètre GET)
if (isset($_GET['lang']) && in_array($_GET['lang'], $langues_disponibles)) {
    $langue = $_GET['lang'];
} elseif (isset($_COOKIE['googtrans'])) {
    // Le cookie googtrans a la forme "/fr/en", on en extrait la langue cible
    $parts = explode('/', $_COOKIE['googtrans']);
    $langue = end($parts);
    if (!in_array($langue, $langues_disponibles)) $langue = 'fr';
} else {
    $langue = 'fr';
}
$_SESSION['langue'] = $langue;
// Pas de fonction __()