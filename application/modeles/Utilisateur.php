<?php
class Utilisateur extends Modele {
    protected $table = 'utilisateurs';

    // ========================
    // MÉTHODES D'AUTHENTIFICATION
    // ========================

    /**
     * Inscrit un nouvel utilisateur (patient par défaut)
     */
    public function inscrire($nom, $prenom, $email, $mot_de_passe, $telephone = null) {
        $stmt = $this->pdo->prepare("SELECT id_utilisateur FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            return false;
        }

        $id_role = 5; // Patient
        $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->table} (nom, prenom, email, mot_de_passe, telephone, id_role) 
            VALUES (:nom, :prenom, :email, :mdp, :tel, :role)
        ");
        $ok = $stmt->execute([
            'nom'  => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mdp'  => $hash,
            'tel'  => $telephone,
            'role' => $id_role
        ]);
        if ($ok) {
            $id = $this->pdo->lastInsertId();
            $this->pdo->prepare("INSERT INTO patients (id_patient) VALUES (:id)")->execute(['id' => $id]);
            return $id;
        }
        return false;
    }

    /**
     * Vérifie l'email/mot de passe et retourne l'utilisateur si OK, false sinon.
     * Empêche la connexion si le compte est bloqué (actif = 0).
     */
    public function connecter($email, $mot_de_passe) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            // Vérifier si le compte est actif
            if (isset($utilisateur['actif']) && $utilisateur['actif'] == 0) {
                return false; // Compte bloqué
            }
            return $utilisateur;
        }
        return false;
    }

    // ========================
    // MÉTHODES D'ADMINISTRATION
    // ========================

    public function tousAvecRole() {
        $sql = "SELECT u.*, r.libelle as role_libelle, e.nom as etablissement_nom
                FROM {$this->table} u
                JOIN roles r ON u.id_role = r.id_role
                LEFT JOIN etablissements e ON u.id_etablissement = e.id_etablissement
                ORDER BY u.nom, u.prenom";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function trouverParId($id) {
        $stmt = $this->pdo->prepare("SELECT u.*, r.libelle as role_libelle 
                                     FROM {$this->table} u 
                                     JOIN roles r ON u.id_role = r.id_role 
                                     WHERE u.id_utilisateur = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    public function trouverParEmail($email) {
    $stmt = $this->pdo->prepare("SELECT id_utilisateur FROM {$this->table} WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
}

    public function creerAvecRole($donnees, $typeRole) {
        $donnees['mot_de_passe'] = password_hash($donnees['mot_de_passe'], PASSWORD_BCRYPT);

        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nom, prenom, email, mot_de_passe, telephone, id_role, id_etablissement) 
                                     VALUES (:nom, :prenom, :email, :mot_de_passe, :telephone, :id_role, :id_etablissement)");
        $stmt->execute([
            'nom'             => $donnees['nom'],
            'prenom'          => $donnees['prenom'],
            'email'           => $donnees['email'],
            'mot_de_passe'    => $donnees['mot_de_passe'],
            'telephone'       => $donnees['telephone'],
            'id_role'         => $donnees['id_role'],
            'id_etablissement'=> $donnees['id_etablissement'] ?: null
        ]);
        $id_utilisateur = $this->pdo->lastInsertId();

        if ($typeRole === 'medecin') {
            $stmtMed = $this->pdo->prepare("INSERT INTO medecins (id_medecin, sexe, diplomes, experience, id_specialite, id_etablissement) 
                                           VALUES (:id, :sexe, :diplomes, :experience, :id_specialite, :id_etablissement)");
            $stmtMed->execute([
                'id'             => $id_utilisateur,
                'sexe'           => $donnees['sexe'] ?? 'M',
                'diplomes'       => $donnees['diplomes'] ?? '',
                'experience'     => $donnees['experience'] ?? 0,
                'id_specialite'  => $donnees['id_specialite'] ?? null,
                'id_etablissement'=> $donnees['id_etablissement'] ?: null
            ]);
        }
        return $id_utilisateur;
    }

    /**
     * Met à jour un utilisateur avec les champs fournis.
     * Si 'mot_de_passe' est présent et non vide, il est haché.
     */
    public function mettreAJour($id, $donnees) {
        $sets = [];
        $params = [];
        $champsAutorises = ['nom', 'prenom', 'email', 'telephone', 'id_role', 'id_etablissement', 'mot_de_passe'];

        foreach ($donnees as $champ => $valeur) {
            if (in_array($champ, $champsAutorises)) {
                if ($champ === 'mot_de_passe') {
                    if (!empty($valeur)) {
                        $sets[] = "mot_de_passe = :mot_de_passe";
                        $params['mot_de_passe'] = password_hash($valeur, PASSWORD_BCRYPT);
                    }
                } else {
                    $sets[] = "$champ = :$champ";
                    $params[$champ] = $valeur;
                }
            }
        }

        if (empty($sets)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id_utilisateur = :id";
        $params['id'] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_utilisateur = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Active ou désactive un utilisateur
     */
    public function changerActif($id, $actif) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET actif = :actif WHERE id_utilisateur = :id");
        return $stmt->execute(['actif' => $actif ? 1 : 0, 'id' => $id]);
    }

    /**
     * Vérifie si un utilisateur est actif
     */
    public function estActif($email) {
        $stmt = $this->pdo->prepare("SELECT actif FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();
        return $result && $result['actif'] == 1;
    }
}