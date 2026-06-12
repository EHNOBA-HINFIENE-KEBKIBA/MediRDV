<?php
class Partenaire extends Modele {
    protected $table = 'partenaires';

    public function tous() {
        return $this->pdo->query("SELECT * FROM {$this->table} ORDER BY nom")->fetchAll();
    }

    public function ajouter($nom, $logo = null, $site_web = null) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nom, logo, site_web) VALUES (:nom, :logo, :site)");
        return $stmt->execute(['nom' => $nom, 'logo' => $logo, 'site' => $site_web]);
    }

    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_partenaire = :id");
        return $stmt->execute(['id' => $id]);
    }
}