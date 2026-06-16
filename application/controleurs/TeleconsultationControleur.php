<?php
class TeleconsultationControleur extends Controleur {

    /**
     * (Médecin) Page de gestion de la téléconsultation
     */
    public function gerer($id_rdv) {
        if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role_id'] != 3) {
            $this->rediriger('/connexion');
        }
        $id_medecin = $this->getIdMedecin();
        $rendezVousModel = new RendezVous();
        $rdv = $rendezVousModel->trouverParId($id_rdv);
        if (!$rdv || $rdv['id_medecin'] != $id_medecin) {
            $_SESSION['message_medecin'] = 'Rendez-vous introuvable.';
            $this->rediriger('/medecin/agenda');
        }

        $teleModel = new Teleconsultation();
        $tele = $teleModel->pourRendezVous($id_rdv);
        $lien = $tele['lien'] ?? '';
        $statut = $tele['statut'] ?? '';

        // Vérifier si la date et l'heure du RDV sont passées
        $dateHeureRdv = $rdv['date_rdv'] . ' ' . $rdv['heure_rdv'];
        $peutActiver = strtotime($dateHeureRdv) <= time();

        $this->afficherVuePrivee('teleconsultation/gerer', [
            'titre'       => 'Gérer la téléconsultation',
            'rdv'         => $rdv,
            'lien'        => $lien,
            'statut'      => $statut,
            'id_rdv'       => $id_rdv,
            'peutActiver' => $peutActiver
        ]);
    }

    /**
     * Activation AJAX de la téléconsultation – renvoie le lien et un message
     */
    public function activer($id_rdv) {
        if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role_id'] != 3) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Non autorisé.']);
            exit;
        }
        $id_medecin = $this->getIdMedecin();
        $rendezVousModel = new RendezVous();
        $rdv = $rendezVousModel->trouverParId($id_rdv);
        if (!$rdv || $rdv['id_medecin'] != $id_medecin) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Rendez-vous introuvable.']);
            exit;
        }

        // Vérifier que la date et l'heure sont passées
        $dateHeureRdv = $rdv['date_rdv'] . ' ' . $rdv['heure_rdv'];
        if (strtotime($dateHeureRdv) > time()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas activer la téléconsultation avant la date et l\'heure du rendez-vous.']);
            exit;
        }

        $teleModel = new Teleconsultation();
        $lien = $teleModel->activer($id_rdv, $rdv['reference']);

        // Notification au patient
        $notificationModel = new Notification();
        $patient = (new Utilisateur())->trouverParId($rdv['id_patient']);
        $messagePatient = "Votre téléconsultation avec le Dr {$rdv['medecin_nom']} {$rdv['medecin_prenom']} est disponible.\nRejoignez la salle : {$lien}";
        $notificationModel->ajouter($rdv['id_patient'], 'teleconsultation', $messagePatient, 'Email');

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Téléconsultation activée. Le patient a été notifié.', 'lien' => $lien]);
        exit;
    }

    /**
     * (Patient) Rejoindre la téléconsultation
     */
    public function rejoindre($id_rdv) {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }
        $rendezVousModel = new RendezVous();
        $rdv = $rendezVousModel->trouverParId($id_rdv);
        if (!$rdv || $rdv['id_patient'] != $_SESSION['utilisateur_id']) {
            $_SESSION['erreur_rdv'] = 'Rendez-vous introuvable.';
            $this->rediriger('/mes-rendezvous');
        }
        $teleModel = new Teleconsultation();
        $tele = $teleModel->pourRendezVous($id_rdv);
        if (!$tele || empty($tele['lien'])) {
            $_SESSION['erreur_rdv'] = 'Aucune téléconsultation disponible.';
            $this->rediriger('/mes-rendezvous');
        }
        $this->afficherVuePrivee('teleconsultation/rejoindre', [
            'titre'  => 'Téléconsultation',
            'lien'   => $tele['lien'],
            'statut' => $tele['statut'] ?? '',
            'rdv'    => $rdv,
            'id_rdv' => $id_rdv
        ]);
    }

    /**
     * (Patient) Obtenir le statut en JSON (polling)
     */
    public function statut($id_rdv) {
        if (!isset($_SESSION['utilisateur_id'])) {
            echo json_encode(['statut' => '']);
            exit;
        }
        $teleModel = new Teleconsultation();
        $tele = $teleModel->pourRendezVous($id_rdv);
        echo json_encode(['statut' => $tele['statut'] ?? '']);
        exit;
    }

    private function getIdMedecin() {
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id_medecin FROM medecins WHERE id_medecin = :id");
        $stmt->execute(['id' => $_SESSION['utilisateur_id']]);
        $medecin = $stmt->fetch();
        return $medecin['id_medecin'] ?? 0;
    }
}