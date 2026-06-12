<?php
class Medecin extends Modele {
    protected $table = 'medecins';

    public function tousAvecSpecialite() {
        $sql = "SELECT m.*, u.nom, u.prenom, s.nom as specialite_nom 
                FROM medecins m 
                JOIN utilisateurs u ON m.id_medecin = u.id_utilisateur
                JOIN specialites s ON m.id_specialite = s.id_specialite";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function trouverAvecSpecialite($id) {
        $sql = "SELECT m.*, u.nom, u.prenom, s.nom as specialite_nom 
                FROM medecins m 
                JOIN utilisateurs u ON m.id_medecin = u.id_utilisateur
                JOIN specialites s ON m.id_specialite = s.id_specialite
                WHERE m.id_medecin = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function trouverParId($id) {
        // Surcharge pour récupérer aussi l'id_etablissement
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_medecin = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    public function rechercher($filtres = []) {
    $sql = "SELECT m.*, u.nom, u.prenom, u.telephone, u.email, s.nom as specialite_nom, e.nom as etablissement_nom, v.nom as ville_nom
            FROM medecins m 
            JOIN utilisateurs u ON m.id_medecin = u.id_utilisateur
            JOIN specialites s ON m.id_specialite = s.id_specialite
            LEFT JOIN etablissements e ON m.id_etablissement = e.id_etablissement
            LEFT JOIN villes v ON e.id_ville = v.id_ville
            WHERE 1=1";
    $params = [];
    if (!empty($filtres['specialite'])) {
        $sql .= " AND m.id_specialite = :specialite";
        $params['specialite'] = $filtres['specialite'];
    }
    if (!empty($filtres['ville'])) {
        $sql .= " AND e.id_ville = :ville";
        $params['ville'] = $filtres['ville'];
    }
    if (!empty($filtres['nom'])) {
        $sql .= " AND (u.nom LIKE :nom OR u.prenom LIKE :nom2)";
        $params['nom'] = '%' . $filtres['nom'] . '%';
        $params['nom2'] = '%' . $filtres['nom'] . '%';
    }
    $sql .= " ORDER BY u.nom, u.prenom";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
public function parEtablissement($id_etablissement) {
    $sql = "SELECT m.id_medecin, u.nom, u.prenom, s.nom as specialite_nom
            FROM medecins m
            JOIN utilisateurs u ON m.id_medecin = u.id_utilisateur
            JOIN specialites s ON m.id_specialite = s.id_specialite
            WHERE m.id_etablissement = :etablissement
            ORDER BY u.nom";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['etablissement' => $id_etablissement]);
    return $stmt->fetchAll();
}
}