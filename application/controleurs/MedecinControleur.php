<?php
class MedecinControleur extends Controleur {

    private function verifierMedecin() {
        if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role_id'] != 3) {
            $this->rediriger('/connexion');
        }
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id_medecin FROM medecins WHERE id_medecin = :id");
        $stmt->execute(['id' => $_SESSION['utilisateur_id']]);
        $medecin = $stmt->fetch();
        if (!$medecin) {
            $this->rediriger('/connexion');
        }
        return $medecin['id_medecin'];
    }

public function agenda() {
    $id_medecin = $this->verifierMedecin();
    $rendezVousModel = new RendezVous();
    $date = $_GET['date'] ?? date('Y-m-d');
    $rdvs = $rendezVousModel->pourMedecin($id_medecin, $date);

    // Téléconsultations pour chaque RDV
    $teleModel = new Teleconsultation();
    foreach ($rdvs as &$rdv) {
        $rdv['teleconsultation'] = $teleModel->pourRendezVous($rdv['id_rdv']);
    }

    // IDs des RDV ayant des documents joints
    $pdo = BaseDeDonnees::getInstance()->getPdo();
    $stmt = $pdo->prepare("SELECT DISTINCT id_rdv FROM documents WHERE id_rdv IN (SELECT id_rdv FROM rendez_vous WHERE id_medecin = :id)");
    $stmt->execute(['id' => $id_medecin]);
    $docIds = array_column($stmt->fetchAll(), 'id_rdv');

    $this->afficherVuePrivee('medecin/agenda', [
        'titre'  => 'Mon agenda',
        'rdvs'   => $rdvs,
        'date'   => $date,
        'docIds' => $docIds
    ]);
}

    public function changerStatut() {
    $id_medecin = $this->verifierMedecin();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_rdv = $_POST['id_rdv'] ?? 0;
        $statut = $_POST['statut'] ?? '';

        $rendezVousModel = new RendezVous();
        $rdv = $rendezVousModel->trouverParId($id_rdv);
        if (!$rdv || $rdv['id_medecin'] != $id_medecin) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Rendez-vous introuvable.']);
            exit;
        }

        if ($rendezVousModel->changerStatut($id_rdv, $id_medecin, $statut)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Statut mis à jour.']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erreur lors du changement de statut.']);
        }
        exit;
    }
    $this->rediriger('/medecin/agenda');
}

// ==================== DISPONIBILITÉS (version enrichie) ====================

public function disponibilites() {
    $id_medecin = $this->verifierMedecin();
    $disponibiliteModel = new Disponibilite();
    $dispos = $disponibiliteModel->pourMedecin($id_medecin);

    // Formater pour l'affichage dans le tableau (1 ligne par jour)
    $dispoParJour = [];
    foreach ($dispos as $d) {
        $dispoParJour[$d['jour']] = $d;
    }

    // Récupérer les exceptions
    $pdo = BaseDeDonnees::getInstance()->getPdo();
    $stmt = $pdo->prepare("SELECT * FROM exceptions WHERE id_medecin = :id ORDER BY date_exception ASC");
    $stmt->execute(['id' => $id_medecin]);
    $exceptions = $stmt->fetchAll();

    $message = $_SESSION['message_dispo'] ?? '';
    unset($_SESSION['message_dispo']);

    $this->afficherVuePrivee('medecin/disponibilites', [
        'titre'        => 'Mes disponibilités',
        'dispoParJour' => $dispoParJour,
        'exceptions'   => $exceptions,
        'message'      => $message
    ]);
}

public function enregistrerDisponibilites() {
    $id_medecin = $this->verifierMedecin();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        // Supprimer toutes les disponibilités existantes du médecin
        $pdo->prepare("DELETE FROM disponibilites WHERE id_medecin = :id")->execute(['id' => $id_medecin]);

        // Réinsérer les nouvelles disponibilités à partir du formulaire
        for ($i = 1; $i <= 7; $i++) {
            $actif = isset($_POST["actif_{$i}"]) ? 1 : 0;
            if ($actif) {
                $jour = $_POST["jour_{$i}"] ?? '';
                $debut = $_POST["heure_debut_{$i}"] ?? '';
                $fin = $_POST["heure_fin_{$i}"] ?? '';
                $duree = $_POST["duree_{$i}"] ?? 30;
                if ($jour && $debut && $fin) {
                    $stmt = $pdo->prepare("INSERT INTO disponibilites (id_medecin, jour, heure_debut, heure_fin, duree_consultation) VALUES (:id, :jour, :debut, :fin, :duree)");
                    $stmt->execute(['id' => $id_medecin, 'jour' => $jour, 'debut' => $debut, 'fin' => $fin, 'duree' => $duree]);
                }
            }
        }
        $_SESSION['message_dispo'] = 'Disponibilités enregistrées avec succès.';
    }
    $this->rediriger('/medecin/disponibilites');
}

// ==================== EXCEPTIONS ====================

public function ajouterException() {
    $id_medecin = $this->verifierMedecin();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date = $_POST['date_exception'] ?? '';
        $type = $_POST['type'] ?? 'indisponible';
        $motif = $_POST['motif'] ?? '';
        if ($date) {
            $pdo = BaseDeDonnees::getInstance()->getPdo();
            $stmt = $pdo->prepare("INSERT INTO exceptions (id_medecin, date_exception, type, motif) VALUES (:id, :date, :type, :motif)");
            $stmt->execute(['id' => $id_medecin, 'date' => $date, 'type' => $type, 'motif' => $motif]);
            $_SESSION['message_dispo'] = 'Exception ajoutée.';
        }
    }
    $this->rediriger('/medecin/disponibilites');
}

public function supprimerException($id) {
    $id_medecin = $this->verifierMedecin();
    $pdo = BaseDeDonnees::getInstance()->getPdo();
    $stmt = $pdo->prepare("DELETE FROM exceptions WHERE id_exception = :id AND id_medecin = :id_medecin");
    $stmt->execute(['id' => $id, 'id_medecin' => $id_medecin]);
    $_SESSION['message_dispo'] = 'Exception supprimée.';
    $this->rediriger('/medecin/disponibilites');
}
    public function listePublique() {
    $medecinModel = new Medecin();
    $specialiteModel = new Specialite();
    $villeModel = new Ville();

    $filtres = [
        'specialite' => $_GET['specialite'] ?? null,
        'ville'      => $_GET['ville'] ?? null,
        'nom'        => $_GET['nom'] ?? null,
    ];
    $medecins = $medecinModel->rechercher($filtres);
    $specialites = $specialiteModel->tous();
    $villes = $villeModel->tous();

    // Utiliser le gabarit public avec le fichier unique
    $this->afficherVue('medecins_public', [
        'titre'       => 'Trouver un médecin',
        'medecins'    => $medecins,
        'specialites' => $specialites,
        'villes'      => $villes,
        'filtres'     => $filtres
    ]);
}
public function mesPatients() {
    $id_medecin = $this->verifierMedecin();
    $recherche = $_GET['recherche'] ?? '';

    $rendezVousModel = new RendezVous();
    $patients = $rendezVousModel->patientsDuMedecin($id_medecin, $recherche);

    $this->afficherVuePrivee('medecin/patients', [
        'titre'    => 'Mes patients',
        'patients' => $patients,
        'recherche'=> $recherche
    ]);
}

public function sauvegarderObservations() {
    $id_medecin = $this->verifierMedecin();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_rdv = $_POST['id_rdv'] ?? 0;
        $observations = $_POST['observations'] ?? '';

        // Vérifier que le RDV appartient bien au médecin
        $rendezVousModel = new RendezVous();
        $rdv = $rendezVousModel->trouverParId($id_rdv);
        $success = false;
        $message = '';

        if ($rdv && $rdv['id_medecin'] == $id_medecin) {
            if ($rendezVousModel->sauvegarderObservations($id_rdv, $observations)) {
                $success = true;
                $message = 'Observations enregistrées.';
            } else {
                $message = 'Erreur lors de l\'enregistrement.';
            }
        } else {
            $message = 'Rendez-vous non trouvé ou non autorisé.';
        }

        if ($this->estAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }
        // Fallback non AJAX (peu probable)
        $this->rediriger('/medecin/agenda');
    }
}

private function estAjax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

public function modifierRdv($id_rdv) {
    $id_medecin = $this->verifierMedecin();
    $rendezVousModel = new RendezVous();
    $rdv = $rendezVousModel->trouverParId($id_rdv); // utilise la méthode spécifique

    if (!$rdv || $rdv['id_medecin'] != $id_medecin) {
        $_SESSION['message_medecin'] = 'Rendez-vous introuvable ou non autorisé.';
        $this->rediriger('/medecin/agenda');
    }

    $patient = (new Utilisateur())->trouverParId($rdv['id_patient']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $donnees = [
            'date_rdv'  => $_POST['date_rdv'] ?? $rdv['date_rdv'],
            'heure_rdv' => $_POST['heure_rdv'] ?? $rdv['heure_rdv'],
            'motif'     => $_POST['motif'] ?? $rdv['motif'],
            'statut'    => $_POST['statut'] ?? $rdv['statut']
        ];
        if ($rendezVousModel->modifierRdvComplet($id_rdv, $donnees)) {
            $_SESSION['message_medecin'] = 'Rendez-vous modifié avec succès.';
        } else {
            $_SESSION['message_medecin'] = 'Erreur lors de la modification.';
        }
        $this->rediriger('/medecin/agenda');
    }

    $this->afficherVuePrivee('medecin/modifier_rdv', [
        'titre'   => 'Modifier le rendez-vous',
        'rdv'     => $rdv,
        'patient' => $patient
    ]);
}

/**
 * Affiche les documents liés à un rendez-vous (accessible uniquement par le médecin concerné)
 */
public function documentsRdv($id_rdv) {
    $id_medecin = $this->verifierMedecin();
    $rendezVousModel = new RendezVous();
    $rdv = $rendezVousModel->trouverParId($id_rdv);
    if (!$rdv || $rdv['id_medecin'] != $id_medecin) {
        $_SESSION['message_medecin'] = 'Rendez-vous introuvable.';
        $this->rediriger('/medecin/agenda');
    }

    $documentModel = new Document();
    $documents = $documentModel->pourRdv($id_rdv); // à créer

    $this->afficherVuePrivee('medecin/documents_rdv', [
        'titre'     => 'Documents du rendez-vous',
        'rdv'       => $rdv,
        'documents' => $documents
    ]);
}
/**
 * Affiche le dossier médical complet d'un patient (accessible au médecin qui a déjà vu ce patient)
 */
public function dossierPatient($id_patient) {
    $id_medecin = $this->verifierMedecin();
    // Vérifier que le médecin a déjà eu ce patient
    $rendezVousModel = new RendezVous();
    $patients = $rendezVousModel->patientsDuMedecin($id_medecin);
    $autorise = false;
    foreach ($patients as $p) {
        if ($p['id_utilisateur'] == $id_patient) {
            $autorise = true;
            break;
        }
    }
    if (!$autorise) {
        $_SESSION['message_medecin'] = 'Patient non trouvé ou non autorisé.';
        $this->rediriger('/medecin/patients');
    }

    $utilisateurModel = new Utilisateur();
    $patientInfo = $utilisateurModel->trouverParId($id_patient);

    // Récupérer aussi les infos supplémentaires de la table patients
    $pdo = BaseDeDonnees::getInstance()->getPdo();
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE id_patient = :id");
    $stmt->execute(['id' => $id_patient]);
    $patientExtra = $stmt->fetch();
    if ($patientExtra) {
        $patientInfo = array_merge($patientInfo, $patientExtra);
    }

    $documentModel = new Document();
    $documents = $documentModel->pourPatient($id_patient);

    // Historique des consultations
    $consultationModel = new Consultation();
    $consultations = $consultationModel->pourPatient($id_patient);

    $this->afficherVuePrivee('medecin/dossier_patient', [
        'titre'         => 'Dossier de ' . $patientInfo['prenom'] . ' ' . $patientInfo['nom'],
        'patient'       => $patientInfo,
        'documents'     => $documents,
        'consultations' => $consultations
    ]);
}
/**
 * Page de report d'un rendez-vous (médecin)
 */
public function reporterRdv($id_rdv) {
    $id_medecin = $this->verifierMedecin();
    $rendezVousModel = new RendezVous();
    $rdv = $rendezVousModel->trouverParId($id_rdv);
    if (!$rdv || $rdv['id_medecin'] != $id_medecin) {
        $_SESSION['message_medecin'] = 'Rendez-vous introuvable.';
        $this->rediriger('/medecin/agenda');
    }
    $patient = (new Utilisateur())->trouverParId($rdv['id_patient']);

    $this->afficherVuePrivee('medecin/reporter_rdv', [
        'titre'   => 'Reporter le rendez-vous',
        'rdv'     => $rdv,
        'patient' => $patient
    ]);
}

/**
 * Enregistrement du report (POST)
 */
public function enregistrerReport($id_rdv) {
    $id_medecin = $this->verifierMedecin();
    $rendezVousModel = new RendezVous();
    $rdv = $rendezVousModel->trouverParId($id_rdv);
    if (!$rdv || $rdv['id_medecin'] != $id_medecin) {
        $_SESSION['message_medecin'] = 'Rendez-vous introuvable.';
        $this->rediriger('/medecin/agenda');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nouvelleDate = $_POST['nouvelle_date'] ?? '';
        $nouvelleHeure = $_POST['nouvelle_heure'] ?? '';
        $messageReport = trim($_POST['message_report'] ?? '');

        if (empty($nouvelleDate) || empty($nouvelleHeure)) {
            $_SESSION['message_medecin'] = 'Veuillez fournir une nouvelle date et heure.';
            $this->rediriger('/medecin/reporter-rdv/' . $id_rdv);
            return;
        }

        // Mettre à jour le rendez-vous
        $rendezVousModel->modifierRdvComplet($id_rdv, [
            'date_rdv'  => $nouvelleDate,
            'heure_rdv' => $nouvelleHeure,
            'statut'    => 'Reporté',
            'motif'     => $rdv['motif'] // on conserve le motif initial, on pourrait ajouter un champ dédié
        ]);

        // Envoyer une notification au patient
        $notificationModel = new Notification();
        $patientMsg = "Votre rendez-vous du " . date('d/m/Y', strtotime($rdv['date_rdv'])) . " à " . substr($rdv['heure_rdv'], 0, 5) . " a été reporté au " . date('d/m/Y', strtotime($nouvelleDate)) . " à " . substr($nouvelleHeure, 0, 5) . ". Motif : " . ($messageReport ?: 'Non précisé');
        $notificationModel->ajouter($rdv['id_patient'], 'report_rdv', $patientMsg, 'Email');

        $_SESSION['message_medecin'] = 'Rendez-vous reporté et patient notifié.';
    }
    $this->rediriger('/medecin/agenda');
}
}