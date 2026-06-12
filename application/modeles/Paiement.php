<?php
class Paiement extends Modele {
    protected $table = 'paiements';

    /**
     * Ajoute un paiement
     */
    public function ajouter($id_rdv, $montant, $mode = 'Espèces') {
        $reference = 'PAY-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (montant, mode, id_rdv) VALUES (:montant, :mode, :id_rdv)");
        return $stmt->execute([
            'montant' => $montant,
            'mode'    => $mode,
            'id_rdv'  => $id_rdv
        ]);
    }

    /**
     * Paiements d'un patient (via ses rendez‑vous)
     */
    public function pourPatient($id_patient) {
        $sql = "SELECT p.*, r.reference as rdv_reference, r.date_rdv, r.heure_rdv,
                       u.nom as medecin_nom, u.prenom as medecin_prenom
                FROM {$this->table} p
                JOIN rendez_vous r ON p.id_rdv = r.id_rdv
                JOIN medecins m ON r.id_medecin = m.id_medecin
                JOIN utilisateurs u ON m.id_medecin = u.id_utilisateur
                WHERE r.id_patient = :id_patient
                ORDER BY p.date_paiement DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_patient' => $id_patient]);
        return $stmt->fetchAll();
    }

    /**
     * Paiements pour un établissement
     */
    public function pourEtablissement($id_etablissement) {
        $sql = "SELECT p.*, r.reference as rdv_reference, r.date_rdv, r.heure_rdv,
                       u.nom as patient_nom, u.prenom as patient_prenom,
                       u2.nom as medecin_nom, u2.prenom as medecin_prenom
                FROM {$this->table} p
                JOIN rendez_vous r ON p.id_rdv = r.id_rdv
                JOIN patients pat ON r.id_patient = pat.id_patient
                JOIN utilisateurs u ON pat.id_patient = u.id_utilisateur
                JOIN medecins m ON r.id_medecin = m.id_medecin
                JOIN utilisateurs u2 ON m.id_medecin = u2.id_utilisateur
                WHERE r.id_etablissement = :id_etablissement
                ORDER BY p.date_paiement DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_etablissement' => $id_etablissement]);
        return $stmt->fetchAll();
    }
}