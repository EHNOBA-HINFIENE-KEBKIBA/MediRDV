<?php
class Modele {
    protected $pdo;
    protected $table; // sera défini dans les classes filles

    public function __construct() {
        $this->pdo = BaseDeDonnees::getInstance()->getPdo();
    }

    // Récupère tous les enregistrements
    public function tous() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    // Trouve un enregistrement par son ID
    public function trouverParId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Insère un enregistrement (à compléter selon les besoins)
    // Met à jour...
    // Supprime...
}