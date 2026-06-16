<?php
require_once __DIR__ . '/../../vendor/tcpdf/tcpdf.php';

class PdfGenerator {
    public static function generer($titre, $html, $cheminSauvegarde = null) {
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator('MediRDV');
    $pdf->SetTitle($titre);
    $pdf->SetHeaderData('', 0, 'MediRDV - Ordonnance', '');
    $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 20, 15);
    $pdf->SetAutoPageBreak(true, 25);
    $pdf->AddPage();
    $pdf->writeHTML($html, true, false, true, false, '');

    if ($cheminSauvegarde) {
        $pdf->Output($cheminSauvegarde, 'F'); // Sauvegarde sur le serveur
    } else {
        $pdf->Output($titre . '.pdf', 'D'); // Téléchargement direct
    }
}
}