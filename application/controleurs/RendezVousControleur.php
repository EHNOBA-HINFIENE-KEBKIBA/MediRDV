<?php
require_once __DIR__ . '/../helpers/QrCodeGenerator.php';

class RendezVousControleur extends Controleur {

    private function verifierPatient($id_utilisateur) {
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id_patient FROM patients WHERE id_patient = :id");
        $stmt->execute(['id' => $id_utilisateur]);
        if (!$stmt->fetch()) {
            $pdo->prepare("INSERT INTO patients (id_patient) VALUES (:id)")->execute(['id' => $id_utilisateur]);
        }
    }

    /**
     * Page principale : filtres + recherche dynamique (sans liste préchargée)
     */
    public function rechercher() {
    if (!isset($_SESSION['utilisateur_id'])) {
        $this->rediriger('/connexion');
    }
    $this->verifierPatient($_SESSION['utilisateur_id']);

    // Initialiser le token CSRF pour la vue
    Securite::csrfField(); // génère $_SESSION['csrf_token']

    $specialiteModel = new Specialite();
    $specialites = $specialiteModel->tous();
    $villeModel = new Ville();
    $villes = $villeModel->tous();

    $this->afficherVuePrivee('rendezvous/rechercher', [
        'titre'       => 'Prendre un rendez-vous',
        'specialites' => $specialites,
        'villes'      => $villes
    ]);
}

    /**
     * Retourne la liste des médecins filtrés en JSON (appelé par AJAX)
     */
public function rechercherAjax() {
    if (!isset($_SESSION['utilisateur_id'])) {
        echo json_encode([]);
        exit;
    }
    $medecinModel = new Medecin();
    $filtres = [
        'specialite' => $_GET['specialite'] ?? null,
        'ville'      => $_GET['ville'] ?? null,
        'nom'        => $_GET['nom'] ?? null,
    ];
    $medecins = $medecinModel->rechercherAvecProfil($filtres);
    header('Content-Type: application/json');
    echo json_encode($medecins);
    exit;
}

    /**
     * Retourne les créneaux disponibles pour un médecin et une date (JSON)
     */
public function creneauxAjax($id_medecin) {
    if (!isset($_SESSION['utilisateur_id'])) {
        header('Content-Type: application/json');
        echo json_encode([]);
        exit;
    }
    $this->verifierPatient($_SESSION['utilisateur_id']);

    $date = $_GET['date'] ?? date('Y-m-d');
    $rendezVousModel = new RendezVous();
    $creneaux = $rendezVousModel->tousLesCreneaux($id_medecin, $date);

    header('Content-Type: application/json');
    echo json_encode($creneaux);
    exit;
}

    /**
     * Réservation (POST) – compatible AJAX, accepte des fichiers joints
     */
    public function reserver() {
    if (!isset($_SESSION['utilisateur_id'])) {
        $this->rediriger('/connexion');
    }
    $this->verifierPatient($_SESSION['utilisateur_id']);

    // Vérification CSRF adaptée à l'AJAX
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            $this->reponseAjax(false, 'Session expirée, veuillez rafraîchir la page.');
            return;
        }
        // Régénérer le token après usage
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } else {
        $this->rediriger('/prendre-rdv');
    }

    $id_patient = $_SESSION['utilisateur_id'];
    $id_medecin = $_POST['id_medecin'] ?? null;
    $date       = $_POST['date'] ?? null;
    $heure      = $_POST['heure'] ?? null;
    $motif      = trim($_POST['motif'] ?? '');

    if (!$id_medecin || !$date || !$heure) {
        $this->reponseAjax(false, 'Veuillez choisir un créneau valide.');
        return;
    }

    $medecinModel = new Medecin();
    $medecin = $medecinModel->trouverParId($id_medecin);
    if (!$medecin) {
        $this->reponseAjax(false, 'Médecin introuvable.');
        return;
    }
    $id_etablissement = $medecin['id_etablissement'];

    $rendezVousModel = new RendezVous();
    $id_rdv = $rendezVousModel->creer($id_patient, $id_medecin, $id_etablissement, $date, $heure, $motif);

    if ($id_rdv) {
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT reference FROM rendez_vous WHERE id_rdv = :id");
        $stmt->execute(['id' => $id_rdv]);
        $rdv = $stmt->fetch();
        $reference = $rdv['reference'] ?? '';

        // QR Code
        try {
            $qrTexte = "RDV:{$reference} - Dr {$medecin['nom']} {$medecin['prenom']} le {$date} à {$heure}";
            $qrCheminRelatif = "public/assets/images/qrcodes/{$reference}.png";
            $qrCheminAbsolu = __DIR__ . '/../../' . $qrCheminRelatif;
            if (!is_dir(dirname($qrCheminAbsolu))) {
                mkdir(dirname($qrCheminAbsolu), 0755, true);
            }
            QrCodeGenerator::generer($qrTexte, $qrCheminAbsolu);
            $pdo->prepare("INSERT INTO qr_codes (code, id_rdv) VALUES (:code, :id_rdv)")
                ->execute(['code' => $qrCheminRelatif, 'id_rdv' => $id_rdv]);
        } catch (Exception $e) {
            error_log('Erreur QR code : ' . $e->getMessage());
        }

        // === GESTION DES FICHIERS JOINTS ===
        if (!empty($_FILES['documents']['name'][0])) {
            $dossier = 'stockage/documents/';
            if (!is_dir($dossier)) mkdir($dossier, 0755, true);
            $documentModel = new Document();

            foreach ($_FILES['documents']['name'] as $i => $nom) {
                if ($_FILES['documents']['error'][$i] === UPLOAD_ERR_OK) {
                    $extension = pathinfo($nom, PATHINFO_EXTENSION);
                    $nomFichier = time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
                    $chemin = $dossier . $nomFichier;
                    if (move_uploaded_file($_FILES['documents']['tmp_name'][$i], $chemin)) {
                        $documentModel->ajouterPourRdv($id_patient, $id_rdv, $nom, $chemin);
                    }
                }
            }
        }

        // === ASSOCIER LES DOCUMENTS EXISTANTS ===
        if (!empty($_POST['documents_existants']) && is_array($_POST['documents_existants'])) {
            foreach ($_POST['documents_existants'] as $id_doc) {
                $pdo->prepare("UPDATE documents SET id_rdv = :id_rdv WHERE id_document = :id_doc AND id_patient = :id_patient")
                    ->execute(['id_rdv' => $id_rdv, 'id_doc' => $id_doc, 'id_patient' => $id_patient]);
            }
        }

        // Notifications
        $notificationModel = new Notification();
        $messagePatient = "Bonjour, votre rendez-vous du " . date('d/m/Y', strtotime($date)) . " à " . substr($heure, 0, 5) . " a été confirmé. Référence : " . $reference;
        $notificationModel->ajouter($id_patient, 'confirmation', $messagePatient, 'Email');
        $messageMedecin = "Nouveau rendez-vous le " . date('d/m/Y', strtotime($date)) . " à " . substr($heure, 0, 5) . " avec un patient.";
        $notificationModel->ajouter($id_medecin, 'nouveau_rdv', $messageMedecin, 'Email');

        $this->reponseAjax(true, 'Rendez-vous réservé avec succès.');
    } else {
        $this->reponseAjax(false, 'Ce créneau n\'est plus disponible.');
    }
}

    private function reponseAjax($success, $message) {
        if ($this->estAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }
        $_SESSION[$success ? 'succes_rdv' : 'erreur_rdv'] = $message;
        $this->rediriger('/mes-rendezvous');
    }

    private function estAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * Liste des rendez-vous du patient connecté
     */
    public function mesRendezVous() {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }
        $this->verifierPatient($_SESSION['utilisateur_id']);

        $rendezVousModel = new RendezVous();
        $rdvs = $rendezVousModel->pourPatient($_SESSION['utilisateur_id']);

        $teleModel = new Teleconsultation();
        $pdo = BaseDeDonnees::getInstance()->getPdo();

        foreach ($rdvs as &$rdv) {
            $rdv['teleconsultation'] = $teleModel->pourRendezVous($rdv['id_rdv']);
            $stmt = $pdo->prepare("SELECT code FROM qr_codes WHERE id_rdv = :id");
            $stmt->execute(['id' => $rdv['id_rdv']]);
            $qr = $stmt->fetch();
            $rdv['qr_code'] = $qr ? $qr['code'] : null;
        }

        $this->afficherVuePrivee('rendezvous/liste', [
            'titre' => 'Mes rendez-vous',
            'rdvs'  => $rdvs
        ]);
    }

    /**
     * Annuler un rendez-vous
     */
    public function annuler($id_rdv) {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }
        $this->verifierPatient($_SESSION['utilisateur_id']);

        $rendezVousModel = new RendezVous();
        $rdv = $rendezVousModel->trouverParId($id_rdv);
        if (!$rdv || $rdv['id_patient'] != $_SESSION['utilisateur_id']) {
            $_SESSION['message_patient'] = 'Rendez-vous introuvable.';
            $this->rediriger('/mes-rendezvous');
        }

        if ($rdv['statut'] == 'Terminé') {
            $_SESSION['message_patient'] = 'Impossible d\'annuler un rendez-vous déjà terminé.';
            $this->rediriger('/mes-rendezvous');
        }

        if ($rendezVousModel->changerStatut($id_rdv, $_SESSION['utilisateur_id'], 'Annulé')) {
            $notificationModel = new Notification();
            $messageMedecin = "Le rendez-vous du " . date('d/m/Y', strtotime($rdv['date_rdv'])) . " à " . substr($rdv['heure_rdv'], 0, 5) . " a été annulé par le patient.";
            $notificationModel->ajouter($rdv['id_medecin'], 'annulation_rdv', $messageMedecin, 'Email');
            $_SESSION['message_patient'] = 'Rendez-vous annulé avec succès.';
        } else {
            $_SESSION['message_patient'] = 'Une erreur est survenue lors de l\'annulation du rendez-vous.';
        }
        $this->rediriger('/mes-rendezvous');
    }
   
}