<?php
class Utilisateur extends Modele {
    protected $table = 'utilisateurs';

    // ========================
    // AUTHENTIFICATION
    // ========================

    /**
     * Inscrit un nouvel utilisateur (patient par défaut)
     */
    public function inscrire($nom, $prenom, $email, $mot_de_passe, $telephone = null, $date_naissance = null, $sexe = null, $pays = null, $ville = null) {
        $stmt = $this->pdo->prepare("SELECT id_utilisateur FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) return false;

        $id_role = 5; // Patient
        $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->table} (nom, prenom, email, mot_de_passe, telephone, id_role) 
            VALUES (:nom, :prenom, :email, :mdp, :tel, :role)
        ");
        $ok = $stmt->execute([
            'nom'  => $nom, 'prenom' => $prenom, 'email' => $email,
            'mdp'  => $hash, 'tel' => $telephone, 'role' => $id_role
        ]);
        if ($ok) {
            $id = $this->pdo->lastInsertId();
            $stmtPat = $this->pdo->prepare("
                INSERT INTO patients (id_patient, date_naissance, sexe, pays, ville) 
                VALUES (:id, :date_naissance, :sexe, :pays, :ville)
            ");
            $stmtPat->execute([
                'id' => $id, 'date_naissance' => $date_naissance,
                'sexe' => $sexe, 'pays' => $pays, 'ville' => $ville
            ]);
            return $id;
        }
        return false;
    }

    /**
     * Vérifie email/mot de passe et retourne l'utilisateur si OK, false sinon.
     * Empêche la connexion si le compte est bloqué (actif = 0).
     */
    public function connecter($email, $mot_de_passe) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            if (isset($utilisateur['actif']) && $utilisateur['actif'] == 0) return false;
            return $utilisateur;
        }
        return false;
    }

    // ========================
    // ADMINISTRATION & CRUD
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
     * Met à jour les champs de la table utilisateurs (version flexible).
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

        if (empty($sets)) return false;

        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id_utilisateur = :id";
        $params['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Met à jour le profil complet (utilisateur + patient)
     */
    public function mettreAJourProfil($id, $donnees) {
        // Colonnes utilisateurs
        $colsUser = ['nom', 'prenom', 'email', 'telephone', 'photo'];
        $setsUser = [];
        $paramsUser = [];
        foreach ($donnees as $champ => $valeur) {
            if (in_array($champ, $colsUser)) {
                $setsUser[] = "$champ = :$champ";
                $paramsUser[$champ] = $valeur;
            }
        }
        if (!empty($setsUser)) {
            $sql = "UPDATE {$this->table} SET " . implode(', ', $setsUser) . " WHERE id_utilisateur = :id";
            $paramsUser['id'] = $id;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($paramsUser);
        }

        // Colonnes patients
        $colsPat = ['date_naissance', 'sexe', 'pays', 'ville'];
        $setsPat = [];
        $paramsPat = [];
        foreach ($donnees as $champ => $valeur) {
            if (in_array($champ, $colsPat)) {
                $setsPat[] = "$champ = :$champ";
                $paramsPat[$champ] = $valeur;
            }
        }
        if (!empty($setsPat)) {
            $sql = "UPDATE patients SET " . implode(', ', $setsPat) . " WHERE id_patient = :id";
            $paramsPat['id'] = $id;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($paramsPat);
        }
        return true;
    }

    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_utilisateur = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function changerActif($id, $actif) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET actif = :actif WHERE id_utilisateur = :id");
        return $stmt->execute(['actif' => $actif ? 1 : 0, 'id' => $id]);
    }

    public function estActif($email) {
        $stmt = $this->pdo->prepare("SELECT actif FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();
        return $result && $result['actif'] == 1;
    }

    /**
     * Retourne l'URL de la photo ou un avatar par défaut.
     */
    public function photoUrl($utilisateur) {
        if (!empty($utilisateur['photo']) && file_exists($utilisateur['photo'])) {
            return BASE_URL . '/' . $utilisateur['photo'];
        }
        return BASE_URL . '/public/assets/images/avatar.png';
    }
}