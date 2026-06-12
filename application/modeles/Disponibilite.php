<?php
class Disponibilite extends Modele {
    protected $table = 'disponibilites';

    public function pourMedecin($id_medecin) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_medecin = :id_medecin ORDER BY FIELD(jour, 'Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'), heure_debut");
        $stmt->execute(['id_medecin' => $id_medecin]);
        return $stmt->fetchAll();
    }

    public function ajouter($id_medecin, $jour, $heure_debut, $heure_fin) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (id_medecin, jour, heure_debut, heure_fin) VALUES (:id_medecin, :jour, :debut, :fin)");
        return $stmt->execute([
            'id_medecin' => $id_medecin,
            'jour' => $jour,
            'debut' => $heure_debut,
            'fin' => $heure_fin
        ]);
    }

    public function supprimer($id_disponibilite, $id_medecin) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_disponibilite = :id AND id_medecin = :id_medecin");
        return $stmt->execute(['id' => $id_disponibilite, 'id_medecin' => $id_medecin]);
    }
}