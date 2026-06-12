<?php
class Patient extends Modele {
    protected $table = 'patients';

    public function rechercher($terme) {
        $sql = "SELECT u.id_utilisateur, u.nom, u.prenom, u.email, u.telephone, p.date_naissance
                FROM {$this->table} p
                JOIN utilisateurs u ON p.id_patient = u.id_utilisateur
                WHERE u.nom LIKE :terme OR u.prenom LIKE :terme OR u.email LIKE :terme
                ORDER BY u.nom
                LIMIT 20";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['terme' => "%$terme%"]);
        return $stmt->fetchAll();
    }
}