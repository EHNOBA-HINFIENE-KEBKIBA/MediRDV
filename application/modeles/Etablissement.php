<?php
class Etablissement extends Modele {
    protected $table = 'etablissements';

    /**
     * Récupère tous les établissements avec leur ville
     */
    public function tousAvecVille() {
        $sql = "SELECT e.*, v.nom as ville_nom, v.pays 
                FROM {$this->table} e 
                LEFT JOIN villes v ON e.id_ville = v.id_ville 
                ORDER BY e.nom";
        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Récupère un établissement par son ID
     */
    public function trouverParId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_etablissement = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Ajoute un nouvel établissement
     */
/**
 * Ajoute un nouvel établissement.
 * Accepte un tableau associatif des colonnes à insérer.
 * Les clés doivent correspondre aux colonnes de la table 'etablissements'.
 */
public function ajouter($donnees) {
    // Filtrer les champs autorisés (colonnes réelles de la table)
    $colonnesAutorisees = [
        'nom', 'type', 'description', 'adresse', 'telephone',
        'email', 'coord_gps', 'horaires', 'logo', 'id_ville'
    ];

    $champs = [];
    $params = [];
    foreach ($donnees as $colonne => $valeur) {
        if (in_array($colonne, $colonnesAutorisees)) {
            $champs[] = $colonne;
            $params[":$colonne"] = $valeur;
        }
    }

    if (empty($champs)) return false;

    $sql = "INSERT INTO {$this->table} (" . implode(', ', $champs) . ") VALUES (" . implode(', ', array_keys($params)) . ")";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($params);
}

/**
 * Modifie un établissement existant.
 * Accepte un tableau associatif des colonnes à modifier.
 */
public function modifier($id, $donnees) {
    $colonnesAutorisees = [
        'nom', 'type', 'description', 'adresse', 'telephone',
        'email', 'coord_gps', 'horaires', 'logo', 'id_ville'
    ];

    $sets = [];
    $params = [];

    foreach ($donnees as $colonne => $valeur) {
        if (in_array($colonne, $colonnesAutorisees)) {
            $sets[] = "$colonne = :$colonne";
            $params[":$colonne"] = $valeur;
        }
    }

    if (empty($sets)) {
        return false;
    }

    $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id_etablissement = :id";
    $params[':id'] = $id;

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($params);
}

    /**
     * Supprime un établissement
     */
    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_etablissement = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Liste des types d'établissements possibles (pour les formulaires)
     */
    public function types() {
        return ['Hopital', 'Clinique', 'Cabinet', 'Centre de sante', 'Laboratoire'];
    }

    /**
     * Recherche avec services (pour la partie publique)
     */
    public function rechercherAvecServices($filtres = []) {
        $sql = "SELECT e.*, v.nom as ville_nom, GROUP_CONCAT(s.nom SEPARATOR ', ') as services_noms
                FROM {$this->table} e
                LEFT JOIN villes v ON e.id_ville = v.id_ville
                LEFT JOIN etablissement_service es ON e.id_etablissement = es.id_etablissement
                LEFT JOIN services s ON es.id_service = s.id_service
                WHERE 1=1";
        $params = [];
        if (!empty($filtres['type'])) {
            $sql .= " AND e.type = :type";
            $params['type'] = $filtres['type'];
        }
        if (!empty($filtres['ville'])) {
            $sql .= " AND e.id_ville = :ville";
            $params['ville'] = $filtres['ville'];
        }
        $sql .= " GROUP BY e.id_etablissement ORDER BY e.nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // ==================== GESTION DES SERVICES PAR L'ADMIN ÉTABLISSEMENT ====================

    /**
     * Récupère tous les services avec l'indication s'ils sont associés à l'établissement
     */
    public function servicesEtablissement($id_etablissement) {
        $sql = "SELECT s.*, 
                       CASE WHEN es.id_etablissement IS NOT NULL THEN 1 ELSE 0 END as associe
                FROM services s
                LEFT JOIN etablissement_service es 
                    ON s.id_service = es.id_service AND es.id_etablissement = :id_etab
                ORDER BY s.nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_etab' => $id_etablissement]);
        return $stmt->fetchAll();
    }

    /**
     * Associe un service à un établissement
     */
    public function associerService($id_etablissement, $id_service) {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO etablissement_service (id_etablissement, id_service) VALUES (:etab, :service)");
        return $stmt->execute(['etab' => $id_etablissement, 'service' => $id_service]);
    }

    /**
     * Dissocie un service d'un établissement
     */
    public function dissocierService($id_etablissement, $id_service) {
        $stmt = $this->pdo->prepare("DELETE FROM etablissement_service WHERE id_etablissement = :etab AND id_service = :service");
        return $stmt->execute(['etab' => $id_etablissement, 'service' => $id_service]);
    }
}