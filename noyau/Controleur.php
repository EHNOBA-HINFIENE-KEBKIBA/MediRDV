<?php
class Controleur {

    // Pour les pages publiques
    protected function afficherVue($cheminVue, $donnees = []) {
        extract($donnees);
        ob_start();
        require __DIR__ . '/../application/vues/' . $cheminVue . '.php';
        $contenu = ob_get_clean();

        require __DIR__ . '/../application/vues/gabarit/en_tete.php';
        echo $contenu;
        require __DIR__ . '/../application/vues/gabarit/pied_page.php';
    }

    // Pour les pages privées (avec sidebar)
    protected function afficherVuePrivee($cheminVue, $donnees = []) {
        $donnees['role_id'] = $_SESSION['role_id'] ?? 0;
        $donnees['nom'] = $_SESSION['nom'] ?? '';
        extract($donnees);

        ob_start();
        require __DIR__ . '/../application/vues/' . $cheminVue . '.php';
        $contenu = ob_get_clean();

        require __DIR__ . '/../application/vues/gabarit/prive/en_tete.php';
        echo $contenu;
        require __DIR__ . '/../application/vues/gabarit/prive/pied_page.php';
    }

    protected function rediriger($url) {
        if (strpos($url, '/') === 0) {
            $url = BASE_URL . $url;
        }
        header('Location: ' . $url);
        exit;
    }

    // ==================== SÉCURITÉ ====================

    /**
     * Génère un token CSRF et le stocke en session
     */
    protected function genererTokenCsrf() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifie le token CSRF soumis (peut être appelée directement)
     */
    protected function verifierTokenCsrf($token) {
        if (!isset($_SESSION['csrf_token']) || hash_equals($_SESSION['csrf_token'], $token) === false) {
            session_destroy();
            $this->rediriger('/connexion?erreur=csrf');
            exit;
        }
    }
}