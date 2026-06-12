<?php
class TeleconsultationControleur extends Controleur {

    /**
     * (Médecin) Définir ou modifier le lien pour un rendez-vous
     */
    public function gerer($id_rdv) {
        // Vérifier que l'utilisateur est médecin et que le RDV lui appartient
        if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role_id'] != 3) {
            $this->rediriger('/connexion');
        }
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM rendez_vous WHERE id_rdv = :id AND id_medecin = (SELECT id_medecin FROM medecins WHERE id_medecin = :id_medecin)");
        $stmt->execute(['id' => $id_rdv, 'id_medecin' => $_SESSION['utilisateur_id']]);
        $rdv = $stmt->fetch();
        if (!$rdv) {
            $this->rediriger('/medecin/agenda');
        }

        $teleconsultationModel = new Teleconsultation();
        $teleconsultation = $teleconsultationModel->pourRendezVous($id_rdv);
        $lien = $teleconsultation['lien'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lien = trim($_POST['lien'] ?? '');
            if ($lien) {
                $teleconsultationModel->definirLien($id_rdv, $lien);
            }
            $this->rediriger('/medecin/agenda');
        }

        $this->afficherVuePrivee('teleconsultation/gerer', [
            'titre' => 'Téléconsultation',
            'rdv'   => $rdv,
            'lien'  => $lien
        ]);
    }

    /**
     * (Patient) Rejoindre la téléconsultation
     */
    public function rejoindre($id_rdv) {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }
        // Vérifier que le RDV appartient au patient
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM rendez_vous WHERE id_rdv = :id AND id_patient = :id_patient");
        $stmt->execute(['id' => $id_rdv, 'id_patient' => $_SESSION['utilisateur_id']]);
        $rdv = $stmt->fetch();
        if (!$rdv) {
            $this->rediriger('/mes-rendezvous');
        }

        $teleconsultationModel = new Teleconsultation();
        $teleconsultation = $teleconsultationModel->pourRendezVous($id_rdv);
        if (!$teleconsultation || empty($teleconsultation['lien'])) {
            $_SESSION['erreur_rdv'] = 'Aucun lien de téléconsultation disponible pour ce rendez-vous.';
            $this->rediriger('/mes-rendezvous');
        }

        $this->afficherVuePrivee('teleconsultation/rejoindre', [
            'titre' => 'Téléconsultation',
            'lien'  => $teleconsultation['lien'],
            'rdv'   => $rdv
        ]);
    }
}