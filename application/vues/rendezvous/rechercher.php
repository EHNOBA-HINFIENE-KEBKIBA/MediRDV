<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .filter-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        padding: 2rem;
        margin-bottom: 2.5rem;
    }

    .medecin-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .medecin-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8faff 100%);
        border-radius: 24px;
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1.2);
        cursor: pointer;
        border: 1px solid rgba(13, 110, 253, 0.08);
    }
    .medecin-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(13, 110, 253, 0.15);
        border-color: rgba(13, 110, 253, 0.25);
    }

    .card-img-wrapper {
        height: 160px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .card-img-wrapper::after {
        content: "";
        position: absolute;
        bottom: -30px;
        left: 0;
        width: 100%;
        height: 60px;
        background: white;
        border-radius: 50% 50% 0 0;
    }
    .medecin-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 1;
        background: #e9ecef;
    }
    .medecin-avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 1;
    }
    .medecin-avatar-placeholder i {
        font-size: 3rem;
        color: white;
        opacity: 0.8;
    }

    .card-body {
        padding: 1.5rem 1.5rem 1.5rem;
        text-align: center;
    }
    .card-body h5 {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .specialite-badge {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        font-weight: 600;
        padding: 0.3rem 1rem;
        border-radius: 30px;
        font-size: 0.8rem;
        display: inline-block;
        margin-bottom: 0.75rem;
    }
    .info-item {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
    }
    .btn-choisir {
        border-radius: 30px;
        font-weight: 600;
        padding: 0.5rem 1.5rem;
        transition: all 0.2s ease;
        border: 2px solid #0d6efd;
        color: #0d6efd;
        background: transparent;
        margin-top: 1rem;
    }
    .btn-choisir:hover {
        background: #0d6efd;
        color: white;
        transform: scale(1.03);
    }

    /* Bloc détail */
    .detail-block {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        padding: 2.5rem;
        animation: fadeSlideIn 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        margin-bottom: 2rem;
    }
    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .creneau-btn {
        border-radius: 10px;
        margin: 0.2rem;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        transition: all 0.15s;
    }
    .creneau-btn.active {
        background-color: #0d6efd !important;
        color: white !important;
        box-shadow: 0 4px 10px rgba(13,110,253,0.3);
    }
    .mini-form {
        margin-top: 20px;
        padding: 20px;
        background: #f8faff;
        border-radius: 16px;
        border: 1px solid #e7ecf5;
    }
</style>

<h2 class="fw-bold mb-4"><i class="bi bi-search-heart me-2 text-primary"></i>Trouver un médecin</h2>

<!-- FILTRES (recherche) -->
<div class="filter-card" id="blocFiltres">
    <form id="formFiltres" class="row g-3">
        <div class="col-md-3">
            <label for="specialite" class="form-label fw-semibold">Spécialité</label>
            <select class="form-select" id="specialite" name="specialite">
                <option value="">Toutes</option>
                <?php foreach ($specialites as $spe): ?>
                <option value="<?= $spe['id_specialite'] ?>"><?= htmlspecialchars($spe['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="ville" class="form-label fw-semibold">Ville</label>
            <select class="form-select" id="ville" name="ville">
                <option value="">Toutes</option>
                <?php foreach ($villes as $v): ?>
                <option value="<?= $v['id_ville'] ?>"><?= htmlspecialchars($v['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="nom" class="form-label fw-semibold">Nom du médecin</label>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Ex : Dr. Dupont">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Rechercher</button>
        </div>
    </form>
</div>

<!-- RÉSULTATS -->
<div id="resultatsRecherche">
    <p class="text-muted text-center mt-4">🔍 Utilisez les filtres pour afficher les médecins.</p>
</div>

<!-- Bloc de réservation (affiché après sélection d'un médecin) -->
<div id="blocReservation" style="display:none;"></div>

<script>
function escapeHtml(text) {
    const map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
    return text.replace(/[&<>"']/g, m => map[m]);
}

let medecinsActuels = [];

document.getElementById('formFiltres').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams(formData).toString();
    const container = document.getElementById('resultatsRecherche');
    container.innerHTML = '<p class="text-muted text-center mt-4">Recherche en cours...</p>';
    document.getElementById('blocReservation').style.display = 'none';

    fetch('<?= BASE_URL ?>/rendez-vous/rechercher-ajax?' + params, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(medecins => {
        container.innerHTML = '';
        medecinsActuels = medecins;
        if (!medecins.length) {
            container.innerHTML = '<p class="text-muted text-center mt-4">Aucun médecin trouvé.</p>';
            return;
        }
        afficherCartesMedecins(medecins);
    })
    .catch(err => {
        console.error('Erreur recherche', err);
        container.innerHTML = '<p class="text-danger text-center mt-4">Erreur lors de la recherche.</p>';
    });
});

function afficherCartesMedecins(medecins) {
    const container = document.getElementById('resultatsRecherche');
    container.innerHTML = '<div class="medecin-grid"></div>';
    const grid = container.querySelector('.medecin-grid');

    medecins.forEach(med => {
        const card = document.createElement('div');
        card.className = 'medecin-card';

        let avatarHtml;
        if (med.photo) {
            avatarHtml = `<img src="<?= BASE_URL ?>/` + med.photo + `" class="medecin-avatar" alt="Dr. ${escapeHtml(med.nom)}">`;
        } else {
            avatarHtml = `<div class="medecin-avatar-placeholder"><i class="bi bi-person-fill"></i></div>`;
        }

        card.innerHTML = `
            <div class="card-img-wrapper">
                ${avatarHtml}
            </div>
            <div class="card-body">
                <h5>Dr. ${escapeHtml(med.nom)} ${escapeHtml(med.prenom)}</h5>
                <span class="specialite-badge">${escapeHtml(med.specialite_nom || '')}</span>
                <div class="info-item"><i class="bi bi-building"></i>${escapeHtml(med.etablissement_nom || '')}</div>
                <div class="info-item"><i class="bi bi-geo-alt"></i>${escapeHtml(med.ville_nom || '')}</div>
                ${med.experience ? `<div class="info-item"><i class="bi bi-clock-history"></i>${med.experience} ans d'expérience</div>` : ''}
                ${med.diplomes ? `<div class="info-item"><i class="bi bi-mortarboard"></i>${escapeHtml(med.diplomes)}</div>` : ''}
                <button class="btn-choisir w-100" data-id="${med.id_medecin}">
                    <i class="bi bi-check2-circle me-1"></i>Choisir ce médecin
                </button>
            </div>
        `;
        grid.appendChild(card);
    });

    document.querySelectorAll('.btn-choisir').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const med = medecinsActuels.find(m => m.id_medecin == id);
            afficherBlocReservation(med);
        });
    });
}

function afficherBlocReservation(med) {
    document.getElementById('resultatsRecherche').style.display = 'none';
    document.getElementById('blocFiltres').style.display = 'none';
    const bloc = document.getElementById('blocReservation');
    bloc.style.display = 'block';

    let avatarHtml;
    if (med.photo) {
        avatarHtml = `<img src="<?= BASE_URL ?>/` + med.photo + `" class="medecin-avatar" style="width:120px; height:120px; border:4px solid white; box-shadow:0 4px 12px rgba(0,0,0,0.2);" alt="Dr. ${escapeHtml(med.nom)}">`;
    } else {
        avatarHtml = `<div class="medecin-avatar-placeholder" style="width:120px; height:120px; margin:0 auto;"><i class="bi bi-person-fill fs-1 text-secondary"></i></div>`;
    }

    bloc.innerHTML = `
        <div class="detail-block">
            <button class="btn btn-outline-secondary btn-sm mb-3" id="btnRetourListe">
                <i class="bi bi-arrow-left me-1"></i> Retour à la liste
            </button>
            <div class="text-center mb-4">
                ${avatarHtml}
            </div>
            <h4 class="text-center mb-1">Dr. ${escapeHtml(med.nom)} ${escapeHtml(med.prenom)}</h4>
            <p class="text-center"><span class="specialite-badge">${escapeHtml(med.specialite_nom || '')}</span></p>
            <div class="row justify-content-center text-muted small">
                <div class="col-auto"><i class="bi bi-building me-1"></i>${escapeHtml(med.etablissement_nom || '')}</div>
                <div class="col-auto"><i class="bi bi-geo-alt me-1"></i>${escapeHtml(med.ville_nom || '')}</div>
                ${med.experience ? `<div class="col-auto"><i class="bi bi-clock-history me-1"></i>${med.experience} ans</div>` : ''}
                ${med.diplomes ? `<div class="col-auto"><i class="bi bi-mortarboard me-1"></i>${escapeHtml(med.diplomes)}</div>` : ''}
            </div>
            <hr>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Date souhaitée</label>
                    <input type="date" id="dateDetail" class="form-control" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="btnChargerCreneaux">Voir les créneaux</button>
                </div>
            </div>
            <div id="creneaux-detail" class="mt-3"></div>
            <div id="mini-form-detail" class="mini-form mt-3" style="display:none;"></div>
        </div>
    `;

    document.getElementById('btnRetourListe').addEventListener('click', function() {
        bloc.style.display = 'none';
        document.getElementById('resultatsRecherche').style.display = 'block';
        document.getElementById('blocFiltres').style.display = 'block';
    });

    document.getElementById('btnChargerCreneaux').addEventListener('click', function() {
        const date = document.getElementById('dateDetail').value;
        chargerCreneauxDetail(med.id_medecin, date);
    });
}

function chargerCreneauxDetail(idMedecin, date) {
    const container = document.getElementById('creneaux-detail');
    container.innerHTML = '<span class="text-muted">Chargement...</span>';

    fetch(`<?= BASE_URL ?>/prendre-rdv/creneaux/${idMedecin}?date=${date}`)
        .then(r => {
            if (!r.ok) throw new Error('Erreur HTTP ' + r.status);
            return r.json();
        })
        .then(creneaux => {
            container.innerHTML = '';
            if (!creneaux.length) {
                container.innerHTML = '<p class="text-muted">Aucun créneau disponible.</p>';
                return;
            }
            const divCreneaux = document.createElement('div');
            divCreneaux.className = 'd-flex flex-wrap';
            creneaux.forEach(item => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-sm m-1 ' + (item.libre ? 'btn-outline-success creneau-btn' : 'btn-outline-secondary disabled');
                btn.textContent = item.heure.substr(0,5);
                btn.disabled = !item.libre;
                if (item.libre) {
                    btn.addEventListener('click', function() {
                        divCreneaux.querySelectorAll('.active').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        afficherMiniFormulaireDetail(idMedecin, item.heure, date);
                    });
                }
                divCreneaux.appendChild(btn);
            });
            container.appendChild(divCreneaux);
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = '<p class="text-danger">Erreur de chargement.</p>';
        });
}

function afficherMiniFormulaireDetail(idMedecin, heure, date) {
    const mini = document.getElementById('mini-form-detail');
    mini.style.display = 'block';
    mini.innerHTML = `
        <div class="mb-2">
            <label class="form-label fw-semibold">Motif de consultation</label>
            <textarea id="motif-detail" class="form-control" rows="2" placeholder="Décrivez brièvement le motif..."></textarea>
        </div>
        <div class="mb-2">
            <label class="form-label fw-semibold">Documents (optionnel)</label>
            <input type="file" id="documents-detail" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
        </div>
        <button class="btn btn-primary btn-sm" id="btn-reserver-detail">Réserver</button>
        <button class="btn btn-outline-secondary btn-sm ms-2" id="btn-annuler-detail">Annuler</button>
    `;

    document.getElementById('btn-annuler-detail').addEventListener('click', function() {
        mini.style.display = 'none';
        mini.innerHTML = '';
        const liste = document.querySelector('#creneaux-detail .d-flex');
        if (liste) liste.querySelectorAll('.active').forEach(b => b.classList.remove('active'));
    });

    document.getElementById('btn-reserver-detail').addEventListener('click', function() {
        const motif = document.getElementById('motif-detail').value.trim();
        if (!motif) {
            alert('Veuillez indiquer le motif.');
            return;
        }

        const formData = new FormData();
        formData.append('id_medecin', idMedecin);
        formData.append('date', date);
        formData.append('heure', heure);
        formData.append('motif', motif);
        formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');

        const fileInput = document.getElementById('documents-detail');
        if (fileInput && fileInput.files.length > 0) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append('documents[]', fileInput.files[i]);
            }
        }

        fetch('<?= BASE_URL ?>/prendre-rdv/reserver', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?= BASE_URL ?>/mes-rendezvous';
            } else {
                alert(data.message);
            }
        })
        .catch(err => alert('Erreur réseau'));
    });
}
</script>