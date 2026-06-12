<?php
class Statistique {
    private $pdo;

    public function __construct() {
        $this->pdo = BaseDeDonnees::getInstance()->getPdo();
    }

    public function getCounts() {
        return [
            'medecins'       => $this->pdo->query("SELECT COUNT(*) FROM medecins")->fetchColumn(),
            'etablissements' => $this->pdo->query("SELECT COUNT(*) FROM etablissements")->fetchColumn(),
            'patients'       => $this->pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn(),
            'rendezvous'     => $this->pdo->query("SELECT COUNT(*) FROM rendez_vous")->fetchColumn()
        ];
    }
}