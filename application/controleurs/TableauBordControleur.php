<?php
class TableauBordControleur extends Controleur {

    public function index() {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }

        $role_id = $_SESSION['role_id'];

        switch ($role_id) {
            case 1: // Super Admin → tableau de bord personnalisé
                $this->tableauBordSuperAdmin();
                break;

            case 2: // Admin établissement → redirige vers son propre tableau de bord
                $this->rediriger('/admin-etablissement/tableau-bord');
                break;

            case 3: // Médecin
                $this->tableauBordMedecin();
                break;

            case 4: // Réceptionniste
                $this->tableauBordReceptionniste();
                break;

            default: // Patient
                $this->tableauBordPatient();
                break;
        }
    }

    // ---------- SUPER ADMIN ----------
    private function tableauBordSuperAdmin() {
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stats['medecins']       = $pdo->query("SELECT COUNT(*) FROM medecins")->fetchColumn();
        $stats['patients']       = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
        $stats['etablissements'] = $pdo->query("SELECT COUNT(*) FROM etablissements")->fetchColumn();
        $stats['rendezvous']     = $pdo->query("SELECT COUNT(*) FROM rendez_vous")->fetchColumn();
        $stats['paiements']      = $pdo->query("SELECT COUNT(*) FROM paiements")->fetchColumn();

        $this->afficherVuePrivee('tableau_bord/super_admin', [
            'titre' => 'Tableau de bord',
            'nom'   => $_SESSION['nom'] ?? '',
            'role'  => 'Super Administrateur',
            'stats' => $stats
        ]);
    }

    // ---------- PATIENT ----------
    private function tableauBordPatient() {
        $id_patient = $_SESSION['utilisateur_id'];
        $pdo = BaseDeDonnees::getInstance()->getPdo();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM rendez_vous WHERE id_patient = :id AND date_rdv >= CURDATE() AND statut IN ('En attente','Confirmé')");
        $stmt->execute(['id' => $id_patient]);
        $rdvAVenir = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM rendez_vous WHERE id_patient = :id AND statut = 'Terminé'");
        $stmt->execute(['id' => $id_patient]);
        $rdvTermines = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM paiements p JOIN rendez_vous r ON p.id_rdv = r.id_rdv WHERE r.id_patient = :id");
        $stmt->execute(['id' => $id_patient]);
        $totalPaiements = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM documents WHERE id_patient = :id");
        $stmt->execute(['id' => $id_patient]);
        $documents = $stmt->fetchColumn();

        $this->afficherVuePrivee('tableau_bord/index', [
            'titre'          => 'Tableau de bord',
            'nom'            => $_SESSION['nom'],
            'role'           => 'Patient',
            'rdvAVenir'      => $rdvAVenir,
            'rdvTermines'    => $rdvTermines,
            'totalPaiements' => $totalPaiements,
            'documents'      => $documents
        ]);
    }

    // ---------- MÉDECIN ----------
    private function tableauBordMedecin() {
    $id_medecin = $this->getIdMedecin();
    if (!$id_medecin) { $this->rediriger('/connexion'); return; }
    $pdo = BaseDeDonnees::getInstance()->getPdo();
    $aujourdhui = date('Y-m-d');

    // Compteurs existants
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM rendez_vous WHERE id_medecin = :id AND date_rdv = :date AND statut != 'Annulé'");
    $stmt->execute(['id' => $id_medecin, 'date' => $aujourdhui]);
    $rdvAujourdhui = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM rendez_vous WHERE id_medecin = :id AND statut = 'En attente'");
    $stmt->execute(['id' => $id_medecin]);
    $enAttente = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM rendez_vous WHERE id_medecin = :id AND statut = 'Terminé'");
    $stmt->execute(['id' => $id_medecin]);
    $rdvTermines = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT id_patient) FROM rendez_vous WHERE id_medecin = :id");
    $stmt->execute(['id' => $id_medecin]);
    $totalPatients = $stmt->fetchColumn();

    // Rendez-vous par mois (année courante) pour le graphique
    $annee = date('Y');
    $stmtMensuel = $pdo->prepare("SELECT MONTH(date_rdv) as mois, COUNT(*) as total FROM rendez_vous WHERE id_medecin = :id AND YEAR(date_rdv) = :annee GROUP BY MONTH(date_rdv)");
    $stmtMensuel->execute(['id' => $id_medecin, 'annee' => $annee]);
    $statsMensuelles = $stmtMensuel->fetchAll();

    $this->afficherVuePrivee('tableau_bord/medecin', [
        'titre'           => 'Tableau de bord médecin',
        'nom'             => $_SESSION['nom'],
        'role'            => 'Médecin',
        'rdvAujourdhui'   => $rdvAujourdhui,
        'enAttente'       => $enAttente,
        'rdvTermines'     => $rdvTermines,
        'totalPatients'   => $totalPatients,
        'statsMensuelles' => $statsMensuelles
    ]);
}

    // ---------- RÉCEPTIONNISTE ----------
    private function tableauBordReceptionniste() {
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id_etablissement FROM utilisateurs WHERE id_utilisateur = :id");
        $stmt->execute(['id' => $_SESSION['utilisateur_id']]);
        $user = $stmt->fetch();
        $id_etablissement = $user['id_etablissement'] ?? 0;
        $aujourdhui = date('Y-m-d');

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM rendez_vous WHERE id_etablissement = :id AND date_rdv = :date");
        $stmt->execute(['id' => $id_etablissement, 'date' => $aujourdhui]);
        $rdvAujourdhui = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM rendez_vous WHERE id_etablissement = :id AND date_rdv = :date AND statut = 'En attente'");
        $stmt->execute(['id' => $id_etablissement, 'date' => $aujourdhui]);
        $enAttente = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM rendez_vous WHERE id_etablissement = :id AND date_rdv = :date AND statut = 'Confirmé'");
        $stmt->execute(['id' => $id_etablissement, 'date' => $aujourdhui]);
        $confirmes = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM rendez_vous WHERE id_etablissement = :id AND date_rdv = :date AND statut = 'Terminé'");
        $stmt->execute(['id' => $id_etablissement, 'date' => $aujourdhui]);
        $termines = $stmt->fetchColumn();

        $this->afficherVuePrivee('tableau_bord/receptionniste', [
            'titre'         => 'Tableau de bord réceptionniste',
            'nom'           => $_SESSION['nom'],
            'role'          => 'Réceptionniste',
            'rdvAujourdhui' => $rdvAujourdhui,
            'enAttente'     => $enAttente,
            'confirmes'     => $confirmes,
            'termines'      => $termines
        ]);
    }

    private function getIdMedecin() {
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id_medecin FROM medecins WHERE id_medecin = :id");
        $stmt->execute(['id' => $_SESSION['utilisateur_id']]);
        $medecin = $stmt->fetch();
        return $medecin['id_medecin'] ?? 0;
    }
    
}