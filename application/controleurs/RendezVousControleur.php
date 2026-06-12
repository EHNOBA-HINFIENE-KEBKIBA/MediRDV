<?php
require_once __DIR__ . '/../helpers/QrCodeGenerator.php';

class RendezVousControleur extends Controleur {

    /**
     * Vérifie que le patient existe dans la table patients, sinon le crée.
     */
    private function verifierPatient($id_utilisateur) {
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id_patient FROM patients WHERE id_patient = :id");
        $stmt->execute(['id' => $id_utilisateur]);
        if (!$stmt->fetch()) {
            $pdo->prepare("INSERT INTO patients (id_patient) VALUES (:id)")->execute(['id' => $id_utilisateur]);
        }
    }

    /**
     * Étape 1 : Rechercher un médecin / établissement
     */
    public function rechercher() {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }
        $this->verifierPatient($_SESSION['utilisateur_id']);

        $medecinModel = new Medecin();
        $medecins = $medecinModel->tousAvecSpecialite();

        $this->afficherVuePrivee('rendezvous/rechercher', [
            'titre'    => 'Prendre un rendez-vous',
            'medecins' => $medecins
        ]);
    }

    /**
     * Étape 2 : Choisir une date et un créneau pour un médecin donné
     */
    public function choisir($id_medecin) {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }
        $this->verifierPatient($_SESSION['utilisateur_id']);

        $medecinModel = new Medecin();
        $medecin = $medecinModel->trouverAvecSpecialite($id_medecin);
        if (!$medecin) {
            $_SESSION['erreur_rdv'] = 'Médecin introuvable.';
            $this->rediriger('/prendre-rdv');
            return;
        }

        $rendezVousModel = new RendezVous();
        $date = $_GET['date'] ?? date('Y-m-d');
        $creneaux = $rendezVousModel->creneauxDisponibles($id_medecin, $date);

        $this->afficherVuePrivee('rendezvous/choisir', [
            'titre'    => 'Choisir un créneau',
            'medecin'  => $medecin,
            'date'     => $date,
            'creneaux' => $creneaux
        ]);
    }

    /**
     * Étape 3 : Confirmer et enregistrer le rendez-vous
     */
     public function reserver() {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
    }
        $this->verifierPatient($_SESSION['utilisateur_id']);
        Securite::verifierCsrf();  // <-- AJOUT


        // Accepter uniquement les requêtes POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->rediriger('/prendre-rdv');
        }

        // Récupération des données
        $id_patient = $_SESSION['utilisateur_id'];
        $id_medecin = $_POST['id_medecin'] ?? null;
        $date       = $_POST['date'] ?? null;
        $heure      = $_POST['heure'] ?? null;
        $motif      = trim($_POST['motif'] ?? '');

        // Validation des champs obligatoires
        if (!$id_medecin || !$date || !$heure) {
            $_SESSION['erreur_rdv'] = 'Veuillez choisir un créneau valide.';
            $this->rediriger('/prendre-rdv/choisir/' . ($id_medecin ?: 0) . '?date=' . ($date ?: date('Y-m-d')));
            return;
        }

        // Récupérer l'établissement du médecin
        $medecinModel = new Medecin();
        $medecin = $medecinModel->trouverParId($id_medecin);
        if (!$medecin) {
            $_SESSION['erreur_rdv'] = 'Médecin introuvable.';
            $this->rediriger('/prendre-rdv');
            return;
        }
        $id_etablissement = $medecin['id_etablissement'];

        // Créer le rendez-vous
        $rendezVousModel = new RendezVous();
        $id_rdv = $rendezVousModel->creer($id_patient, $id_medecin, $id_etablissement, $date, $heure, $motif);

        if ($id_rdv) {
            // Récupérer la référence du rendez-vous
            $pdo = BaseDeDonnees::getInstance()->getPdo();
            $stmt = $pdo->prepare("SELECT reference FROM rendez_vous WHERE id_rdv = :id");
            $stmt->execute(['id' => $id_rdv]);
            $rdv = $stmt->fetch();
            $reference = $rdv['reference'] ?? '';

            // === GÉNÉRATION DU QR CODE (AVANT la redirection) ===
            try {
                $qrTexte = "RDV:{$reference} - Dr {$medecin['nom']} {$medecin['prenom']} le {$date} à {$heure}";
                $qrCheminRelatif = "public/assets/images/qrcodes/{$reference}.png";
                $qrCheminAbsolu = __DIR__ . '/../../' . $qrCheminRelatif;

                // S'assurer que le dossier existe
                $dossier = dirname($qrCheminAbsolu);
                if (!is_dir($dossier)) {
                    mkdir($dossier, 0755, true);
                }

                QrCodeGenerator::generer($qrTexte, $qrCheminAbsolu);

                // Enregistrer en base (chemin relatif)
                $pdo->prepare("INSERT INTO qr_codes (code, id_rdv) VALUES (:code, :id_rdv)")
                    ->execute(['code' => $qrCheminRelatif, 'id_rdv' => $id_rdv]);
            } catch (Exception $e) {
                // Loguer l'erreur si nécessaire, mais ne pas bloquer la réservation
                error_log('Erreur QR code : ' . $e->getMessage());
            }

            // Ajouter les notifications
            $notificationModel = new Notification();
            // Pour le patient
            $messagePatient = "Bonjour, votre rendez-vous du " . date('d/m/Y', strtotime($date)) . " à " . substr($heure, 0, 5) . " a été confirmé. Référence : " . $reference;
            $notificationModel->ajouter($id_patient, 'confirmation', $messagePatient, 'Email');

            // Pour le médecin
            $messageMedecin = "Nouveau rendez-vous le " . date('d/m/Y', strtotime($date)) . " à " . substr($heure, 0, 5) . " avec un patient.";
            $notificationModel->ajouter($id_medecin, 'nouveau_rdv', $messageMedecin, 'Email');

            $_SESSION['succes_rdv'] = 'Votre rendez-vous a été réservé avec succès.';
            $this->rediriger('/mes-rendezvous');
        } else {
            $_SESSION['erreur_rdv'] = 'Ce créneau n\'est plus disponible.';
            $this->rediriger('/prendre-rdv/choisir/' . $id_medecin . '?date=' . $date);
        }
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

            // Récupérer le QR code associé au rendez-vous
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
            // Ajouter une notification d'annulation pour le médecin
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