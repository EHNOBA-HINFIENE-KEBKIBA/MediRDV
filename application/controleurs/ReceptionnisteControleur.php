<?php
class ReceptionnisteControleur extends Controleur {

    private function verifierReceptionniste() {
        if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role_id'] != 4) {
            $this->rediriger('/connexion');
        }
    }

    /**
     * Tableau de bord : file d'attente du jour
     */
    public function tableauBord() {
        $this->verifierReceptionniste();
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id_etablissement FROM utilisateurs WHERE id_utilisateur = :id");
        $stmt->execute(['id' => $_SESSION['utilisateur_id']]);
        $user = $stmt->fetch();
        $id_etablissement = $user['id_etablissement'] ?? 0;

        $rendezVousModel = new RendezVous();
        $date = $_GET['date'] ?? date('Y-m-d');
        $rdvs = $rendezVousModel->pourEtablissement($id_etablissement, $date);

        $this->afficherVuePrivee('receptionniste/tableau_bord', [
            'titre' => 'File d\'attente',
            'rdvs'  => $rdvs,
            'date'  => $date
        ]);
    }

    /**
     * Formulaire de création d'un rendez-vous
     */
    public function creerRendezVous() {
        $this->verifierReceptionniste();
        // Récupérer l'établissement du réceptionniste
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id_etablissement FROM utilisateurs WHERE id_utilisateur = :id");
        $stmt->execute(['id' => $_SESSION['utilisateur_id']]);
        $user = $stmt->fetch();
        $id_etablissement = $user['id_etablissement'] ?? 0;

        // Récupérer la liste des médecins de l'établissement
        $medecinModel = new Medecin();
        $medecins = $medecinModel->parEtablissement($id_etablissement); // à ajouter dans Medecin

        // Récupérer les patients (pour le choix rapide) : tous ou recherche
        $patients = [];
        if (!empty($_GET['recherche'])) {
            $patientModel = new Patient();
            $patients = $patientModel->rechercher($_GET['recherche']); // à ajouter dans Patient
        }

        $this->afficherVuePrivee('receptionniste/creer_rdv', [
            'titre'      => 'Nouveau rendez-vous',
            'medecins'   => $medecins,
            'patients'   => $patients,
            'recherche'  => $_GET['recherche'] ?? '',
            'id_etablissement' => $id_etablissement
        ]);
    }

    /**
     * Enregistrement d'un nouveau rendez-vous (créé par le réceptionniste)
     */
    public function enregistrerRendezVous() {
        $this->verifierReceptionniste();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_patient = $_POST['id_patient'] ?? 0;
            $id_medecin = $_POST['id_medecin'] ?? 0;
            $date = $_POST['date'] ?? '';
            $heure = $_POST['heure'] ?? '';
            $motif = $_POST['motif'] ?? '';

            // Récupérer l'établissement (celui du réceptionniste)
            $pdo = BaseDeDonnees::getInstance()->getPdo();
            $stmt = $pdo->prepare("SELECT id_etablissement FROM utilisateurs WHERE id_utilisateur = :id");
            $stmt->execute(['id' => $_SESSION['utilisateur_id']]);
            $user = $stmt->fetch();
            $id_etablissement = $user['id_etablissement'] ?? 0;

            if ($id_patient && $id_medecin && $date && $heure) {
                $rendezVousModel = new RendezVous();
                $id_rdv = $rendezVousModel->creer($id_patient, $id_medecin, $id_etablissement, $date, $heure, $motif);
                if ($id_rdv) {
                    $_SESSION['message_reception'] = 'Rendez-vous créé avec succès.';
                } else {
                    $_SESSION['erreur_reception'] = 'Le créneau est déjà pris ou une erreur est survenue.';
                }
            } else {
                $_SESSION['erreur_reception'] = 'Veuillez remplir tous les champs obligatoires.';
            }
        }
        $this->rediriger('/receptionniste/tableau-bord');
    }

    /**
     * Formulaire de modification d'un rendez-vous
     */
    public function modifierRendezVous($id_rdv) {
        $this->verifierReceptionniste();
        $rendezVousModel = new RendezVous();
        $rdv = $rendezVousModel->trouverParId($id_rdv);
        if (!$rdv) {
            $_SESSION['erreur_reception'] = 'Rendez-vous introuvable.';
            $this->rediriger('/receptionniste/tableau-bord');
        }

        // Vérifier que le RDV appartient à l'établissement du réceptionniste
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id_etablissement FROM utilisateurs WHERE id_utilisateur = :id");
        $stmt->execute(['id' => $_SESSION['utilisateur_id']]);
        $user = $stmt->fetch();
        if ($rdv['id_etablissement'] != $user['id_etablissement']) {
            $_SESSION['erreur_reception'] = 'Vous ne pouvez pas modifier ce rendez-vous.';
            $this->rediriger('/receptionniste/tableau-bord');
        }

        // Récupérer les médecins de l'établissement pour le formulaire
        $medecinModel = new Medecin();
        $medecins = $medecinModel->parEtablissement($user['id_etablissement']);

        $this->afficherVuePrivee('receptionniste/modifier_rdv', [
            'titre'    => 'Modifier un rendez-vous',
            'rdv'      => $rdv,
            'medecins' => $medecins
        ]);
    }

    /**
     * Traitement de la modification
     */
    public function enregistrerModificationRendezVous() {
        $this->verifierReceptionniste();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_rdv = $_POST['id_rdv'] ?? 0;
            $id_medecin = $_POST['id_medecin'] ?? 0;
            $date = $_POST['date'] ?? '';
            $heure = $_POST['heure'] ?? '';
            $motif = $_POST['motif'] ?? '';
            $statut = $_POST['statut'] ?? '';

            // Vérifications...
            $rendezVousModel = new RendezVous();
            $ok = $rendezVousModel->modifierRdv($id_rdv, [
                'id_medecin' => $id_medecin,
                'date_rdv'   => $date,
                'heure_rdv'  => $heure,
                'motif'      => $motif,
                'statut'     => $statut
            ]);
            if ($ok) {
                $_SESSION['message_reception'] = 'Rendez-vous modifié.';
            } else {
                $_SESSION['erreur_reception'] = 'Erreur lors de la modification.';
            }
        }
        $this->rediriger('/receptionniste/tableau-bord');
    }

    /**
     * Annulation d'un rendez-vous
     */
    public function annulerRendezVous($id_rdv) {
        $this->verifierReceptionniste();
        $rendezVousModel = new RendezVous();
        $rendezVousModel->changerStatut($id_rdv, null, 'Annulé'); // on passe null car on ne vérifie pas le médecin ici
        $_SESSION['message_reception'] = 'Rendez-vous annulé.';
        $this->rediriger('/receptionniste/tableau-bord');
    }
    public function index() {
    $this->verifierReceptionniste();
    // Quelques statistiques simples
    $pdo = BaseDeDonnees::getInstance()->getPdo();
    $stmt = $pdo->prepare("SELECT id_etablissement FROM utilisateurs WHERE id_utilisateur = :id");
    $stmt->execute(['id' => $_SESSION['utilisateur_id']]);
    $user = $stmt->fetch();
    $id_etablissement = $user['id_etablissement'] ?? 0;

    $rdvModel = new RendezVous();
    $aujourdHui = $rdvModel->pourEtablissement($id_etablissement, date('Y-m-d'));
    $totalAujourdHui = count($aujourdHui);

    $this->afficherVuePrivee('receptionniste/tableau_bord_accueil', [
        'titre' => 'Tableau de bord',
        'totalAujourdHui' => $totalAujourdHui
    ]);
}
}