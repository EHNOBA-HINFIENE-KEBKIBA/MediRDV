<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['langue'] ?>" dir="<?= $_SESSION['langue'] == 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediRDV - Plateforme de rendez-vous médicaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --bs-primary: #0d6efd; --bs-primary-dark: #0a58ca; }
        .navbar { box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .hero { background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%); color: white; padding: 100px 0; }
        .hero h1 { font-size: 2.8rem; font-weight: bold; }
        .btn-primary { background-color: white; color: #0d6efd; border: none; }
        .btn-primary:hover { background-color: #e2e6ea; color: #0a58ca; }
        .stats-section { background-color: #f8f9fa; }
        .footer { background-color: #212529; color: white; padding: 20px 0; }
        /* Sélecteur de langue */
        .lang-selector .dropdown-toggle::after { display: none; }
        .lang-selector .dropdown-menu { min-width: auto; padding: 0.5rem 0; }
        .lang-selector .dropdown-item { padding: 0.25rem 1rem; font-size: 0.9rem; }
        /* Masquer l'interface Google Translate */
        .goog-te-banner-frame.skiptranslate,
        .goog-te-gadget,
        .goog-logo-link,
        .goog-te-balloon-frame {
            display: none !important;
        }
        body {
            top: 0 !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/accueil">
            <span class="fw-bold text-primary">MediRDV</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/accueil">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/a-propos">À propos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/medecins">Médecins</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/etablissements">Établissements</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/contact">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/blog">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/faq">FAQ</a></li>
                <?php if (isset($_SESSION['utilisateur_id'])): ?>
                    <li class="nav-item"><a class="btn btn-outline-primary ms-2" href="<?= BASE_URL ?>/tableau-bord">Mon espace</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/deconnexion">Déconnexion</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-outline-primary ms-2" href="<?= BASE_URL ?>/connexion">Connexion</a></li>
                <?php endif; ?>
            </ul>
            <!-- Sélecteur de langue avec globe -->
            <div class="dropdown lang-selector ms-3">
                <button class="btn btn-link text-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Changer la langue">
                    <i class="bi bi-globe fs-5"></i> <span class="d-none d-md-inline"><?= strtoupper($_SESSION['langue']) ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="javascript:changerLangue('fr')">🇫🇷 Français</a></li>
                    <li><a class="dropdown-item" href="javascript:changerLangue('en')">🇬🇧 English</a></li>
                    <li><a class="dropdown-item" href="javascript:changerLangue('ar')">🇸🇦 العربية</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Google Translate asynchrone -->
<script>
    function googleTranslateElementInit() {
        if (typeof google !== 'undefined' && google.translate) {
            new google.translate.TranslateElement({
                pageLanguage: 'fr',
                includedLanguages: 'fr,en,ar',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    }

    function changerLangue(lang) {
        if (typeof google !== 'undefined' && google.translate) {
            var cookie = '/fr/' + lang;
            document.cookie = 'googtrans=' + cookie + ';path=/;max-age=' + 60*60*24*30;
            window.location.reload();
        } else {
            var currentUrl = window.location.href.split('?')[0];
            window.location.href = currentUrl + '?lang=' + lang;
        }
    }

    (function() {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
        script.async = true;
        var timeout = setTimeout(function() {
            if (!script.loaded && !script.error) {
                script.src = '';
                console.log('Google Translate non chargé (timeout)');
            }
        }, 5000);
        script.onload = function() { clearTimeout(timeout); };
        script.onerror = function() { clearTimeout(timeout); };
        document.head.appendChild(script);
    })();
</script>
<div id="google_translate_element" style="display:none;"></div>

<div class="container">