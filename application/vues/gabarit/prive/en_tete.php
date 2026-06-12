<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['langue'] ?>" dir="<?= $_SESSION['langue'] == 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediRDV - <?= $titre ?? 'Espace privé' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* ---- SIDEBAR PROFESSIONNELLE ---- */
        .sidebar {
            position: fixed;
            top: 56px;
            bottom: 0;
            left: 0;
            width: 260px;
            background: linear-gradient(180deg, #0d6efd 0%, #6610f2 100%);
            color: white;
            padding: 20px 0;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link i {
            margin-right: 12px;
            width: 24px;
            text-align: center;
            font-size: 1.2rem;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.15);
            color: white;
            border-left: 3px solid #ffc107;
        }
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
        }

        /* ---- CONTENU PRINCIPAL ---- */
        .main-content {
            margin-left: 260px;
            padding: 25px;
            margin-top: 56px;
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
            animation: fadeIn 0.4s ease-in;
            box-sizing: border-box;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ---- CARTES AMÉLIORÉES ---- */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
            word-wrap: break-word;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .card-stat {
            border-left: 5px solid;
            background: white;
        }
        .card-stat.primary { border-left-color: #0d6efd; }
        .card-stat.success { border-left-color: #198754; }
        .card-stat.warning { border-left-color: #ffc107; }
        .card-stat.danger  { border-left-color: #dc3545; }

        /* ---- TABLEAUX STYLÉS ---- */
        .table {
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }
        .table thead th {
            background-color: #0d6efd;
            color: white;
            font-weight: 500;
            border: none;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(13, 110, 253, 0.03);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.08);
        }
        .table-responsive {
            overflow-x: auto;
        }

        /* ---- BOUTONS ANIMÉS ---- */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-primary { background-color: #0d6efd; border-color: #0d6efd; }
        .btn-outline-primary:hover { background-color: #0d6efd; color: white; }
        .btn:hover { transform: scale(1.02); }

        /* ---- FORMULAIRES ---- */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 10px 15px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        /* ---- ALERTES ---- */
        .alert {
            border-radius: 10px;
            border: none;
        }

        /* ---- TOAST CONTAINER ---- */
        .toast-container {
            z-index: 9999;
        }

        /* ---- RESPONSIVE ---- */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
                width: 260px;
                top: 56px;
                bottom: 0;
                left: 0;
                z-index: 1100;
            }
            .sidebar.show {
                display: block;
            }
            .main-content {
                margin-left: 0;
            }
            .btn-toggle-sidebar {
                display: inline-block;
            }
            .navbar .btn-toggle-sidebar {
                display: inline-block;
                background: transparent;
                border: none;
                font-size: 1.5rem;
                color: #0d6efd;
            }
        }

        @media (min-width: 769px) {
            .btn-toggle-sidebar {
                display: none;
            }
        }

        img, canvas {
            max-width: 100%;
            height: auto;
        }
        .table td, .table th {
            white-space: nowrap;
        }

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

<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container-fluid">
        <button class="btn btn-toggle-sidebar me-2" id="btnToggleSidebar">
            <i class="bi bi-list"></i>
        </button>
        <a class="navbar-brand fw-bold text-primary" href="<?= BASE_URL ?>/tableau-bord">MediRDV</a>
        <div class="d-flex align-items-center">
            <!-- Sélecteur de langue avec globe -->
            <div class="dropdown lang-selector me-3">
                <button class="btn btn-link text-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Changer la langue">
                    <i class="bi bi-globe fs-5"></i> <span class="d-none d-md-inline"><?= strtoupper($_SESSION['langue']) ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="javascript:changerLangue('fr')">🇫🇷 Français</a></li>
                    <li><a class="dropdown-item" href="javascript:changerLangue('en')">🇬🇧 English</a></li>
                    <li><a class="dropdown-item" href="javascript:changerLangue('ar')">🇸🇦 العربية</a></li>
                </ul>
            </div>
            <span class="me-3"><?= htmlspecialchars($nom ?? '') ?></span>
            <a href="<?= BASE_URL ?>/deconnexion" class="btn btn-outline-danger btn-sm">Déconnexion</a>
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

<!-- Sidebar inchangée -->
<div class="sidebar" id="sidebar">
    <ul class="nav flex-column">
        <?php if (($role_id ?? 0) == 5): // Patient ?>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/tableau-bord"><i class="bi bi-speedometer2"></i> Tableau de bord</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/prendre-rdv"><i class="bi bi-calendar-plus"></i> Prendre rendez-vous</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/mes-rendezvous"><i class="bi bi-calendar-check"></i> Mes rendez-vous</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/consultation/historique"><i class="bi bi-clipboard2-heart"></i> Consultations</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/mes-rendezvous"><i class="bi bi-camera-video"></i> Téléconsultations</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/paiements"><i class="bi bi-credit-card"></i> Paiements</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/mes-documents"><i class="bi bi-file-earmark-text"></i> Documents</a></li>
        <?php elseif (($role_id ?? 0) == 3): // Médecin ?>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/tableau-bord"><i class="bi bi-speedometer2"></i> Tableau de bord</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/medecin/agenda"><i class="bi bi-calendar-week"></i> Agenda</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/medecin/disponibilites"><i class="bi bi-clock"></i> Disponibilités</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/medecin/patients"><i class="bi bi-people"></i> Patients</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/medecin/agenda"><i class="bi bi-camera-video"></i> Téléconsultations</a></li>
        <?php elseif (($role_id ?? 0) == 4): // Réceptionniste ?>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/receptionniste/accueil"><i class="bi bi-speedometer2"></i> Tableau de bord</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/receptionniste/tableau-bord"><i class="bi bi-card-list"></i> File d'attente</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/receptionniste/creer-rdv"><i class="bi bi-plus-circle"></i> Nouveau RDV</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/paiements"><i class="bi bi-cash-stack"></i> Paiements</a></li>
        <?php elseif (($role_id ?? 0) == 2): // Admin établissement ?>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin-etablissement/tableau-bord"><i class="bi bi-speedometer2"></i> Tableau de bord</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin-etablissement/medecins"><i class="bi bi-person-badge"></i> Médecins</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin-etablissement/receptionnistes"><i class="bi bi-person-lines-fill"></i> Réceptionnistes</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin-etablissement/services"><i class="bi bi-clipboard2-plus"></i> Services</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin-etablissement/statistiques"><i class="bi bi-graph-up"></i> Statistiques</a></li>
        <?php elseif (($role_id ?? 0) == 1): // Super Admin ?>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/tableau-bord"><i class="bi bi-speedometer2"></i> Tableau de bord</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/etablissements"><i class="bi bi-building"></i> Établissements</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/utilisateurs"><i class="bi bi-people"></i> Utilisateurs</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/specialites"><i class="bi bi-clipboard2-pulse"></i> Spécialités</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/services"><i class="bi bi-clipboard2-plus"></i> Services</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/villes"><i class="bi bi-geo-alt"></i> Villes</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/temoignages"><i class="bi bi-chat-quote"></i> Témoignages</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/statistiques"><i class="bi bi-graph-up"></i> Statistiques globales</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/blog"><i class="bi bi-journal-text"></i> Blog</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/faq"><i class="bi bi-question-circle"></i> FAQ</a></li>
        <?php endif; ?>

        <li class="nav-item mt-4"><hr class="border-light"></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/profil"><i class="bi bi-person-circle"></i> Mon profil</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/accueil"><i class="bi bi-house"></i> Accueil public</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/deconnexion"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
    </ul>
</div>
<div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer"></div>

<div class="main-content" id="mainContent">