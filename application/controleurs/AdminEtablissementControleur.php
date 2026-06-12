<?php
class AdminEtablissementControleur extends Controleur {

    private function verifierAdminEtablissement() {
        if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role_id'] != 2) {
            $this->rediriger('/connexion');
        }
    }

    private function getIdEtablissement() {
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id_etablissement FROM utilisateurs WHERE id_utilisateur = :id");
        $stmt->execute(['id' => $_SESSION['utilisateur_id']]);
        $user = $stmt->fetch();
        return $user['id_etablissement'] ?? 0;
    }

    // ==================== TABLEAU DE BORD ====================
    public function index() {
        $this->verifierAdminEtablissement();
        $id_etablissement = $this->getIdEtablissement();

        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stats = [];
        $stats['medecins'] = $pdo->query("SELECT COUNT(*) FROM medecins WHERE id_etablissement = $id_etablissement")->fetchColumn();
        $stats['receptionnistes'] = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE id_etablissement = $id_etablissement AND id_role = 4")->fetchColumn();
        $stats['rendezvous'] = $pdo->query("SELECT COUNT(*) FROM rendez_vous WHERE id_etablissement = $id_etablissement")->fetchColumn();
        $stats['paiements'] = $pdo->query("SELECT COUNT(*) FROM paiements p JOIN rendez_vous r ON p.id_rdv = r.id_rdv WHERE r.id_etablissement = $id_etablissement")->fetchColumn();

        $this->afficherVuePrivee('admin_etablissement/tableau_bord', [
            'titre' => 'Tableau de bord établissement',
            'stats' => $stats
        ]);
    }

    // ==================== MÉDECINS ====================
    public function medecins() {
        $this->verifierAdminEtablissement();
        $id_etablissement = $this->getIdEtablissement();
        $medecinModel = new Medecin();
        $medecins = $medecinModel->parEtablissement($id_etablissement);
        $message = $_SESSION['message_admin_etab'] ?? '';
        unset($_SESSION['message_admin_etab']);

        $this->afficherVuePrivee('admin_etablissement/medecins', [
            'titre' => 'Médecins de l\'établissement',
            'medecins' => $medecins,
            'message' => $message
        ]);
    }

    public function modifierMedecin($id_medecin) {
        $this->verifierAdminEtablissement();
        $id_etablissement = $this->getIdEtablissement();
        $medecinModel = new Medecin();
        $medecin = $medecinModel->trouverParId($id_medecin);
        if (!$medecin || $medecin['id_etablissement'] != $id_etablissement) {
            $_SESSION['message_admin_etab'] = 'Médecin introuvable ou non autorisé.';
            $this->rediriger('/admin-etablissement/medecins');
        }
        $dispoModel = new Disponibilite();
        $disponibilites = $dispoModel->pourMedecin($id_medecin);

        $this->afficherVuePrivee('admin_etablissement/modifier_medecin', [
            'titre' => 'Modifier le médecin',
            'medecin' => $medecin,
            'disponibilites' => $disponibilites
        ]);
    }

    public function enregistrerMedecin() {
        $this->verifierAdminEtablissement();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_medecin = $_POST['id_medecin'] ?? 0;
            $pdo = BaseDeDonnees::getInstance()->getPdo();
            $pdo->prepare("DELETE FROM disponibilites WHERE id_medecin = :id")->execute(['id' => $id_medecin]);
            if (!empty($_POST['dispo_jour'])) {
                $dispo = new Disponibilite();
                foreach ($_POST['dispo_jour'] as $index => $jour) {
                    $debut = $_POST['dispo_debut'][$index] ?? '';
                    $fin   = $_POST['dispo_fin'][$index] ?? '';
                    if ($jour && $debut && $fin) {
                        $dispo->ajouter($id_medecin, $jour, $debut, $fin);
                    }
                }
            }
            $_SESSION['message_admin_etab'] = 'Médecin mis à jour.';
        }
        $this->rediriger('/admin-etablissement/medecins');
    }

    // ==================== RÉCEPTIONNISTES ====================
    public function receptionnistes() {
        $this->verifierAdminEtablissement();
        $id_etablissement = $this->getIdEtablissement();
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_etablissement = :id AND id_role = 4");
        $stmt->execute(['id' => $id_etablissement]);
        $receptionnistes = $stmt->fetchAll();
        $message = $_SESSION['message_admin_etab'] ?? '';
        unset($_SESSION['message_admin_etab']);

        $this->afficherVuePrivee('admin_etablissement/receptionnistes', [
            'titre' => 'Réceptionnistes',
            'receptionnistes' => $receptionnistes,
            'message' => $message
        ]);
    }

    public function creerReceptionniste() {
        $this->verifierAdminEtablissement();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_etablissement = $this->getIdEtablissement();
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            $mot_de_passe = password_hash($_POST['mot_de_passe'] ?? 'password', PASSWORD_BCRYPT);
            $telephone = $_POST['telephone'] ?? '';

            $pdo = BaseDeDonnees::getInstance()->getPdo();
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, id_role, id_etablissement) VALUES (:nom, :prenom, :email, :mdp, :tel, 4, :id_etab)");
            $stmt->execute(['nom'=>$nom,'prenom'=>$prenom,'email'=>$email,'mdp'=>$mot_de_passe,'tel'=>$telephone,'id_etab'=>$id_etablissement]);
            $_SESSION['message_admin_etab'] = 'Réceptionniste créé.';
        }
        $this->rediriger('/admin-etablissement/receptionnistes');
    }

    public function supprimerReceptionniste($id) {
        $this->verifierAdminEtablissement();
        $id_etablissement = $this->getIdEtablissement();
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = :id AND id_etablissement = :id_etab AND id_role = 4")
            ->execute(['id' => $id, 'id_etab' => $id_etablissement]);
        $_SESSION['message_admin_etab'] = 'Réceptionniste supprimé.';
        $this->rediriger('/admin-etablissement/receptionnistes');
    }

    // ==================== SERVICES ====================
    public function gererServices() {
        $this->verifierAdminEtablissement();
        $id_etablissement = $this->getIdEtablissement();
        $etablissementModel = new Etablissement();
        $services = $etablissementModel->servicesEtablissement($id_etablissement);
        $message = $_SESSION['message_admin_etab'] ?? '';
        unset($_SESSION['message_admin_etab']);

        $this->afficherVuePrivee('admin_etablissement/services', [
            'titre'    => 'Gérer les services',
            'services' => $services,
            'message'  => $message
        ]);
    }

    public function associerService($id_service) {
        $this->verifierAdminEtablissement();
        $id_etablissement = $this->getIdEtablissement();
        (new Etablissement())->associerService($id_etablissement, $id_service);
        $_SESSION['message_admin_etab'] = 'Service associé.';
        $this->rediriger('/admin-etablissement/services');
    }

    public function dissocierService($id_service) {
        $this->verifierAdminEtablissement();
        $id_etablissement = $this->getIdEtablissement();
        (new Etablissement())->dissocierService($id_etablissement, $id_service);
        $_SESSION['message_admin_etab'] = 'Service dissocié.';
        $this->rediriger('/admin-etablissement/services');
    }

    // ==================== STATISTIQUES ====================
    public function statistiques() {
        $this->verifierAdminEtablissement();
        $id_etablissement = $this->getIdEtablissement();
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $annee = date('Y');
        $stats = $pdo->query("SELECT MONTH(date_rdv) as mois, COUNT(*) as total FROM rendez_vous WHERE id_etablissement = $id_etablissement AND YEAR(date_rdv) = $annee GROUP BY MONTH(date_rdv)")->fetchAll();
        $this->afficherVuePrivee('admin_etablissement/statistiques', ['titre' => 'Statistiques', 'stats' => $stats]);
    }
}