<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .dossier-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .info-patient i {
        width: 24px;
        color: #0d6efd;
    }
</style>

<h2 class="fw-bold mb-4"><i class="bi bi-folder2-open me-2 text-primary"></i><?= $titre ?? 'Dossier patient' ?></h2>

<!-- Informations du patient -->
<div class="dossier-card">
    <h5 class="mb-3"><i class="bi bi-person-badge me-2"></i>Informations personnelles</h5>
    <div class="row">
        <div class="col-md-6">
            <p class="info-patient"><i class="bi bi-person me-2"></i><strong><?= htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) ?></strong></p>
            <p class="info-patient"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($patient['email']) ?></p>
            <p class="info-patient"><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($patient['telephone'] ?? 'Non renseigné') ?></p>
        </div>
        <div class="col-md-6">
            <p class="info-patient"><i class="bi bi-calendar3 me-2"></i>Né(e) le <?= !empty($patient['date_naissance']) ? date('d/m/Y', strtotime($patient['date_naissance'])) : 'Non renseigné' ?></p>
            <p class="info-patient"><i class="bi bi-gender-ambiguous me-2"></i><?= ($patient['sexe'] ?? '') == 'M' ? 'Homme' : (($patient['sexe'] ?? '') == 'F' ? 'Femme' : 'Non renseigné') ?></p>
            <p class="info-patient"><i class="bi bi-droplet me-2"></i>Groupe sanguin : <?= htmlspecialchars($patient['groupe_sanguin'] ?? 'Inconnu') ?></p>
        </div>
    </div>
    <?php if (!empty($patient['pays']) || !empty($patient['ville'])): ?>
    <div class="row mt-2">
        <div class="col-md-12">
            <p class="info-patient"><i class="bi bi-geo-alt me-2"></i><?= htmlspecialchars(($patient['ville'] ?? '') . (!empty($patient['ville']) && !empty($patient['pays']) ? ', ' : '') . ($patient['pays'] ?? '')) ?></p>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Historique des consultations -->
<div class="dossier-card">
    <h5 class="mb-3"><i class="bi bi-clipboard2-pulse me-2"></i>Historique des consultations</h5>
    <?php if (empty($consultations)): ?>
        <p class="text-muted"><i class="bi bi-info-circle me-1"></i>Aucune consultation enregistrée pour ce patient.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Médecin</th>
                        <th>Diagnostic</th>
                        <th>Prescription</th>
                        <th>Signature</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($consultations as $c): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($c['date_rdv'])) ?></td>
                        <td>Dr. <?= htmlspecialchars($c['medecin_nom'] . ' ' . $c['medecin_prenom']) ?></td>
                        <td><?= substr(htmlspecialchars($c['diagnostic'] ?? ''), 0, 80) ?>...</td>
                        <td><?= substr(htmlspecialchars($c['prescription'] ?? ''), 0, 80) ?>...</td>
                        <td>
                            <?php if ($c['signature']): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Signée</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Non signée</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/consultation/voir/<?= $c['id_rdv'] ?>" class="btn btn-sm btn-outline-primary" target="_blank"><i class="bi bi-eye"></i> Voir</a>
                            <?php if (!empty($c['pdf_chemin'])): ?>
                                <a href="<?= BASE_URL . '/' . $c['pdf_chemin'] ?>" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="bi bi-download"></i> PDF</a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/consultation/ordonnance/<?= $c['id_rdv'] ?>" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="bi bi-file-earmark-text"></i> Ordonnance</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Documents médicaux -->
<div class="dossier-card">
    <h5 class="mb-3"><i class="bi bi-file-earmark-medical me-2"></i>Documents médicaux</h5>
    <?php if (empty($documents)): ?>
        <p class="text-muted"><i class="bi bi-info-circle me-1"></i>Aucun document enregistré pour ce patient.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nom du fichier</th>
                        <th>Date d'upload</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents as $doc): ?>
                    <tr>
                        <td><i class="bi bi-file-earmark me-2"></i><?= htmlspecialchars($doc['nom_fichier']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($doc['date_upload'])) ?></td>
                        <td>
                            <a href="<?= BASE_URL . '/' . $doc['chemin'] ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="bi bi-download me-1"></i>Télécharger
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<a href="<?= BASE_URL ?>/medecin/patients" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour à la liste des patients</a>