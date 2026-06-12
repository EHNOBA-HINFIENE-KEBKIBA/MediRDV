<?php
class Teleconsultation extends Modele {
    protected $table = 'teleconsultations';

    /**
     * Crée ou met à jour le lien de téléconsultation pour un rendez-vous
     */
    public function definirLien($id_rdv, $lien) {
        $stmt = $this->pdo->prepare("SELECT id_tele FROM {$this->table} WHERE id_rdv = :id_rdv");
        $stmt->execute(['id_rdv' => $id_rdv]);
        if ($stmt->fetch()) {
            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET lien = :lien WHERE id_rdv = :id_rdv");
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (lien, date_debut, id_rdv) VALUES (:lien, NOW(), :id_rdv)");
        }
        return $stmt->execute(['lien' => $lien, 'id_rdv' => $id_rdv]);
    }

    /**
     * Récupère la téléconsultation associée à un rendez-vous
     */
    public function pourRendezVous($id_rdv) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_rdv = :id_rdv");
        $stmt->execute(['id_rdv' => $id_rdv]);
        return $stmt->fetch();
    }
}