<?php
class RendezVous extends Modele {
    protected $table = 'rendez_vous';

    /**
     * Crée un nouveau rendez-vous
     */
    public function creer($id_patient, $id_medecin, $id_etablissement, $date, $heure, $motif) {
        // Vérifier qu'il n'y a pas de conflit horaire
        $stmt = $this->pdo->prepare("
            SELECT id_rdv FROM {$this->table} 
            WHERE id_medecin = :medecin 
            AND date_rdv = :date 
            AND heure_rdv = :heure 
            AND statut NOT IN ('Annulé')
        ");
        $stmt->execute(['medecin' => $id_medecin, 'date' => $date, 'heure' => $heure]);
        if ($stmt->fetch()) {
            return false; // créneau déjà pris
        }

        // Générer une référence unique
        $reference = 'RDV-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->table} (reference, date_rdv, heure_rdv, motif, id_patient, id_medecin, id_etablissement)
            VALUES (:ref, :date, :heure, :motif, :patient, :medecin, :etab)
        ");
        $ok = $stmt->execute([
            'ref'     => $reference,
            'date'    => $date,
            'heure'   => $heure,
            'motif'   => $motif,
            'patient' => $id_patient,
            'medecin' => $id_medecin,
            'etab'    => $id_etablissement
        ]);
        return $ok ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Récupère les rendez-vous d'un patient
     */
    public function pourPatient($id_patient) {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.nom as medecin_nom, u.prenom as medecin_prenom, 
                   s.nom as specialite, e.nom as etablissement_nom
            FROM {$this->table} r
            JOIN medecins med ON r.id_medecin = med.id_medecin
            JOIN utilisateurs u ON med.id_medecin = u.id_utilisateur
            JOIN specialites s ON med.id_specialite = s.id_specialite
            JOIN etablissements e ON r.id_etablissement = e.id_etablissement
            WHERE r.id_patient = :patient
            ORDER BY r.date_rdv DESC, r.heure_rdv DESC
        ");
        $stmt->execute(['patient' => $id_patient]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère les créneaux disponibles d'un médecin pour une date
     */
    public function creneauxDisponibles($id_medecin, $date) {
        $jour = $this->getJourSemaine($date);
        $stmt = $this->pdo->prepare("
            SELECT heure_debut, heure_fin FROM disponibilites 
            WHERE id_medecin = :medecin AND jour = :jour
        ");
        $stmt->execute(['medecin' => $id_medecin, 'jour' => $jour]);
        $dispos = $stmt->fetchAll();

        if (empty($dispos)) return [];

        $stmt = $this->pdo->prepare("
            SELECT heure_rdv FROM {$this->table}
            WHERE id_medecin = :medecin AND date_rdv = :date AND statut NOT IN ('Annulé')
        ");
        $stmt->execute(['medecin' => $id_medecin, 'date' => $date]);
        $pris = array_column($stmt->fetchAll(), 'heure_rdv');

        $creneaux = [];
        foreach ($dispos as $disp) {
            $debut = new DateTime($disp['heure_debut']);
            $fin   = new DateTime($disp['heure_fin']);
            while ($debut < $fin) {
                $heure = $debut->format('H:i:s');
                if (!in_array($heure, $pris)) {
                    $creneaux[] = $heure;
                }
                $debut->add(new DateInterval('PT30M'));
            }
        }
        return $creneaux;
    }

    /**
     * Rendez-vous d'un médecin (avec filtre de date optionnel)
     */
public function pourMedecin($id_medecin, $date = null) {
    $sql = "SELECT r.*, u.nom as patient_nom, u.prenom as patient_prenom 
            FROM {$this->table} r
            JOIN patients p ON r.id_patient = p.id_patient
            JOIN utilisateurs u ON p.id_patient = u.id_utilisateur
            WHERE r.id_medecin = :medecin";
    $params = ['medecin' => $id_medecin];
    if ($date) {
        $sql .= " AND r.date_rdv = :date";
        $params['date'] = $date;
    }
    $sql .= " ORDER BY r.date_rdv, r.heure_rdv";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

    /**
     * Change le statut d'un rendez-vous
     */
    public function changerStatut($id_rdv, $id_medecin, $statut) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET statut = :statut WHERE id_rdv = :id AND id_medecin = :medecin");
        return $stmt->execute(['statut' => $statut, 'id' => $id_rdv, 'medecin' => $id_medecin]);
    }

    /**
     * Rendez-vous d'un établissement (pour le réceptionniste)
     */
public function pourEtablissement($id_etablissement, $date = null) {
    $sql = "SELECT r.*, u.nom as patient_nom, u.prenom as patient_prenom, 
                   m.id_medecin, u2.nom as medecin_nom, u2.prenom as medecin_prenom
            FROM {$this->table} r
            JOIN patients p ON r.id_patient = p.id_patient
            JOIN utilisateurs u ON p.id_patient = u.id_utilisateur
            JOIN medecins m ON r.id_medecin = m.id_medecin
            JOIN utilisateurs u2 ON m.id_medecin = u2.id_utilisateur
            WHERE r.id_etablissement = :etablissement";
    $params = ['etablissement' => $id_etablissement];
    if ($date) {
        $sql .= " AND r.date_rdv = :date";
        $params['date'] = $date;
    }
    $sql .= " ORDER BY r.heure_rdv";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

    private function getJourSemaine($date) {
        $jours = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
        $timestamp = strtotime($date);
        return $jours[date('w', $timestamp)];
    }

    public function modifierRdv($id_rdv, $donnees) {
    $sql = "UPDATE {$this->table} SET 
            id_medecin = :id_medecin,
            date_rdv = :date_rdv,
            heure_rdv = :heure_rdv,
            motif = :motif,
            statut = :statut
            WHERE id_rdv = :id";
    $donnees['id'] = $id_rdv;
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($donnees);
}

/**
 * Liste des patients uniques d'un médecin
 */
public function patientsDuMedecin($id_medecin) {
    $sql = "SELECT DISTINCT u.id_utilisateur, u.nom, u.prenom, u.email, u.telephone, p.date_naissance, p.groupe_sanguin
            FROM {$this->table} r
            JOIN patients p ON r.id_patient = p.id_patient
            JOIN utilisateurs u ON p.id_patient = u.id_utilisateur
            WHERE r.id_medecin = :medecin
            ORDER BY u.nom, u.prenom";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['medecin' => $id_medecin]);
    return $stmt->fetchAll();
}

/**
 * Met à jour les observations d'un rendez-vous
 */
public function sauvegarderObservations($id_rdv, $observations) {
    $stmt = $this->pdo->prepare("UPDATE {$this->table} SET observations = :obs WHERE id_rdv = :id");
    return $stmt->execute(['obs' => $observations, 'id' => $id_rdv]);
}

/**
 * Met à jour la date/heure/motif/statut d'un rendez-vous (utilisé par le médecin)
 */
public function modifierRdvComplet($id_rdv, $donnees) {
    $sql = "UPDATE {$this->table} SET 
            date_rdv = :date_rdv,
            heure_rdv = :heure_rdv,
            motif = :motif,
            statut = :statut";
    if (isset($donnees['id_medecin'])) {
        $sql .= ", id_medecin = :id_medecin";
    }
    $sql .= " WHERE id_rdv = :id";
    $donnees['id'] = $id_rdv;
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($donnees);
}
/**
 * Trouve un rendez-vous par son ID (id_rdv)
 */
public function trouverParId($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_rdv = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}
}