<?php
class Teleconsultation extends Modele {
    protected $table = 'teleconsultations';

    /**
     * Active la téléconsultation pour un RDV : génère le lien s'il n'existe pas
     * et passe le statut à 'active'. Retourne le lien.
     */
    public function activer($id_rdv, $reference) {
        $lien = 'https://meet.jit.si/MediRDV-' . $reference;
        $stmt = $this->pdo->prepare("SELECT id_tele FROM {$this->table} WHERE id_rdv = :id_rdv");
        $stmt->execute(['id_rdv' => $id_rdv]);
        $existe = $stmt->fetch();
        if ($existe) {
            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET lien = :lien, statut = 'active' WHERE id_rdv = :id_rdv");
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (lien, statut, id_rdv) VALUES (:lien, 'active', :id_rdv)");
        }
        $stmt->execute(['lien' => $lien, 'id_rdv' => $id_rdv]);
        return $lien;
    }

    public function pourRendezVous($id_rdv) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_rdv = :id_rdv");
        $stmt->execute(['id_rdv' => $id_rdv]);
        return $stmt->fetch();
    }
}