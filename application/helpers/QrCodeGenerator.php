<?php
class QrCodeGenerator {

    /**
     * Génère un QR code PNG via l'API Google Charts et le sauvegarde.
     * @param string $texte   Le texte à encoder.
     * @param string $chemin  Le chemin complet où sauvegarder l'image (ex: /var/www/.../image.png)
     */
    public static function generer($texte, $chemin) {
        $dossier = dirname($chemin);
        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }

        // URL de l'API Google Charts pour QR code
        $url = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($texte) . '&choe=UTF-8';

        // Récupérer l'image générée
        $image = file_get_contents($url);
        if ($image !== false) {
            file_put_contents($chemin, $image);
        }
    }
}