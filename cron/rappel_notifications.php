<?php
require_once __DIR__ . '/../configuration/base_de_donnees.php';
require_once __DIR__ . '/../configuration/email.php';
require_once __DIR__ . '/../noyau/BaseDeDonnees.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../application/helpers/Mailer.php';

$pdo = BaseDeDonnees::getInstance()->getPdo();

// Récupérer les rendez-vous de demain qui n'ont pas encore reçu de rappel
$demain = date('Y-m-d', strtotime('+1 day'));
$stmt = $pdo->prepare("SELECT r.*, u.email, u.nom, u.prenom, 
                              med.id_medecin, u2.email as email_medecin, u2.nom as nom_medecin, u2.prenom as prenom_medecin
                       FROM rendez_vous r
                       JOIN patients p ON r.id_patient = p.id_patient
                       JOIN utilisateurs u ON p.id_patient = u.id_utilisateur
                       JOIN medecins med ON r.id_medecin = med.id_medecin
                       JOIN utilisateurs u2 ON med.id_medecin = u2.id_utilisateur
                       WHERE r.date_rdv = :demain 
                       AND r.statut NOT IN ('Annulé')
                       AND r.id_rdv NOT IN (SELECT DISTINCT id_rdv FROM notifications WHERE type = 'rappel' AND id_rdv IS NOT NULL)");
$stmt->execute(['demain' => $demain]);
$rdvs = $stmt->fetchAll();

foreach ($rdvs as $rdv) {
    // Notification au patient
    $messagePatient = "Rappel : vous avez un rendez-vous demain, le " . date('d/m/Y', strtotime($rdv['date_rdv'])) . " à " . substr($rdv['heure_rdv'], 0, 5) . ".";
    Mailer::envoyer($rdv['email'], 'MediRDV - Rappel de rendez-vous', $messagePatient);
    
    // Notification au médecin
    $messageMedecin = "Rappel : vous avez un rendez-vous demain à " . substr($rdv['heure_rdv'], 0, 5) . " avec " . $rdv['prenom'] . " " . $rdv['nom'] . ".";
    Mailer::envoyer($rdv['email_medecin'], 'MediRDV - Rappel de rendez-vous', $messageMedecin);

    // Enregistrer les notifications envoyées
    $pdo->prepare("INSERT INTO notifications (id_utilisateur, type, contenu, canal) VALUES (?, 'rappel', ?, 'Email')")
        ->execute([$rdv['id_patient'], $messagePatient]);
    $pdo->prepare("INSERT INTO notifications (id_utilisateur, type, contenu, canal) VALUES (?, 'rappel', ?, 'Email')")
        ->execute([$rdv['id_medecin'], $messageMedecin]);
}

echo count($rdvs) . " rappels envoyés.\n";