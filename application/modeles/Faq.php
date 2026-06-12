<?php
class Faq extends Modele {
    protected $table = 'faq';

    public function toutes() {
        return $this->pdo->query("SELECT * FROM {$this->table} ORDER BY ordre")->fetchAll();
    }

    public function ajouter($question, $reponse) {
        $max = $this->pdo->query("SELECT MAX(ordre) FROM {$this->table}")->fetchColumn();
        $ordre = ($max ?? 0) + 1;
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (question, reponse, ordre) VALUES (:q, :r, :o)");
        return $stmt->execute(['q' => $question, 'r' => $reponse, 'o' => $ordre]);
    }

    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_faq = :id");
        return $stmt->execute(['id' => $id]);
    }
}