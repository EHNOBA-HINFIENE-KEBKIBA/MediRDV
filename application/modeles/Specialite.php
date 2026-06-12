<?php
class Specialite extends Modele {
    protected $table = 'specialites';

    public function tous() {
        return $this->pdo->query("SELECT * FROM {$this->table} ORDER BY nom")->fetchAll();
    }
}