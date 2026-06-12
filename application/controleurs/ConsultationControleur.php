<?php
class ConsultationControleur extends Controleur {

    /**
     * Médecin : saisir ou modifier une consultation
     */
    public function gerer($id_rdv) {
        if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role_id'] != 3) {
            $this->rediriger('/connexion');
        }

        $rendezVousModel = new RendezVous();
        $rdv = $rendezVousModel->trouverParId($id_rdv);
        if (!$rdv || $rdv['id_medecin'] != $_SESSION['utilisateur_id']) {
            $_SESSION['message_medecin'] = 'Rendez-vous introuvable ou non autorisé.';
            $this->rediriger('/medecin/agenda');
        }

        $consultationModel = new Consultation();
        $consultation = $consultationModel->pourRendezVous($id_rdv);
        $patient = (new Utilisateur())->trouverParId($rdv['id_patient']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $diagnostic   = trim($_POST['diagnostic'] ?? '');
            $prescription = trim($_POST['prescription'] ?? '');
            $notes        = trim($_POST['notes'] ?? '');

            $consultationModel->creerOuMettreAJour($id_rdv, $diagnostic, $prescription, $notes);

            // Mettre à jour le statut du rendez-vous à "Terminé"
            $rendezVousModel->changerStatut($id_rdv, $_SESSION['utilisateur_id'], 'Terminé');

            $_SESSION['message_medecin'] = 'Consultation enregistrée avec succès.';
            $this->rediriger('/medecin/agenda');
        }

        $this->afficherVuePrivee('consultation/gerer', [
            'titre'        => 'Consultation médicale',
            'rdv'          => $rdv,
            'patient'      => $patient,
            'consultation' => $consultation
        ]);
    }

    /**
     * Patient : consulter le compte-rendu
     */
    public function voir($id_rdv) {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }

        $rendezVousModel = new RendezVous();
        $rdv = $rendezVousModel->trouverParId($id_rdv);
        if (!$rdv || $rdv['id_patient'] != $_SESSION['utilisateur_id']) {
            $_SESSION['message_patient'] = 'Rendez-vous introuvable.';
            $this->rediriger('/mes-rendezvous');
        }

        $consultationModel = new Consultation();
        $consultation = $consultationModel->pourRendezVous($id_rdv);
        if (!$consultation) {
            $_SESSION['message_patient'] = 'Aucune consultation trouvée pour ce rendez-vous.';
            $this->rediriger('/mes-rendezvous');
        }

        $this->afficherVuePrivee('consultation/voir', [
            'titre'        => 'Compte-rendu de consultation',
            'rdv'          => $rdv,
            'consultation' => $consultation
        ]);
    }

    /**
     * Patient : historique de toutes les consultations
     */
    public function historique() {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }

        $consultationModel = new Consultation();
        $consultations = $consultationModel->pourPatient($_SESSION['utilisateur_id']);

        $this->afficherVuePrivee('consultation/historique', [
            'titre'         => 'Historique des consultations',
            'consultations' => $consultations
        ]);
    }
}