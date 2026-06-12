<?php
class PaiementControleur extends Controleur {

    // ========== PATIENT ==========
    /**
     * Liste des paiements du patient connecté
     */
    public function mesPaiements() {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }
        $paiementModel = new Paiement();
        $paiements = $paiementModel->pourPatient($_SESSION['utilisateur_id']);

        $this->afficherVuePrivee('paiement/patient_liste', [
            'titre'     => 'Mes paiements',
            'paiements' => $paiements
        ]);
    }

    /**
     * Formulaire de paiement pour un rendez‑vous donné
     */
    public function payer($id_rdv) {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }
        // Vérifier que le RDV appartient au patient
        $rendezVousModel = new RendezVous();
        $rdv = $rendezVousModel->trouverParId($id_rdv);
        if (!$rdv || $rdv['id_patient'] != $_SESSION['utilisateur_id']) {
            $this->rediriger('/mes-rendezvous');
        }

        // Vérifier si déjà payé
        $paiementModel = new Paiement();
        $existant = $paiementModel->pourPatient($_SESSION['utilisateur_id']);
        foreach ($existant as $p) {
            if ($p['id_rdv'] == $id_rdv) {
                $_SESSION['message_paiement'] = 'Ce rendez‑vous est déjà payé.';
                $this->rediriger('/paiements');
                return;
            }
        }

        $this->afficherVuePrivee('paiement/patient_payer', [
            'titre' => 'Payer un rendez‑vous',
            'rdv'   => $rdv
        ]);
    }

    /**
     * Traitement du paiement (simulation)
     */
    public function traiterPaiement() {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_rdv   = $_POST['id_rdv'] ?? 0;
            $montant  = floatval($_POST['montant'] ?? 0);
            $mode     = $_POST['mode'] ?? 'Espèces';

            $rendezVousModel = new RendezVous();
            $rdv = $rendezVousModel->trouverParId($id_rdv);
            if ($rdv && $rdv['id_patient'] == $_SESSION['utilisateur_id'] && $montant > 0) {
                $paiementModel = new Paiement();
                $paiementModel->ajouter($id_rdv, $montant, $mode);
                $_SESSION['message_paiement'] = 'Paiement effectué avec succès.';
            } else {
                $_SESSION['erreur_paiement'] = 'Erreur lors du paiement.';
            }
        }
        $this->rediriger('/paiements');
    }

    // ========== RÉCEPTIONNISTE / ADMIN ==========
    /**
     * Liste des paiements de l'établissement (pour réceptionniste/admin)
     */
    public function listeEtablissement() {
        if (!isset($_SESSION['utilisateur_id']) || !in_array($_SESSION['role_id'], [1,2,4])) {
            $this->rediriger('/connexion');
        }
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id_etablissement FROM utilisateurs WHERE id_utilisateur = :id");
        $stmt->execute(['id' => $_SESSION['utilisateur_id']]);
        $user = $stmt->fetch();
        $id_etablissement = $user['id_etablissement'] ?? 0;

        $paiementModel = new Paiement();
        $paiements = $paiementModel->pourEtablissement($id_etablissement);

        $this->afficherVuePrivee('paiement/admin_liste', [
            'titre'     => 'Paiements de l\'établissement',
            'paiements' => $paiements
        ]);
    }
}