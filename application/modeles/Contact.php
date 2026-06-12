<?php
class Contact extends Modele {
    protected $table = 'contacts';

    public function enregistrer($nom, $email, $sujet, $message) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)");
        return $stmt->execute([
            'nom'     => $nom,
            'email'   => $email,
            'sujet'   => $sujet,
            'message' => $message
        ]);
    }
}