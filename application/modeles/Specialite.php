<?php
class Specialite extends Modele {
    protected $table = 'specialites';

    public function tous() {
        return $this->pdo->query("SELECT * FROM {$this->table} ORDER BY nom")->fetchAll();
    }

    public function ajouter($nom) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nom) VALUES (:nom)");
        return $stmt->execute(['nom' => $nom]);
    }

    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_specialite = :id");
        return $stmt->execute(['id' => $id]);
    }
}