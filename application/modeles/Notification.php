<?php
class Notification extends Modele {
    protected $table = 'notifications';

    /**
     * Ajoute une notification à envoyer
     */
    public function ajouter($id_utilisateur, $type, $contenu, $canal = 'Email') {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (id_utilisateur, type, contenu, canal) VALUES (:id, :type, :contenu, :canal)");
        return $stmt->execute([
            'id'      => $id_utilisateur,
            'type'    => $type,
            'contenu' => $contenu,
            'canal'   => $canal
        ]);
    }

    /**
     * Récupère les notifications en attente d'envoi
     */
    public function aEnvoyer($limite = 50) {
        $stmt = $this->pdo->prepare("SELECT n.*, u.email, u.nom, u.prenom FROM {$this->table} n JOIN utilisateurs u ON n.id_utilisateur = u.id_utilisateur WHERE n.date_envoi IS NULL LIMIT :limite");
        $stmt->bindValue('limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Marque une notification comme envoyée
     */
    public function marquerEnvoyee($id) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET date_envoi = NOW() WHERE id_notif = :id");
        return $stmt->execute(['id' => $id]);
    }
}