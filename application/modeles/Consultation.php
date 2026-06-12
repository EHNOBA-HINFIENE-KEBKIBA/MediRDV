<?php
class Consultation extends Modele {
    protected $table = 'consultations';

    /**
     * Crée ou met à jour une consultation pour un rendez-vous
     */
    public function creerOuMettreAJour($id_rdv, $diagnostic, $prescription, $notes) {
        $stmt = $this->pdo->prepare("SELECT id_consultation FROM {$this->table} WHERE id_rdv = :id_rdv");
        $stmt->execute(['id_rdv' => $id_rdv]);
        $existe = $stmt->fetch();

        if ($existe) {
            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET diagnostic = :diag, prescription = :pres, notes_medicales = :notes, updated_at = NOW() WHERE id_rdv = :id_rdv");
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (id_rdv, diagnostic, prescription, notes_medicales) VALUES (:id_rdv, :diag, :pres, :notes)");
        }
        return $stmt->execute([
            'id_rdv' => $id_rdv,
            'diag'   => $diagnostic,
            'pres'   => $prescription,
            'notes'  => $notes
        ]);
    }

    /**
     * Récupère la consultation associée à un rendez-vous
     */
    public function pourRendezVous($id_rdv) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_rdv = :id_rdv");
        $stmt->execute(['id_rdv' => $id_rdv]);
        return $stmt->fetch();
    }

    /**
     * Consultations d'un patient (via ses rendez-vous)
     */
    public function pourPatient($id_patient) {
        $sql = "SELECT c.*, r.reference, r.date_rdv, r.heure_rdv, u.nom as medecin_nom, u.prenom as medecin_prenom
                FROM {$this->table} c
                JOIN rendez_vous r ON c.id_rdv = r.id_rdv
                JOIN medecins m ON r.id_medecin = m.id_medecin
                JOIN utilisateurs u ON m.id_medecin = u.id_utilisateur
                WHERE r.id_patient = :id_patient
                ORDER BY c.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_patient' => $id_patient]);
        return $stmt->fetchAll();
    }
}