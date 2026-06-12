<?php
class Securite {

    /**
     * Génère un champ caché pour le token CSRF
     */
    public static function csrfField() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
    }

    /**
     * Vérifie le token CSRF pour les requêtes POST
     */
    public static function verifierCsrf() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
                session_destroy();
                header('Location: ' . BASE_URL . '/connexion?erreur=csrf');
                exit;
            }
            // Régénérer le token après usage
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}