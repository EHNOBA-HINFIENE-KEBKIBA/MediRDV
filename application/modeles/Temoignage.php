<?php
class Temoignage extends Modele {
    protected $table = 'temoignages';

    public function tousActifs() {
        return $this->pdo->query("SELECT * FROM {$this->table} WHERE actif = 1 ORDER BY date_creation DESC")->fetchAll();
    }

    public function tous() {
        return $this->pdo->query("SELECT * FROM {$this->table} ORDER BY date_creation DESC")->fetchAll();
    }

    public function ajouter($nom, $contenu, $note, $profession = null) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nom, profession, contenu, note) VALUES (:nom, :prof, :contenu, :note)");
        return $stmt->execute(['nom' => $nom, 'prof' => $profession, 'contenu' => $contenu, 'note' => $note]);
    }

    public function basculerActif($id) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET actif = NOT actif WHERE id_temoignage = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_temoignage = :id");
        return $stmt->execute(['id' => $id]);
    }
}