<?php
class ContactControleur extends Controleur {

    public function index() {
        // Récupération des messages flash
        $succes  = $_SESSION['contact_succes'] ?? '';
        $erreurs = $_SESSION['contact_erreurs'] ?? [];
        $anciens = $_SESSION['contact_anciens'] ?? [];

        // Nettoyage des variables de session
        unset($_SESSION['contact_succes'], $_SESSION['contact_erreurs'], $_SESSION['contact_anciens']);

        $this->afficherVue('contact', [
            'titre'   => 'Contactez-nous',
            'succes'  => $succes,
            'erreurs' => $erreurs,
            'anciens' => $anciens
        ]);
    }

    public function envoyer() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération et nettoyage des données
            $nom     = trim($_POST['nom'] ?? '');
            $email   = trim($_POST['email'] ?? '');
            $sujet   = trim($_POST['sujet'] ?? '');
            $message = trim($_POST['message'] ?? '');

            // Validation
            $erreurs = [];
            if (empty($nom)) {
                $erreurs[] = 'Le nom est obligatoire.';
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = 'Email invalide.';
            }
            if (empty($sujet)) {
                $erreurs[] = 'Le sujet est obligatoire.';
            }
            if (empty($message)) {
                $erreurs[] = 'Le message ne peut pas être vide.';
            }

            // S'il y a des erreurs, on redirige avec les messages
            if (!empty($erreurs)) {
                $_SESSION['contact_erreurs'] = $erreurs;
                $_SESSION['contact_anciens'] = compact('nom', 'email', 'sujet', 'message');
                $this->rediriger('/contact');
                return;
            }

            // Enregistrement en base de données
            $contactModel = new Contact();
            if ($contactModel->enregistrer($nom, $email, $sujet, $message)) {
                $_SESSION['contact_succes'] = 'Votre message a été envoyé avec succès. Nous vous répondrons rapidement.';
            } else {
                $_SESSION['contact_erreurs'] = ['Une erreur est survenue lors de l\'envoi. Veuillez réessayer.'];
            }
        }
        $this->rediriger('/contact');
    }
}