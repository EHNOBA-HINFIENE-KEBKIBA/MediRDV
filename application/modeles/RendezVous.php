<?php
class RendezVous extends Modele {
    protected $table = 'rendez_vous';

    public function creer($id_patient, $id_medecin, $id_etablissement, $date, $heure, $motif) {
        $stmt = $this->pdo->prepare("
            SELECT id_rdv FROM {$this->table} 
            WHERE id_medecin = :medecin 
            AND date_rdv = :date 
            AND heure_rdv = :heure 
            AND statut NOT IN ('Annulé')
        ");
        $stmt->execute(['medecin' => $id_medecin, 'date' => $date, 'heure' => $heure]);
        if ($stmt->fetch()) return false;

        $reference = 'RDV-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->table} (reference, date_rdv, heure_rdv, motif, id_patient, id_medecin, id_etablissement)
            VALUES (:ref, :date, :heure, :motif, :patient, :medecin, :etab)
        ");
        $ok = $stmt->execute([
            'ref' => $reference, 'date' => $date, 'heure' => $heure,
            'motif' => $motif, 'patient' => $id_patient,
            'medecin' => $id_medecin, 'etab' => $id_etablissement
        ]);
        return $ok ? $this->pdo->lastInsertId() : false;
    }

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
                if (!in_array($heure, $pris)) $creneaux[] = $heure;
                $debut->add(new DateInterval('PT30M'));
            }
        }
        return $creneaux;
    }

    public function pourMedecin($id_medecin, $date = null) {
        $sql = "SELECT r.*, u.nom as patient_nom, u.prenom as patient_prenom 
                FROM {$this->table} r
                JOIN patients p ON r.id_patient = p.id_patient
                JOIN utilisateurs u ON p.id_patient = u.id_utilisateur
                WHERE r.id_medecin = :medecin";
        $params = ['medecin' => $id_medecin];
        if ($date) { $sql .= " AND r.date_rdv = :date"; $params['date'] = $date; }
        $sql .= " ORDER BY r.date_rdv, r.heure_rdv";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function changerStatut($id_rdv, $id_medecin, $statut) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET statut = :statut WHERE id_rdv = :id AND id_medecin = :medecin");
        return $stmt->execute(['statut' => $statut, 'id' => $id_rdv, 'medecin' => $id_medecin]);
    }

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
        if ($date) { $sql .= " AND r.date_rdv = :date"; $params['date'] = $date; }
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
        $sql = "UPDATE {$this->table} SET id_medecin = :id_medecin, date_rdv = :date_rdv, heure_rdv = :heure_rdv, motif = :motif, statut = :statut WHERE id_rdv = :id";
        $donnees['id'] = $id_rdv;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($donnees);
    }

    public function patientsDuMedecin($id_medecin, $recherche = '') {
        $sql = "SELECT DISTINCT u.id_utilisateur, u.nom, u.prenom, u.email, u.telephone, p.date_naissance, p.groupe_sanguin
                FROM {$this->table} r
                JOIN patients p ON r.id_patient = p.id_patient
                JOIN utilisateurs u ON p.id_patient = u.id_utilisateur
                WHERE r.id_medecin = :medecin";
        $params = ['medecin' => $id_medecin];
        if (!empty($recherche)) {
            $sql .= " AND (u.nom LIKE :r OR u.prenom LIKE :r2 OR u.email LIKE :r3)";
            $params['r'] = "%$recherche%"; $params['r2'] = "%$recherche%"; $params['r3'] = "%$recherche%";
        }
        $sql .= " ORDER BY u.nom, u.prenom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function sauvegarderObservations($id_rdv, $observations) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET observations = :obs WHERE id_rdv = :id");
        return $stmt->execute(['obs' => $observations, 'id' => $id_rdv]);
    }

    public function modifierRdvComplet($id_rdv, $donnees) {
        $sql = "UPDATE {$this->table} SET date_rdv = :date_rdv, heure_rdv = :heure_rdv, motif = :motif, statut = :statut";
        if (isset($donnees['id_medecin'])) $sql .= ", id_medecin = :id_medecin";
        $sql .= " WHERE id_rdv = :id";
        $donnees['id'] = $id_rdv;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($donnees);
    }

    public function trouverParId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_rdv = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Nouvelle méthode : retourne tous les créneaux (libres et occupés)
     */
public function tousLesCreneaux($id_medecin, $date) {
    // Vérifier si une exception rend le médecin indisponible ce jour-là
    $stmtExc = $this->pdo->prepare("
        SELECT type FROM exceptions 
        WHERE id_medecin = :id_medecin AND date_exception = :date
    ");
    $stmtExc->execute(['id_medecin' => $id_medecin, 'date' => $date]);
    $exception = $stmtExc->fetch();

    // Si une indisponibilité est déclarée pour ce jour, aucun créneau
    if ($exception && $exception['type'] == 'indisponible') {
        return [];
    }

    // Si une exception "disponible" existe, on force la disponibilité (même si le jour n'est pas normalement travaillé)
    $forcerDisponible = ($exception && $exception['type'] == 'disponible');

    $jour = $this->getJourSemaine($date);
    $stmt = $this->pdo->prepare("
        SELECT heure_debut, heure_fin, duree_consultation 
        FROM disponibilites 
        WHERE id_medecin = :medecin AND jour = :jour
    ");
    $stmt->execute(['medecin' => $id_medecin, 'jour' => $jour]);
    $dispos = $stmt->fetchAll();

    // Si aucune disponibilité ET pas d'exception "disponible", aucun créneau
    if (empty($dispos) && !$forcerDisponible) return [];

    // Si exception "disponible" mais pas de disponibilités, on utilise des plages par défaut
    if (empty($dispos) && $forcerDisponible) {
        $dispos = [
            ['heure_debut' => '08:00', 'heure_fin' => '17:00', 'duree_consultation' => 30]
        ];
    }

    $stmt = $this->pdo->prepare("
        SELECT heure_rdv FROM {$this->table}
        WHERE id_medecin = :medecin AND date_rdv = :date AND statut NOT IN ('Annulé')
    ");
    $stmt->execute(['medecin' => $id_medecin, 'date' => $date]);
    $pris = array_column($stmt->fetchAll(), 'heure_rdv');

    $creneaux = [];
    foreach ($dispos as $disp) {
        $duree = (int)($disp['duree_consultation'] ?? 30);
        $debut = new DateTime($disp['heure_debut']);
        $fin   = new DateTime($disp['heure_fin']);
        while ($debut < $fin) {
            $heure = $debut->format('H:i:s');
            $creneaux[] = [
                'heure' => $heure,
                'libre' => !in_array($heure, $pris)
            ];
            $debut->add(new DateInterval('PT' . $duree . 'M'));
        }
    }
    return $creneaux;
}
}