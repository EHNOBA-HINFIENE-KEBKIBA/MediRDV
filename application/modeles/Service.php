<?php
class Service extends Modele {
    protected $table = 'services';

    public function tous() {
        return $this->pdo->query("SELECT * FROM {$this->table} ORDER BY nom")->fetchAll();
    }

    public function ajouter($nom, $description = null) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nom, description) VALUES (:nom, :description)");
        return $stmt->execute(['nom' => $nom, 'description' => $description]);
    }

    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_service = :id");
        return $stmt->execute(['id' => $id]);
    }
}