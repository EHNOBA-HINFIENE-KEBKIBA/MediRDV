<?php
class Role extends Modele {
    protected $table = 'roles';

    public function tousSauf(...$exclus) {
        $placeholders = implode(',', array_fill(0, count($exclus), '?'));
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE libelle NOT IN ($placeholders)");
        $stmt->execute($exclus);
        return $stmt->fetchAll();
    }
}