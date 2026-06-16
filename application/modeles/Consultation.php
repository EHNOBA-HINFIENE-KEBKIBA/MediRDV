<?php
class Consultation extends Modele {
    protected $table = 'consultations';

    public function creerOuMettreAJour($id_rdv, $diagnostic, $prescription, $notes, $signature = 0, $signatureImage = null, $pdfChemin = null) {
    $stmt = $this->pdo->prepare("SELECT id_consultation FROM {$this->table} WHERE id_rdv = :id_rdv");
    $stmt->execute(['id_rdv' => $id_rdv]);
    $existe = $stmt->fetch();

    if ($existe) {
        $sql = "UPDATE {$this->table} SET diagnostic = :diag, prescription = :pres, notes_medicales = :notes, signature = :sign, updated_at = NOW()";
        $params = ['diag' => $diagnostic, 'pres' => $prescription, 'notes' => $notes, 'sign' => $signature, 'id_rdv' => $id_rdv];
        if ($signatureImage !== null) {
            $sql .= ", signature_image = :sigImg";
            $params['sigImg'] = $signatureImage;
        }
        if ($pdfChemin !== null) {
            $sql .= ", pdf_chemin = :pdf";
            $params['pdf'] = $pdfChemin;
        }
        $sql .= " WHERE id_rdv = :id_rdv";
    } else {
        $sql = "INSERT INTO {$this->table} (id_rdv, diagnostic, prescription, notes_medicales, signature, signature_image, pdf_chemin) VALUES (:id_rdv, :diag, :pres, :notes, :sign, :sigImg, :pdf)";
        $params = ['id_rdv' => $id_rdv, 'diag' => $diagnostic, 'pres' => $prescription, 'notes' => $notes, 'sign' => $signature, 'sigImg' => $signatureImage, 'pdf' => $pdfChemin];
    }
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($params);
}

    public function pourRendezVous($id_rdv) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_rdv = :id_rdv");
        $stmt->execute(['id_rdv' => $id_rdv]);
        return $stmt->fetch();
    }

    public function pourPatient($id_patient) {
        $sql = "SELECT c.*, r.reference, r.date_rdv, r.heure_rdv, u.nom as medecin_nom, u.prenom as medecin_prenom
                FROM {$this->table} c
                JOIN rendez_vous r ON c.id_rdv = r.id_rdv
                JOIN medecins m ON r.id_medecin = m.id_medecin
                JOIN utilisateurs u ON m.id_medecin = u.id_utilisateur
                WHERE r.id_patient = :id_patient
                ORDER BY c.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_patient' => $id_patient]);
        return $stmt->fetchAll();
    }
}