<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ordonnance - MediRDV</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 40px;
            color: #000;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #0d6efd;
            margin: 0;
        }
        .header p {
            margin: 5px 0;
        }
        .info-block {
            margin-bottom: 25px;
        }
        .info-block p {
            margin: 5px 0;
        }
        .section-title {
            background: #0d6efd;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            margin: 20px 0 10px 0;
        }
        .content {
            padding: 10px 20px;
            border-left: 2px solid #0d6efd;
            margin-bottom: 20px;
            background: #f9f9f9;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
        }
        .signature img {
            max-width: 200px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 0.8rem;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>MediRDV</h1>
    <p>Ordonnance Médicale</p>
    <p>Date d'émission : <?= date('d/m/Y') ?></p>
</div>

<div class="info-block">
    <p><strong>Patient :</strong> <?= htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) ?></p>
    <p><strong>Date de naissance :</strong> <?= !empty($patient['date_naissance']) ? date('d/m/Y', strtotime($patient['date_naissance'])) : 'Non renseignée' ?></p>
    <p><strong>Médecin :</strong> Dr. <?= htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']) ?></p>
    <p><strong>Rendez-vous du :</strong> <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'], 0, 5) ?></p>
</div>

<div class="section-title">Diagnostic</div>
<div class="content">
    <?= nl2br(htmlspecialchars($consultation['diagnostic'] ?? '')) ?>
</div>

<div class="section-title">Prescription</div>
<div class="content">
    <?= nl2br(htmlspecialchars($consultation['prescription'] ?? '')) ?>
</div>

<?php if (!empty($consultation['notes_medicales'])): ?>
<div class="section-title">Notes médicales</div>
<div class="content">
    <?= nl2br(htmlspecialchars($consultation['notes_medicales'] ?? '')) ?>
</div>
<?php endif; ?>

<div class="signature">
    <?php if (!empty($consultation['signature_image'])): ?>
        <img src="<?= BASE_URL . '/' . $consultation['signature_image'] ?>" alt="Signature">
    <?php else: ?>
        <p style="color:green;"><strong>Signature électronique :</strong> Dr. <?= htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']) ?><br>Validée le <?= date('d/m/Y à H:i', strtotime($consultation['updated_at'] ?? $consultation['created_at'])) ?></p>
    <?php endif; ?>
</div>

<div class="footer">
    Document généré par MediRDV – Ce document est une ordonnance médicale électronique.
</div>

<div class="no-print" style="text-align:center; margin-top:20px;">
    <button onclick="window.print()" class="btn btn-primary">🖨️ Imprimer / Enregistrer en PDF</button>
    <a href="<?= BASE_URL ?>/mes-rendezvous" class="btn btn-secondary">Retour</a>
</div>

</body>
</html>