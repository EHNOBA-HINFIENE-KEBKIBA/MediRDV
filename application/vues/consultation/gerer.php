<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    #signatureCanvas {
        border: 2px solid #0d6efd;
        border-radius: 8px;
        cursor: crosshair;
        background: #fff;
    }
</style>
<h2><?= $titre ?></h2>
<div class="card mb-4">
    <div class="card-body">
        <h5>Rendez-vous du <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'], 0, 5) ?></h5>
        <p>Patient : <strong><?= htmlspecialchars($patient['nom'] . ' ' . $patient['prenom']) ?></strong></p>
        <p>Motif : <?= htmlspecialchars($rdv['motif']) ?></p>
    </div>
</div>

<form method="post" id="formConsultation">
    <div class="mb-3">
        <label for="diagnostic" class="form-label fw-bold">Diagnostic</label>
        <textarea name="diagnostic" id="diagnostic" class="form-control" rows="4" required><?= htmlspecialchars($consultation['diagnostic'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
        <label for="prescription" class="form-label fw-bold">Prescription</label>
        <textarea name="prescription" id="prescription" class="form-control" rows="4"><?= htmlspecialchars($consultation['prescription'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
        <label for="notes" class="form-label fw-bold">Notes médicales</label>
        <textarea name="notes" id="notes" class="form-control" rows="3"><?= htmlspecialchars($consultation['notes_medicales'] ?? '') ?></textarea>
    </div>

    <!-- Signature manuscrite -->
    <div class="mb-3">
        <label class="form-label fw-bold">✍️ Signature manuscrite</label>
        <canvas id="signatureCanvas" width="400" height="150"></canvas>
        <div class="mt-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnClearCanvas"><i class="bi bi-eraser"></i> Effacer</button>
        </div>
        <input type="hidden" name="signature_data" id="signatureData">
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="signature" id="signatureCheck" <?= ($consultation['signature'] ?? 0) ? 'checked' : '' ?>>
        <label class="form-check-label" for="signatureCheck">Valider la signature électronique</label>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary" id="btnSubmit">Enregistrer</button>
        <?php if (!empty($consultation['id_consultation'])): ?>
            <a href="<?= BASE_URL ?>/consultation/envoyer-ordonnance/<?= $rdv['id_rdv'] ?>" class="btn btn-outline-success">📧 Envoyer au patient</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/medecin/agenda" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<script>
(function() {
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    let painting = false;

    function startPosition(e) {
        painting = true;
        draw(e);
    }

    function endPosition() {
        painting = false;
        ctx.beginPath();
    }

    function draw(e) {
        if (!painting) return;
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;   // canvas physical pixels
        const scaleY = canvas.height / rect.height;
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        const x = (clientX - rect.left) * scaleX;
        const y = (clientY - rect.top) * scaleY;

        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#000';
        ctx.lineTo(x, y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(x, y);
    }

    canvas.addEventListener('mousedown', startPosition);
    canvas.addEventListener('mouseup', endPosition);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('touchstart', startPosition);
    canvas.addEventListener('touchend', endPosition);
    canvas.addEventListener('touchmove', draw);

    document.getElementById('btnClearCanvas').addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById('signatureData').value = '';
    });

    document.getElementById('formConsultation').addEventListener('submit', function(e) {
        // Convertir le contenu du canvas en base64 et le placer dans le champ caché
        const dataURL = canvas.toDataURL('image/png');
        document.getElementById('signatureData').value = dataURL;
        // Si le canvas n'est pas vide, cocher automatiquement la signature
        const blank = document.createElement('canvas');
        blank.width = canvas.width;
        blank.height = canvas.height;
        if (canvas.toDataURL() !== blank.toDataURL()) {
            document.getElementById('signatureCheck').checked = true;
        }
    });
})();
</script>