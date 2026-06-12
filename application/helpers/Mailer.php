<?php
require_once __DIR__ . '/../../vendor/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../../vendor/PHPMailer/SMTP.php';
require_once __DIR__ . '/../../vendor/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    public static function envoyer($destinataire, $sujet, $message) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($destinataire);

            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->Body    = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Vous pouvez logger l'erreur
            return false;
        }
    }
}