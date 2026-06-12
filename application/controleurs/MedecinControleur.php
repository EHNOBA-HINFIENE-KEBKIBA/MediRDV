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

        $this->afficherVuePrivee('medecin/agenda', [
            'titre' => 'Mon agenda',
            'rdvs' => $rdvs,
            'date' => $date
        ]);
    }

    public function changerStatut() {
        $id_medecin = $this->verifierMedecin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_rdv = $_POST['id_rdv'] ?? null;
            $statut = $_POST['statut'] ?? null;
            $statuts_autorises = ['Confirmé', 'Reporté', 'Annulé', 'Terminé'];
            if ($id_rdv && in_array($statut, $statuts_autorises)) {
                $rendezVousModel = new RendezVous();
                $rendezVousModel->changerStatut($id_rdv, $id_medecin, $statut);
            }
        }
        $this->rediriger('/medecin/agenda');
    }

    public function disponibilites() {
        $id_medecin = $this->verifierMedecin();
        $disponibiliteModel = new Disponibilite();
        $dispos = $disponibiliteModel->pourMedecin($id_medecin);
        $message = $_SESSION['message_dispo'] ?? '';
        unset($_SESSION['message_dispo']);

        $this->afficherVuePrivee('medecin/disponibilites', [
            'titre' => 'Mes disponibilités',
            'dispos' => $dispos,
            'message' => $message
        ]);
    }

    public function ajouterDisponibilite() {
        $id_medecin = $this->verifierMedecin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jour = $_POST['jour'] ?? '';
            $debut = $_POST['heure_debut'] ?? '';
            $fin = $_POST['heure_fin'] ?? '';
            if ($jour && $debut && $fin && $debut < $fin) {
                $disponibiliteModel = new Disponibilite();
                $ok = $disponibiliteModel->ajouter($id_medecin, $jour, $debut, $fin);
                $_SESSION['message_dispo'] = $ok ? 'Créneau ajouté.' : 'Erreur lors de l\'ajout.';
            } else {
                $_SESSION['message_dispo'] = 'Données invalides.';
            }
        }
        $this->rediriger('/medecin/disponibilites');
    }

    public function supprimerDisponibilite($id_disponibilite) {
        $id_medecin = $this->verifierMedecin();
        $disponibiliteModel = new Disponibilite();
        $disponibiliteModel->supprimer($id_disponibilite, $id_medecin);
        $_SESSION['message_dispo'] = 'Créneau supprimé.';
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
    $rendezVousModel = new RendezVous();
    $patients = $rendezVousModel->patientsDuMedecin($id_medecin);

    $this->afficherVuePrivee('medecin/patients', [
        'titre' => 'Mes patients',
        'patients' => $patients
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
}