<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .time-input { width: 110px; }
    .time-input input, .time-input select { width: 110px !important; }
    .table-dispo th { background-color: #f8f9fa; }
    .actif-badge { cursor: pointer; }
    .jour-actif { background-color: #e8f0fe; }
    .info-text { font-size: 12px; color: #6c757d; margin-top: 5px; }
</style>

<h2 class="fw-bold mb-4"><i class="bi bi-clock me-2 text-primary"></i><?= $titre ?? 'Mes disponibilités' ?></h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<!-- Formulaire des disponibilités hebdomadaires -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-calendar-week me-2 text-primary"></i>Disponibilités hebdomadaires</h5>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/medecin/enregistrer-disponibilites" method="POST">
            <div class="table-responsive">
                <table class="table table-bordered table-dispo">
                    <thead>
                        <tr class="table-light">
                            <th style="width: 15%">Jour</th>
                            <th style="width: 10%">Actif</th>
                            <th style="width: 20%">Heure début</th>
                            <th style="width: 20%">Heure fin</th>
                            <th style="width: 20%">Durée consultation</th>
                            <th style="width: 15%">Créneaux estimés</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $joursSemaine = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                        for ($i = 1; $i <= 7; $i++): 
                            $jourNom = $joursSemaine[$i-1];
                            $disp = $dispoParJour[$jourNom] ?? null;
                            $estActif = !is_null($disp);
                            $heureDebut = $disp['heure_debut'] ?? '08:00';
                            $heureFin = $disp['heure_fin'] ?? '17:00';
                            $duree = $disp['duree_consultation'] ?? 30;
                            
                            $debut = strtotime($heureDebut);
                            $fin = strtotime($heureFin);
                            $nbCreneaux = $estActif ? floor(($fin - $debut) / ($duree * 60)) : 0;
                        ?>
                        <tr class="<?= $estActif ? 'jour-actif' : ''; ?>">
                            <td>
                                <strong><?= $jourNom; ?></strong>
                                <?php if ($jourNom == 'Samedi' || $jourNom == 'Dimanche'): ?>
                                    <span class="badge bg-secondary ms-2">Week-end</span>
                                <?php endif; ?>
                                <input type="hidden" name="jour_<?= $i; ?>" value="<?= $jourNom; ?>">
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input actif-switch" type="checkbox" 
                                           name="actif_<?= $i; ?>" value="1" 
                                           data-jour="<?= $i; ?>"
                                           <?= $estActif ? 'checked' : ''; ?>>
                                </div>
                            </td>
                            <td class="time-input">
                                <input type="time" name="heure_debut_<?= $i; ?>" 
                                       class="form-control form-control-sm time-debut" 
                                       data-jour="<?= $i; ?>"
                                       value="<?= $heureDebut; ?>"
                                       <?= !$estActif ? 'disabled' : ''; ?>>
                            </td>
                            <td class="time-input">
                                <input type="time" name="heure_fin_<?= $i; ?>" 
                                       class="form-control form-control-sm time-fin" 
                                       data-jour="<?= $i; ?>"
                                       value="<?= $heureFin; ?>"
                                       <?= !$estActif ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                <select name="duree_<?= $i; ?>" class="form-select form-select-sm duree-select" 
                                        data-jour="<?= $i; ?>"
                                        <?= !$estActif ? 'disabled' : ''; ?>>
                                    <option value="15" <?= $duree == 15 ? 'selected' : ''; ?>>15 minutes</option>
                                    <option value="30" <?= $duree == 30 ? 'selected' : ''; ?>>30 minutes</option>
                                    <option value="45" <?= $duree == 45 ? 'selected' : ''; ?>>45 minutes</option>
                                    <option value="60" <?= $duree == 60 ? 'selected' : ''; ?>>60 minutes</option>
                                </select>
                            </td>
                            <td class="text-center creneaux-count" id="creneaux_<?= $i; ?>">
                                <?php if ($estActif && $nbCreneaux > 0): ?>
                                    <span class="badge bg-info"><?= $nbCreneaux; ?> créneaux</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="alert alert-info mt-3">
                <i class="bi bi-lightbulb me-2"></i>
                <strong>Conseil :</strong> Définissez vos plages horaires de consultation. 
                Les patients pourront prendre rendez-vous uniquement pendant ces créneaux.
            </div>
            
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-2"></i> Enregistrer les disponibilités
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Exceptions -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-ban me-2 text-warning"></i>Exceptions (congés, indisponibilités)</h5>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/medecin/exceptions/ajouter" method="POST" class="row g-3 mb-4 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Date</label>
                <input type="date" name="date_exception" class="form-control" required min="<?= date('Y-m-d'); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="indisponible">🔴 Indisponible (congé)</option>
                    <option value="disponible">🟢 Disponible exceptionnellement</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Motif (optionnel)</label>
                <input type="text" name="motif" class="form-control" placeholder="Ex: Congé, Formation...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-plus me-1"></i> Ajouter
                </button>
            </div>
        </form>

        <?php if (!empty($exceptions)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Motif</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exceptions as $e): 
                            $dateException = $e['date_exception'];
                            $estPasse = strtotime($dateException) < strtotime(date('Y-m-d'));
                        ?>
                        <tr class="<?= $estPasse ? 'text-muted' : ''; ?>">
                            <td>
                                <?= date('d/m/Y', strtotime($dateException)); ?>
                                <?php if ($estPasse): ?>
                                    <span class="badge bg-secondary ms-2">Passé</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $e['type'] == 'indisponible' ? 'danger' : 'success'; ?>">
                                    <?= $e['type'] == 'indisponible' ? '🔴 Indisponible' : '🟢 Disponible'; ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($e['motif'] ?? '-'); ?></td>
                            <td>
                                <?php if (!$estPasse): ?>
                                    <span class="badge bg-warning">À venir</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Passé</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$estPasse): ?>
                                    <a href="<?= BASE_URL; ?>/medecin/exceptions/supprimer/<?= $e['id_exception']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Supprimer cette exception ?')">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-secondary" disabled>
                                        <i class="bi bi-trash"></i> Supprimer
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                <p class="text-muted mb-0">Aucune exception enregistrée.</p>
                <small class="text-muted">Ajoutez vos congés ou indisponibilités exceptionnelles.</small>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Gestion de l'activation/désactivation des champs
    document.querySelectorAll('.actif-switch').forEach(switchBtn => {
        switchBtn.addEventListener('change', function() {
            const jour = this.dataset.jour;
            const debutInput = document.querySelector(`.time-debut[data-jour="${jour}"]`);
            const finInput = document.querySelector(`.time-fin[data-jour="${jour}"]`);
            const dureeSelect = document.querySelector(`.duree-select[data-jour="${jour}"]`);
            const ligne = this.closest('tr');
            
            if (this.checked) {
                debutInput.disabled = false;
                finInput.disabled = false;
                dureeSelect.disabled = false;
                ligne.classList.add('jour-actif');
                calculerCreneaux(jour);
            } else {
                debutInput.disabled = true;
                finInput.disabled = true;
                dureeSelect.disabled = true;
                ligne.classList.remove('jour-actif');
                document.getElementById(`creneaux_${jour}`).innerHTML = '<span class="badge bg-secondary">-</span>';
            }
        });
    });
    
    // Calcul du nombre de créneaux
    function calculerCreneaux(jour) {
        const debut = document.querySelector(`.time-debut[data-jour="${jour}"]`).value;
        const fin = document.querySelector(`.time-fin[data-jour="${jour}"]`).value;
        const duree = parseInt(document.querySelector(`.duree-select[data-jour="${jour}"]`).value);
        
        if (debut && fin && duree) {
            const debutTime = new Date(`2000-01-01T${debut}:00`);
            const finTime = new Date(`2000-01-01T${fin}:00`);
            const diffMinutes = (finTime - debutTime) / (1000 * 60);
            const nbCreneaux = Math.floor(diffMinutes / duree);
            
            const span = document.getElementById(`creneaux_${jour}`);
            if (nbCreneaux > 0) {
                span.innerHTML = `<span class="badge bg-info">${nbCreneaux} créneaux</span>`;
            } else {
                span.innerHTML = '<span class="badge bg-danger">0 créneau</span>';
            }
        }
    }
    
    // Ajouter des écouteurs pour recalculer
    document.querySelectorAll('.time-debut, .time-fin, .duree-select').forEach(input => {
        input.addEventListener('change', function() {
            const jour = this.dataset.jour;
            if (document.querySelector(`.actif-switch[data-jour="${jour}"]`).checked) {
                calculerCreneaux(jour);
            }
        });
    });
</script>