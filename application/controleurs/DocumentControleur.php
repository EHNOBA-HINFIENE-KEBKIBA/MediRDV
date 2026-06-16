<?php
class DocumentControleur extends Controleur {

    /**
     * Vérifie que l'utilisateur est connecté et est un patient
     */
    private function verifierPatient() {
        if (!isset($_SESSION['utilisateur_id'])) {
            $this->rediriger('/connexion');
        }
        // Vérifier que le patient existe dans la table patients
        $pdo = BaseDeDonnees::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT id_patient FROM patients WHERE id_patient = :id");
        $stmt->execute(['id' => $_SESSION['utilisateur_id']]);
        if (!$stmt->fetch()) {
            $pdo->prepare("INSERT INTO patients (id_patient) VALUES (:id)")->execute(['id' => $_SESSION['utilisateur_id']]);
        }
    }

    /**
     * Liste les documents du patient connecté
     */
    public function index() {
        $this->verifierPatient();
        $id_patient = $_SESSION['utilisateur_id'];
        $documentModel = new Document();
        $documents = $documentModel->pourPatient($id_patient);
        $message = $_SESSION['message_document'] ?? '';
        unset($_SESSION['message_document']);

        $this->afficherVuePrivee('patient/documents', [
            'titre'     => 'Mes documents',
            'documents' => $documents,
            'message'   => $message
        ]);
    }

    /**
     * Upload d'un nouveau document
     */
    public function uploader() {
        $this->verifierPatient();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
            $fichier = $_FILES['document'];
            if ($fichier['error'] === UPLOAD_ERR_OK) {
                // Créer le dossier si nécessaire
                $dossier = 'stockage/documents/';
                if (!is_dir($dossier)) {
                    mkdir($dossier, 0755, true);
                }

                // Générer un nom unique
                $extension = pathinfo($fichier['name'], PATHINFO_EXTENSION);
                $nomFichier = time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
                $chemin = $dossier . $nomFichier;

                if (move_uploaded_file($fichier['tmp_name'], $chemin)) {
                    $documentModel = new Document();
                    $documentModel->ajouter($_SESSION['utilisateur_id'], $fichier['name'], $chemin);
                    $_SESSION['message_document'] = 'Document ajouté avec succès.';
                } else {
                    $_SESSION['message_document'] = 'Erreur lors de l\'upload.';
                }
            } else {
                $_SESSION['message_document'] = 'Erreur fichier.';
            }
        }
        $this->rediriger('/mes-documents');
    }

    /**
     * Télécharger un document
     */
    public function telecharger($id) {
        $this->verifierPatient();
        $documentModel = new Document();
        $document = $documentModel->trouverParId($id);

        if (!$document || $document['id_patient'] != $_SESSION['utilisateur_id']) {
            $this->rediriger('/mes-documents');
        }

        $chemin = $document['chemin'];
        if (file_exists($chemin)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($document['nom_fichier']) . '"');
            header('Content-Length: ' . filesize($chemin));
            readfile($chemin);
            exit;
        } else {
            $_SESSION['message_document'] = 'Fichier introuvable.';
            $this->rediriger('/mes-documents');
        }
    }

    /**
     * Supprimer un document
     */
    public function supprimer($id) {
        $this->verifierPatient();
        $documentModel = new Document();
        $document = $documentModel->trouverParId($id);

        if ($document && $document['id_patient'] == $_SESSION['utilisateur_id']) {
            // Supprimer le fichier physique
            if (file_exists($document['chemin'])) {
                unlink($document['chemin']);
            }
            $documentModel->supprimer($id);
            $_SESSION['message_document'] = 'Document supprimé.';
        } else {
            $_SESSION['message_document'] = 'Document introuvable.';
        }
        $this->rediriger('/mes-documents');
    }

    /**
 * Retourne les documents du patient connecté au format JSON
 */
public function listeJson() {
    if (!isset($_SESSION['utilisateur_id'])) {
        echo json_encode([]);
        exit;
    }
    $id_patient = $_SESSION['utilisateur_id'];
    $documentModel = new Document();
    $documents = $documentModel->pourPatient($id_patient);
    header('Content-Type: application/json');
    echo json_encode($documents);
    exit;
}

/**
 * Récupère les documents liés à un rendez-vous
 */
public function pourRdv($id_rdv) {
    $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_rdv = :id_rdv ORDER BY date_upload DESC");
    $stmt->execute(['id_rdv' => $id_rdv]);
    return $stmt->fetchAll();
}
}