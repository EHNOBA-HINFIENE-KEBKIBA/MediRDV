<?php
class HistoriqueAction extends Modele {
    protected $table = 'historiques_actions';

    public function enregistrer($id_utilisateur, $action, $details = '') {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (id_utilisateur, action, details) VALUES (:id, :action, :details)");
        return $stmt->execute([
            'id'      => $id_utilisateur ?: null,
            'action'  => $action,
            'details' => $details
        ]);
    }
}