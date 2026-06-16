<?php
class ConsultationControleur extends Controleur {

    // Médecin : saisir ou modifier une consultation
    public function gerer($id_rdv) {
    if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role_id'] != 3) {
        $this->rediriger('/connexion');
    }
    $rendezVousModel = new RendezVous();
    $rdv = $rendezVousModel->trouverParId($id_rdv);
    if (!$rdv || $rdv['id_medecin'] != $_SESSION['utilisateur_id']) {
        $_SESSION['message_medecin'] = 'Rendez-vous introuvable.';
        $this->rediriger('/medecin/agenda');
    }

    $consultationModel = new Consultation();
    $consultation = $consultationModel->pourRendezVous($id_rdv);
    $patient = (new Utilisateur())->trouverParId($rdv['id_patient']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnostic   = trim($_POST['diagnostic'] ?? '');
    $prescription = trim($_POST['prescription'] ?? '');
    $notes        = trim($_POST['notes'] ?? '');
    $signature    = isset($_POST['signature']) ? 1 : 0;

    // Gestion de la signature manuscrite
    $signatureImage = null;
    if (!empty($_POST['signature_data'])) {
        $data = $_POST['signature_data'];
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        $dossier = 'stockage/signatures/';
        if (!is_dir($dossier)) mkdir($dossier, 0755, true);
        $nomFichier = 'sig_' . $id_rdv . '_' . time() . '.png';
        $chemin = $dossier . $nomFichier;
        file_put_contents($chemin, $data);
        $signatureImage = $chemin;
        $signature = 1; // forcer la signature si image présente
    }

    // Enregistrer la consultation SANS génération de PDF (le PDF est remplacé par la page HTML imprimable)
    $consultationModel->creerOuMettreAJour($id_rdv, $diagnostic, $prescription, $notes, $signature, $signatureImage, null);

    // Changer le statut du rendez-vous à "Terminé"
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

    // Génération du PDF de l'ordonnance
    private function genererPdfOrdonnance($rdv, $patient, $diagnostic, $prescription, $notes, $signatureImagePath = null) {
    $medecin = (new Utilisateur())->trouverParId($_SESSION['utilisateur_id']);
    $html = '<h1>Ordonnance Médicale</h1>';
    $html .= '<p><strong>Patient :</strong> ' . htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) . '</p>';
    $html .= '<p><strong>Date :</strong> ' . date('d/m/Y') . '</p>';
    $html .= '<p><strong>Médecin :</strong> Dr. ' . htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']) . '</p>';
    $html .= '<hr>';
    $html .= '<h3>Diagnostic</h3><p>' . nl2br(htmlspecialchars($diagnostic)) . '</p>';
    $html .= '<h3>Prescription</h3><p>' . nl2br(htmlspecialchars($prescription)) . '</p>';
    if (!empty($notes)) {
        $html .= '<h3>Notes</h3><p>' . nl2br(htmlspecialchars($notes)) . '</p>';
    }
    // Signature
    $html .= '<hr><p><strong>Signature :</strong></p>';
    if ($signatureImagePath && file_exists($signatureImagePath)) {
        $html .= '<img src="' . $signatureImagePath . '" style="max-width: 200px;"/>';
    } else {
        $html .= '<p style="color:green;">Dr. ' . htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']) . ' – Validée le ' . date('d/m/Y à H:i') . '</p>';
    }

    $dossier = 'stockage/ordonnances/';
    if (!is_dir($dossier)) mkdir($dossier, 0755, true);
    $nomFichier = 'ordonnance_' . $rdv['reference'] . '.pdf';
    $chemin = $dossier . $nomFichier;

    require_once __DIR__ . '/../helpers/PdfGenerator.php';
    PdfGenerator::generer($nomFichier, $html, $chemin);
    return $chemin;
}

    // Patient : voir une consultation
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
            $_SESSION['message_patient'] = 'Aucune consultation trouvée.';
            $this->rediriger('/mes-rendezvous');
        }

        $this->afficherVuePrivee('consultation/voir', [
            'titre'        => 'Compte-rendu',
            'rdv'          => $rdv,
            'consultation' => $consultation
        ]);
    }

    // Patient : historique des consultations
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

    // Patient : télécharger le PDF d'une ordonnance
    public function telechargerPdf($id_consultation) {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }
        $consultationModel = new Consultation();
        $consultation = $consultationModel->trouverParId($id_consultation); // Méthode à ajouter dans le modèle si nécessaire
        if (!$consultation || !file_exists($consultation['pdf_chemin'])) {
            $_SESSION['message_patient'] = 'PDF introuvable.';
            $this->rediriger('/mes-rendezvous');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="ordonnance.pdf"');
        readfile($consultation['pdf_chemin']);
        exit;
    }

    // Médecin : envoyer l'ordonnance par email au patient
    public function envoyerOrdonnance($id_rdv) {
        if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role_id'] != 3) {
            $this->rediriger('/connexion');
        }
        $consultationModel = new Consultation();
        $consultation = $consultationModel->pourRendezVous($id_rdv);
        if (!$consultation) {
            $_SESSION['message_medecin'] = 'Consultation introuvable.';
            $this->rediriger('/medecin/agenda');
        }

        $rendezVousModel = new RendezVous();
        $rdv = $rendezVousModel->trouverParId($id_rdv);
        $patient = (new Utilisateur())->trouverParId($rdv['id_patient']);

        $notificationModel = new Notification();
        $message = "Votre ordonnance est prête. Connectez-vous à MediRDV pour la consulter.";
        if (!empty($consultation['pdf_chemin'])) {
            $message .= " Vous pouvez également la télécharger depuis votre espace.";
        }
        $notificationModel->ajouter($rdv['id_patient'], 'ordonnance', $message, 'Email');
        $_SESSION['message_medecin'] = 'Ordonnance envoyée au patient.';
        $this->rediriger('/medecin/agenda');
    }
    public function ordonnance($id_rdv) {
    if (!isset($_SESSION['utilisateur_id'])) {
        $this->rediriger('/connexion');
    }
    $rendezVousModel = new RendezVous();
    $rdv = $rendezVousModel->trouverParId($id_rdv);
    if (!$rdv) {
        $this->rediriger('/mes-rendezvous');
    }
    // Vérifier que l'utilisateur est le patient ou le médecin du RDV
    $isPatient = ($rdv['id_patient'] == $_SESSION['utilisateur_id']);
    $isMedecin = (($_SESSION['role_id'] ?? 0) == 3 && $rdv['id_medecin'] == $_SESSION['utilisateur_id']);
    if (!$isPatient && !$isMedecin) {
        $this->rediriger('/mes-rendezvous');
    }

    $consultationModel = new Consultation();
    $consultation = $consultationModel->pourRendezVous($id_rdv);
    if (!$consultation) {
        $_SESSION['message_patient'] = 'Aucune consultation trouvée.';
        $this->rediriger('/mes-rendezvous');
    }

    $patient = (new Utilisateur())->trouverParId($rdv['id_patient']);
    $medecin = (new Utilisateur())->trouverParId($rdv['id_medecin']);

    // Afficher la vue d'ordonnance SANS le gabarit habituel
    require __DIR__ . '/../vues/consultation/ordonnance.php';
}
}