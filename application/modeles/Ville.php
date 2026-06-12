<?php
class Ville extends Modele {
    protected $table = 'villes';

    public function tous() {
        return $this->pdo->query("SELECT * FROM {$this->table} ORDER BY nom")->fetchAll();
    }

    public function ajouter($nom, $pays = 'Cameroun') {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nom, pays) VALUES (:nom, :pays)");
        return $stmt->execute(['nom' => $nom, 'pays' => $pays]);
    }

    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_ville = :id");
        return $stmt->execute(['id' => $id]);
    }
}