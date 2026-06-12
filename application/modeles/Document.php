<?php
class Document extends Modele {
    protected $table = 'documents';

    /**
     * Ajoute un document pour un patient
     */
    public function ajouter($id_patient, $nomFichier, $chemin) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (id_patient, nom_fichier, chemin) VALUES (:id_patient, :nom, :chemin)");
        return $stmt->execute([
            'id_patient' => $id_patient,
            'nom'        => $nomFichier,
            'chemin'     => $chemin
        ]);
    }

    /**
     * Liste les documents d'un patient
     */
    public function pourPatient($id_patient) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_patient = :id_patient ORDER BY date_upload DESC");
        $stmt->execute(['id_patient' => $id_patient]);
        return $stmt->fetchAll();
    }

    /**
     * Trouve un document par son ID
     */
    public function trouverParId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_document = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Supprime un document
     */
    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_document = :id");
        return $stmt->execute(['id' => $id]);
    }
}