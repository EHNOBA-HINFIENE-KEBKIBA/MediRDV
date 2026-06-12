<?php
// Ce script est destiné à être exécuté par un cron toutes les minutes
require_once __DIR__ . '/../configuration/base_de_donnees.php';
require_once __DIR__ . '/../configuration/email.php';
require_once __DIR__ . '/../noyau/BaseDeDonnees.php';
require_once __DIR__ . '/../noyau/Modele.php';
require_once __DIR__ . '/../application/modeles/Notification.php';
require_once __DIR__ . '/../vendor/autoload.php'; // Pour PHPMailer
require_once __DIR__ . '/../application/helpers/Mailer.php';

$notificationModel = new Notification();
$notifications = $notificationModel->aEnvoyer(50);

foreach ($notifications as $notif) {
    $sujet = 'MediRDV - ' . ucfirst($notif['type']);
    $message = '<html><body>';
    $message .= '<h2>MediRDV</h2>';
    $message .= '<p>' . nl2br(htmlspecialchars($notif['contenu'])) . '</p>';
    $message .= '<p>Cordialement,<br>L\'équipe MediRDV</p>';
    $message .= '</body></html>';

    $envoye = Mailer::envoyer($notif['email'], $sujet, $message);
    if ($envoye) {
        $notificationModel->marquerEnvoyee($notif['id_notif']);
        echo "Notification envoyée à {$notif['email']}\n";
    }
}