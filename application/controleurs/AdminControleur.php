<?php
class AdminControleur extends Controleur {

    /**
     * Vérifie que l'utilisateur est Super Admin (role_id = 1)
     */
    private function verifierSuperAdmin() {
        if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role_id'] != 1) {
            $this->rediriger('/connexion');
        }
    }

    // ==================== ÉTABLISSEMENTS ====================

    public function etablissements() {
        $this->verifierSuperAdmin();
        $etablissementModel = new Etablissement();
        $etablissements = $etablissementModel->tousAvecVille();
        $message = $_SESSION['message_admin'] ?? '';
        unset($_SESSION['message_admin']);

        $this->afficherVuePrivee('admin/etablissements/liste', [
            'titre' => 'Gestion des établissements',
            'etablissements' => $etablissements,
            'message' => $message
        ]);
    }

    public function ajouterEtablissement() {
        $this->verifierSuperAdmin();
        $villeModel = new Ville();
        $villes = $villeModel->tous();
        $types = (new Etablissement())->types();

        $this->afficherVuePrivee('admin/etablissements/ajouter', [
            'titre' => 'Ajouter un établissement',
            'villes' => $villes,
            'types' => $types
        ]);
    }

public function enregistrerAjoutEtablissement() {
    $this->verifierSuperAdmin();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $donnees = [
            'nom' => $_POST['nom'] ?? '',
            'type' => $_POST['type'] ?? '',
            'description' => $_POST['description'] ?? '',
            'adresse' => $_POST['adresse'] ?? '',
            'telephone' => $_POST['telephone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'coord_gps' => $_POST['coord_gps'] ?? '',
            'horaires' => $_POST['horaires'] ?? '',
            'id_ville' => $_POST['id_ville'] ?? 0
        ];

        // Récupération des infos pour le compte admin
        $adminNom = $_POST['admin_nom'] ?? '';
        $adminPrenom = $_POST['admin_prenom'] ?? '';
        $adminEmail = $_POST['admin_email'] ?? '';
        $adminMotDePasse = $_POST['admin_mot_de_passe'] ?? '';

        $etablissementModel = new Etablissement();
        $pdo = BaseDeDonnees::getInstance()->getPdo();

        try {
            $pdo->beginTransaction();

            // Créer l'établissement
            $ok = $etablissementModel->ajouter($donnees);
            if (!$ok) throw new Exception("Erreur ajout établissement");

            $id_etablissement = $pdo->lastInsertId();

            // Créer le compte administrateur d'établissement
            $utilisateurModel = new Utilisateur();
            $adminData = [
                'nom' => $adminNom,
                'prenom' => $adminPrenom,
                'email' => $adminEmail,
                'mot_de_passe' => $adminMotDePasse,
                'telephone' => $_POST['admin_telephone'] ?? '',
                'id_role' => 2, // Admin établissement
                'id_etablissement' => $id_etablissement
            ];
            $utilisateurModel->creerAvecRole($adminData, 'autre'); // 'autre' car pas médecin

            $pdo->commit();
            $_SESSION['message_admin'] = 'Établissement et compte administrateur créés avec succès.';
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['message_admin'] = 'Erreur lors de la création : ' . $e->getMessage();
        }
    }
    $this->rediriger('/admin/etablissements');
}

    public function modifierEtablissement($id) {
        $this->verifierSuperAdmin();
        $etablissementModel = new Etablissement();
        $etablissement = $etablissementModel->trouverParId($id);
        if (!$etablissement) {
            $_SESSION['message_admin'] = 'Établissement introuvable.';
            $this->rediriger('/admin/etablissements');
        }
        $villeModel = new Ville();
        $villes = $villeModel->tous();
        $types = $etablissementModel->types();

        $this->afficherVuePrivee('admin/etablissements/modifier', [
            'titre' => 'Modifier un établissement',
            'etablissement' => $etablissement,
            'villes' => $villes,
            'types' => $types
        ]);
    }

    public function enregistrerModificationEtablissement() {
        $this->verifierSuperAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_etablissement'] ?? 0;
            $donnees = [
                'nom' => $_POST['nom'] ?? '',
                'type' => $_POST['type'] ?? '',
                'description' => $_POST['description'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'coord_gps' => $_POST['coord_gps'] ?? '',
                'horaires' => $_POST['horaires'] ?? '',
                'id_ville' => $_POST['id_ville'] ?? 0
            ];
            $etablissementModel = new Etablissement();
            if ($etablissementModel->modifier($id, $donnees)) {
                $_SESSION['message_admin'] = 'Établissement modifié avec succès.';
            } else {
                $_SESSION['message_admin'] = 'Erreur lors de la modification.';
            }
        }
        $this->rediriger('/admin/etablissements');
    }

    public function supprimerEtablissement($id) {
        $this->verifierSuperAdmin();
        $etablissementModel = new Etablissement();
        $success = false;
        $message = '';

        // Vérifier si l'établissement a des dépendances (médecins, rendez-vous...)
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM medecins WHERE id_etablissement = :id");
        $stmt->execute(['id' => $id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $message = 'Impossible de supprimer cet établissement : il contient encore des médecins.';
        } else {
            if ($etablissementModel->supprimer($id)) {
                $success = true;
                $message = 'Établissement supprimé.';
            } else {
                $message = 'Erreur lors de la suppression.';
            }
        }

        if ($this->estAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }

        $_SESSION['message_admin'] = $message;
        $this->rediriger('/admin/etablissements');
    }

    // ==================== UTILISATEURS ====================

    public function utilisateurs() {
        $this->verifierSuperAdmin();
        $utilisateurModel = new Utilisateur();
        $utilisateurs = $utilisateurModel->tousAvecRole();
        $message = $_SESSION['message_admin'] ?? '';
        unset($_SESSION['message_admin']);

        $this->afficherVuePrivee('admin/utilisateurs/liste', [
            'titre' => 'Gestion des utilisateurs',
            'utilisateurs' => $utilisateurs,
            'message' => $message
        ]);
    }

    public function ajouterUtilisateur() {
        $this->verifierSuperAdmin();
        $roleModel = new Role();
        $roles = $roleModel->tousSauf('Super Administrateur', 'Patient');
        $etablissementModel = new Etablissement();
        $etablissements = $etablissementModel->tousAvecVille();
        $specialites = (new Specialite())->tous();

        $this->afficherVuePrivee('admin/utilisateurs/ajouter', [
            'titre' => 'Ajouter un utilisateur',
            'roles' => $roles,
            'etablissements' => $etablissements,
            'specialites' => $specialites
        ]);
    }

public function enregistrerAjoutUtilisateur() {
    $this->verifierSuperAdmin();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';

        // Vérifier si l'email existe déjà
        $utilisateurModel = new Utilisateur();
        $existant = $utilisateurModel->trouverParEmail($email);
        if ($existant) {
            $_SESSION['message_admin'] = 'Cet email est déjà utilisé.';
            $this->rediriger('/admin/utilisateurs');
            return;
        }

        $donnees = [
            'nom' => $_POST['nom'] ?? '',
            'prenom' => $_POST['prenom'] ?? '',
            'email' => $email,
            'mot_de_passe' => $_POST['mot_de_passe'] ?? '',
            'telephone' => $_POST['telephone'] ?? '',
            'id_role' => $_POST['id_role'] ?? 0,
            'id_etablissement' => $_POST['id_etablissement'] ?: null,
            'sexe' => $_POST['sexe'] ?? 'M',
            'diplomes' => $_POST['diplomes'] ?? '',
            'experience' => $_POST['experience'] ?? 0,
            'id_specialite' => $_POST['id_specialite'] ?: null
        ];

        $typeRole = ($donnees['id_role'] == 3) ? 'medecin' : 'autre';
        $id = $utilisateurModel->creerAvecRole($donnees, $typeRole);
        if ($id) {
            $_SESSION['message_admin'] = 'Utilisateur créé avec succès.';
        } else {
            $_SESSION['message_admin'] = 'Erreur lors de la création.';
        }
    }
    $this->rediriger('/admin/utilisateurs');
}
    public function modifierUtilisateur($id) {
        $this->verifierSuperAdmin();
        $utilisateurModel = new Utilisateur();
        $utilisateur = $utilisateurModel->trouverParId($id);
        if (!$utilisateur) {
            $_SESSION['message_admin'] = 'Utilisateur introuvable.';
            $this->rediriger('/admin/utilisateurs');
        }
        $roleModel = new Role();
        $roles = $roleModel->tousSauf('Super Administrateur', 'Patient');
        $etablissementModel = new Etablissement();
        $etablissements = $etablissementModel->tousAvecVille();
        $specialites = (new Specialite())->tous();

        $medecinInfo = null;
        if ($utilisateur['id_role'] == 3) {
            $pdo = BaseDeDonnees::getInstance()->getPdo();
            $stmt = $pdo->prepare("SELECT * FROM medecins WHERE id_medecin = :id");
            $stmt->execute(['id' => $id]);
            $medecinInfo = $stmt->fetch();
        }

        $this->afficherVuePrivee('admin/utilisateurs/modifier', [
            'titre' => 'Modifier un utilisateur',
            'utilisateur' => $utilisateur,
            'medecinInfo' => $medecinInfo,
            'roles' => $roles,
            'etablissements' => $etablissements,
            'specialites' => $specialites
        ]);
    }

    public function enregistrerModificationUtilisateur() {
        $this->verifierSuperAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_utilisateur'] ?? 0;
            $donnees = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'email' => $_POST['email'] ?? '',
                'mot_de_passe' => $_POST['mot_de_passe'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'id_role' => $_POST['id_role'] ?? 0,
                'id_etablissement' => $_POST['id_etablissement'] ?: null
            ];

            $utilisateurModel = new Utilisateur();
            $ok = $utilisateurModel->mettreAJour($id, $donnees);
            if ($ok) {
                if ($donnees['id_role'] == 3) {
                    $pdo = BaseDeDonnees::getInstance()->getPdo();
                    $stmt = $pdo->prepare("UPDATE medecins SET sexe = :sexe, diplomes = :diplomes, experience = :experience, id_specialite = :id_specialite WHERE id_medecin = :id");
                    $stmt->execute([
                        'sexe' => $_POST['sexe'] ?? 'M',
                        'diplomes' => $_POST['diplomes'] ?? '',
                        'experience' => $_POST['experience'] ?? 0,
                        'id_specialite' => $_POST['id_specialite'] ?: null,
                        'id' => $id
                    ]);
                }
                $_SESSION['message_admin'] = 'Utilisateur mis à jour.';
            } else {
                $_SESSION['message_admin'] = 'Erreur lors de la mise à jour.';
            }
        }
        $this->rediriger('/admin/utilisateurs');
    }

    public function supprimerUtilisateur($id) {
        $this->verifierSuperAdmin();
        $utilisateurModel = new Utilisateur();
        $success = false;
        $message = '';

        // Vérifier si l'utilisateur a des rendez-vous ou autres dépendances
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM rendez_vous WHERE id_patient = :id OR id_medecin = (SELECT id_medecin FROM medecins WHERE id_medecin = :id)");
        $stmt->execute(['id' => $id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $message = 'Impossible de supprimer cet utilisateur : il possède des rendez-vous. Veuillez d\'abord supprimer ou réassigner ses rendez-vous.';
        } else {
            if ($utilisateurModel->supprimer($id)) {
                $success = true;
                $message = 'Utilisateur supprimé.';
            } else {
                $message = 'Erreur lors de la suppression.';
            }
        }

        if ($this->estAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }

        $_SESSION['message_admin'] = $message;
        $this->rediriger('/admin/utilisateurs');
    }

    // ==================== SPÉCIALITÉS ====================

    public function specialites() {
        $this->verifierSuperAdmin();
        $specialiteModel = new Specialite();
        $specialites = $specialiteModel->tous();
        $message = $_SESSION['message_admin'] ?? '';
        unset($_SESSION['message_admin']);

        $this->afficherVuePrivee('admin/specialites/liste', [
            'titre' => 'Gestion des spécialités',
            'specialites' => $specialites,
            'message' => $message
        ]);
    }

    public function ajouterSpecialite() {
        $this->verifierSuperAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            if ($nom) {
                (new Specialite())->ajouter($nom);
                $_SESSION['message_admin'] = 'Spécialité ajoutée.';
            }
        }
        $this->rediriger('/admin/specialites');
    }

    public function supprimerSpecialite($id) {
        $this->verifierSuperAdmin();
        $success = false;
        $message = '';

        // Vérifier si la spécialité est utilisée par un médecin
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM medecins WHERE id_specialite = :id");
        $stmt->execute(['id' => $id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $message = 'Impossible de supprimer cette spécialité : elle est attribuée à un ou plusieurs médecins.';
        } else {
            if ((new Specialite())->supprimer($id)) {
                $success = true;
                $message = 'Spécialité supprimée.';
            } else {
                $message = 'Erreur lors de la suppression.';
            }
        }

        if ($this->estAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }

        $_SESSION['message_admin'] = $message;
        $this->rediriger('/admin/specialites');
    }

    // ==================== SERVICES ====================

    public function services() {
        $this->verifierSuperAdmin();
        $serviceModel = new Service();
        $services = $serviceModel->tous();
        $message = $_SESSION['message_admin'] ?? '';
        unset($_SESSION['message_admin']);

        $this->afficherVuePrivee('admin/services/liste', [
            'titre' => 'Gestion des services',
            'services' => $services,
            'message' => $message
        ]);
    }

    public function ajouterService() {
        $this->verifierSuperAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $description = trim($_POST['description'] ?? '');
            if ($nom) {
                (new Service())->ajouter($nom, $description);
                $_SESSION['message_admin'] = 'Service ajouté.';
            }
        }
        $this->rediriger('/admin/services');
    }

    public function supprimerService($id) {
        $this->verifierSuperAdmin();
        $success = false;
        $message = '';

        // Vérifier si le service est utilisé par un établissement
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM etablissement_service WHERE id_service = :id");
        $stmt->execute(['id' => $id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $message = 'Impossible de supprimer ce service : il est associé à un ou plusieurs établissements.';
        } else {
            if ((new Service())->supprimer($id)) {
                $success = true;
                $message = 'Service supprimé.';
            } else {
                $message = 'Erreur lors de la suppression.';
            }
        }

        if ($this->estAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }

        $_SESSION['message_admin'] = $message;
        $this->rediriger('/admin/services');
    }

    // ==================== VILLES ====================

    public function villes() {
        $this->verifierSuperAdmin();
        $villeModel = new Ville();
        $villes = $villeModel->tous();
        $message = $_SESSION['message_admin'] ?? '';
        unset($_SESSION['message_admin']);

        $this->afficherVuePrivee('admin/villes/liste', [
            'titre'   => 'Gestion des villes',
            'villes'  => $villes,
            'message' => $message
        ]);
    }

    public function ajouterVille() {
        $this->verifierSuperAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom  = trim($_POST['nom'] ?? '');
            $pays = trim($_POST['pays'] ?? 'Cameroun');
            if ($nom) {
                (new Ville())->ajouter($nom, $pays);
                $_SESSION['message_admin'] = 'Ville ajoutée.';
            }
        }
        $this->rediriger('/admin/villes');
    }

    public function supprimerVille($id) {
        $this->verifierSuperAdmin();
        $success = false;
        $message = '';

        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM etablissements WHERE id_ville = :id");
        $stmt->execute(['id' => $id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $message = 'Impossible de supprimer cette ville : elle est utilisée par un ou plusieurs établissements.';
        } else {
            $villeModel = new Ville();
            if ($villeModel->supprimer($id)) {
                $success = true;
                $message = 'Ville supprimée avec succès.';
            } else {
                $message = 'Erreur lors de la suppression.';
            }
        }

        if ($this->estAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }

        $_SESSION['message_admin'] = $message;
        $this->rediriger('/admin/villes');
    }

    // ==================== STATISTIQUES GLOBALES ====================
    // (méthode existante, non modifiée)

    // ==================== HELPER ====================
    /**
     * Détermine si la requête courante est AJAX
     */
    private function estAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    public function bloquerUtilisateur($id) {
    $this->verifierSuperAdmin();
    $utilisateurModel = new Utilisateur();
    $user = $utilisateurModel->trouverParId($id);
    if ($user && $user['id_role'] != 1) { // ne pas bloquer le super admin
        $utilisateurModel->changerActif($id, 0);
        $_SESSION['message_admin'] = 'Utilisateur bloqué.';
    }
    $this->rediriger('/admin/utilisateurs');
}

public function debloquerUtilisateur($id) {
    $this->verifierSuperAdmin();
    $utilisateurModel = new Utilisateur();
    $user = $utilisateurModel->trouverParId($id);
    if ($user) {
        $utilisateurModel->changerActif($id, 1);
        $_SESSION['message_admin'] = 'Utilisateur débloqué.';
    }
    $this->rediriger('/admin/utilisateurs');
}
}