<?php
class AuthentificationControleur extends Controleur {

    public function connexion() {
        if (isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/tableau-bord');
        }
        $this->afficherVue('authentification/connexion', [
            'titre'  => 'Connexion',
            'erreur' => $_GET['erreur'] ?? null
        ]);
    }

    public function traiterConnexion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $mot_de_passe = $_POST['mot_de_passe'] ?? '';

            $utilisateurModel = new Utilisateur();
            $utilisateur = $utilisateurModel->connecter($email, $mot_de_passe);

            if ($utilisateur) {
                $_SESSION['utilisateur_id'] = $utilisateur['id_utilisateur'];
                $_SESSION['role_id'] = $utilisateur['id_role'];
                $_SESSION['nom'] = $utilisateur['nom'] . ' ' . $utilisateur['prenom'];
                $this->rediriger('/tableau-bord');
            } else {
                $this->rediriger('/connexion?erreur=identifiants');
            }
        }
    }

    // ... le reste des méthodes (inscription, déconnexion) reste inchangé

    public function inscription() {
        if (isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/tableau-bord');
        }
        $this->afficherVue('authentification/inscription', [
            'titre'    => 'Inscription',
            'erreurs'  => $_SESSION['erreurs_inscription'] ?? [],
            'anciennes'=> $_SESSION['anciennes_inscription'] ?? []
        ]);
        unset($_SESSION['erreurs_inscription'], $_SESSION['anciennes_inscription']);
    }

public function traiterInscription() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        Securite::verifierCsrf();
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $date_naissance = trim($_POST['date_naissance'] ?? '');
        $sexe = trim($_POST['sexe'] ?? '');
        $pays = trim($_POST['pays'] ?? '');
        $ville = trim($_POST['ville'] ?? '');
        $mot_de_passe = $_POST['mot_de_passe'] ?? '';
        $confirmation = $_POST['confirmation'] ?? '';

        $erreurs = [];
        if (empty($nom)) $erreurs[] = 'Le nom est obligatoire.';
        if (empty($prenom)) $erreurs[] = 'Le prénom est obligatoire.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = 'Email invalide.';
        if (strlen($mot_de_passe) < 6) $erreurs[] = 'Le mot de passe doit contenir au moins 6 caractères.';
        if ($mot_de_passe !== $confirmation) $erreurs[] = 'Les mots de passe ne correspondent pas.';
        // Validation simple pour la date de naissance (optionnelle)
        if (!empty($date_naissance) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_naissance)) {
            $erreurs[] = 'Format de date de naissance invalide.';
        }

        if (!empty($erreurs)) {
            $_SESSION['erreurs_inscription'] = $erreurs;
            $_SESSION['anciennes_inscription'] = compact('nom','prenom','email','telephone','date_naissance','sexe','pays','ville');
            $this->rediriger('/inscription');
            return;
        }

        $utilisateurModel = new Utilisateur();
        $id = $utilisateurModel->inscrire($nom, $prenom, $email, $mot_de_passe, $telephone, $date_naissance, $sexe, $pays, $ville);
        if ($id) {
            $historique = new HistoriqueAction();
            $historique->enregistrer($id, 'Inscription', "Email : $email");
            $_SESSION['succes_inscription'] = 'Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.';
            $this->rediriger('/connexion');
        } else {
            $_SESSION['erreurs_inscription'] = ['Cet email est déjà utilisé.'];
            $_SESSION['anciennes_inscription'] = compact('nom','prenom','email','telephone','date_naissance','sexe','pays','ville');
            $this->rediriger('/inscription');
        }
    }
}

    public function deconnexion() {
        $id_utilisateur = $_SESSION['utilisateur_id'] ?? null;
        if ($id_utilisateur) {
            $historique = new HistoriqueAction();
            $historique->enregistrer($id_utilisateur, 'Déconnexion');
        }
        session_destroy();
        $this->rediriger('/accueil');
    }
}