<?php
class AdminTemoignageControleur extends Controleur {

    private function verifierSuperAdmin() {
        if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role_id'] != 1) {
            $this->rediriger('/connexion');
        }
    }

    public function index() {
        $this->verifierSuperAdmin();
        $temoignages = (new Temoignage())->tous();
        $message = $_SESSION['message_admin'] ?? '';
        unset($_SESSION['message_admin']);
        $this->afficherVuePrivee('admin/temoignages', [
            'titre' => 'Gestion des témoignages',
            'temoignages' => $temoignages,
            'message' => $message
        ]);
    }

    public function ajouter() {
        $this->verifierSuperAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $contenu = $_POST['contenu'] ?? '';
            $note = $_POST['note'] ?? 5;
            $profession = $_POST['profession'] ?? null;
            (new Temoignage())->ajouter($nom, $contenu, $note, $profession);
            $_SESSION['message_admin'] = 'Témoignage ajouté.';
        }
        $this->rediriger('/admin/temoignages');
    }

    public function basculer($id) {
        $this->verifierSuperAdmin();
        (new Temoignage())->basculerActif($id);
        $_SESSION['message_admin'] = 'Statut modifié.';
        $this->rediriger('/admin/temoignages');
    }

    public function supprimer($id) {
        $this->verifierSuperAdmin();
        (new Temoignage())->supprimer($id);
        $_SESSION['message_admin'] = 'Témoignage supprimé.';
        $this->rediriger('/admin/temoignages');
    }
}