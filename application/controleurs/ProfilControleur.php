<?php
class ProfilControleur extends Controleur {

    private function verifierConnecte() {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }
    }

    public function index() {
        $this->verifierConnecte();
        $utilisateurModel = new Utilisateur();
        $utilisateur = $utilisateurModel->trouverParId($_SESSION['utilisateur_id']);
        $message = $_SESSION['message_profil'] ?? '';
        unset($_SESSION['message_profil']);

        $this->afficherVuePrivee('profil/index', [
            'titre'      => 'Mon profil',
            'utilisateur'=> $utilisateur,
            'message'    => $message
        ]);
    }

    public function modifier() {
        $this->verifierConnecte();
        $utilisateurModel = new Utilisateur();
        $utilisateur = $utilisateurModel->trouverParId($_SESSION['utilisateur_id']);

        $this->afficherVuePrivee('profil/modifier', [
            'titre'      => 'Modifier mon profil',
            'utilisateur'=> $utilisateur
        ]);
    }

    public function mettreAJour() {
        $this->verifierConnecte();
        Securite::verifierCsrf();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['utilisateur_id'];
            $donnees = [
                'nom'      => $_POST['nom'] ?? '',
                'prenom'   => $_POST['prenom'] ?? '',
                'email'    => $_POST['email'] ?? '',
                'telephone'=> $_POST['telephone'] ?? ''
            ];

            // Gestion de la photo de profil
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $dossier = 'public/assets/images/photos/';
                $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $nomFichier = 'user_' . $id . '_' . time() . '.' . $extension;
                $chemin = $dossier . $nomFichier;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $chemin)) {
                    $donnees['photo'] = $chemin;
                }
            }

            $utilisateurModel = new Utilisateur();
            $ok = $utilisateurModel->mettreAJour($id, $donnees);
            $_SESSION['message_profil'] = $ok ? 'Profil mis à jour.' : 'Erreur.';
            if ($ok) {
                $_SESSION['nom'] = $donnees['nom'] . ' ' . $donnees['prenom'];
                // Journalisation
                $historique = new HistoriqueAction();
                $historique->enregistrer($id, 'Modification profil');
            }
        }
        $this->rediriger('/profil');
    }

    public function changerMotDePasse() {
        $this->verifierConnecte();
        $message = $_SESSION['message_profil'] ?? '';
        unset($_SESSION['message_profil']);
        $this->afficherVuePrivee('profil/changer_mot_de_passe', [
            'titre'   => 'Changer mon mot de passe',
            'message' => $message
        ]);
    }

    public function enregistrerMotDePasse() {
        $this->verifierConnecte();
        Securite::verifierCsrf();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ancien = $_POST['ancien_mot_de_passe'] ?? '';
            $nouveau = $_POST['nouveau_mot_de_passe'] ?? '';
            $confirmation = $_POST['confirmation'] ?? '';

            $erreur = '';
            $utilisateurModel = new Utilisateur();
            $utilisateur = $utilisateurModel->trouverParId($_SESSION['utilisateur_id']);

            if (!password_verify($ancien, $utilisateur['mot_de_passe'])) {
                $erreur = 'Ancien mot de passe incorrect.';
            } elseif (strlen($nouveau) < 6) {
                $erreur = '6 caractères minimum.';
            } elseif ($nouveau !== $confirmation) {
                $erreur = 'Ne correspondent pas.';
            } else {
                $ok = $utilisateurModel->mettreAJour($_SESSION['utilisateur_id'], ['mot_de_passe' => $nouveau]);
                $_SESSION['message_profil'] = $ok ? 'Mot de passe modifié.' : 'Erreur.';
                if ($ok) {
                    $historique = new HistoriqueAction();
                    $historique->enregistrer($_SESSION['utilisateur_id'], 'Changement mot de passe');
                    $this->rediriger('/profil');
                    return;
                }
            }
            if ($erreur) $_SESSION['message_profil'] = $erreur;
        }
        $this->rediriger('/profil/changer-mot-de-passe');
    }
}