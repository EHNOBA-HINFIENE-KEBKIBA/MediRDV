<?php
class Medecin extends Modele {
    protected $table = 'medecins';

    /**
     * Récupère tous les médecins avec leur spécialité, établissement, ville et photo.
     */
    public function tousAvecSpecialite() {
        $sql = "SELECT m.*, u.nom, u.prenom, u.photo, s.nom as specialite_nom, e.nom as etablissement_nom, v.nom as ville_nom
                FROM medecins m 
                JOIN utilisateurs u ON m.id_medecin = u.id_utilisateur
                JOIN specialites s ON m.id_specialite = s.id_specialite
                LEFT JOIN etablissements e ON m.id_etablissement = e.id_etablissement
                LEFT JOIN villes v ON e.id_ville = v.id_ville
                ORDER BY u.nom, u.prenom";
        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Récupère un médecin par son ID avec sa spécialité, établissement, ville et photo.
     */
    public function trouverAvecSpecialite($id) {
        $sql = "SELECT m.*, u.nom, u.prenom, u.photo, s.nom as specialite_nom, e.nom as etablissement_nom, v.nom as ville_nom
                FROM medecins m 
                JOIN utilisateurs u ON m.id_medecin = u.id_utilisateur
                JOIN specialites s ON m.id_specialite = s.id_specialite
                LEFT JOIN etablissements e ON m.id_etablissement = e.id_etablissement
                LEFT JOIN villes v ON e.id_ville = v.id_ville
                WHERE m.id_medecin = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Récupère un médecin par son ID (données brutes de la table medecins).
     */
    public function trouverParId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_medecin = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Recherche publique de médecins avec filtres (spécialité, ville, nom) et retourne
     * toutes les informations nécessaires à l'affichage (photo, expérience, diplômes, etc.).
     */
    public function rechercher($filtres = []) {
        $sql = "SELECT m.id_medecin, u.nom, u.prenom, u.telephone, u.email, u.photo,
                       s.nom as specialite_nom, e.nom as etablissement_nom, v.nom as ville_nom,
                       m.experience, m.diplomes
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

    /**
     * Récupère les médecins d'un établissement (avec nom, prénom, spécialité, photo).
     */
    public function parEtablissement($id_etablissement) {
        $sql = "SELECT m.id_medecin, u.nom, u.prenom, u.photo, s.nom as specialite_nom
                FROM medecins m
                JOIN utilisateurs u ON m.id_medecin = u.id_utilisateur
                JOIN specialites s ON m.id_specialite = s.id_specialite
                WHERE m.id_etablissement = :etablissement
                ORDER BY u.nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['etablissement' => $id_etablissement]);
        return $stmt->fetchAll();
    }

    /**
     * Recherche de médecins pour l'admin établissement (avec filtre texte).
     */
    public function rechercherParEtablissement($id_etablissement, $recherche = '') {
        $sql = "SELECT m.*, u.nom, u.prenom, u.email, u.telephone, u.photo, s.nom as specialite_nom
                FROM medecins m
                JOIN utilisateurs u ON m.id_medecin = u.id_utilisateur
                LEFT JOIN specialites s ON m.id_specialite = s.id_specialite
                WHERE m.id_etablissement = :id_etab";
        $params = ['id_etab' => $id_etablissement];

        if (!empty($recherche)) {
            $sql .= " AND (u.nom LIKE :recherche OR u.prenom LIKE :recherche2 OR u.email LIKE :recherche3)";
            $params['recherche']  = "%$recherche%";
            $params['recherche2'] = "%$recherche%";
            $params['recherche3'] = "%$recherche%";
        }
        $sql .= " ORDER BY u.nom, u.prenom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}