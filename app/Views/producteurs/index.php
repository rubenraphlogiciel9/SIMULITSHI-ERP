<?php
require_once ROOT_PATH . '/app/Views/layouts/header.php';
require_once ROOT_PATH . '/app/Views/layouts/sidebar.php';
require_once ROOT_PATH . '/app/Views/layouts/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-truck text-primary me-2"></i>Gestion des Fournisseurs & Avances
        </h4>
        <p class="text-muted small mb-0">Suivi du répertoire des planteurs/fournisseurs et des avances de caisse octroyées.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAvance">
            <i class="fas fa-hand-holding-usd me-2"></i>Accorder une Avance
        </button>
        <button class="btn btn-primary fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalFournisseur">
            <i class="fas fa-user-plus me-2"></i>Nouveau Fournisseur
        </button>
    </div>
</div>

<!-- Nav tabs -->
<ul class="nav nav-tabs nav-tabs-bordered mb-3" id="fournisseurTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold" id="liste-tab" data-bs-toggle="tab" data-bs-target="#liste-pane" type="button" role="tab">
            <i class="fas fa-list me-2"></i>Liste des Fournisseurs (<?= count($fournisseurs ?? []) ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold" id="avances-tab" data-bs-toggle="tab" data-bs-target="#avances-pane" type="button" role="tab">
            <i class="fas fa-history me-2"></i>Historique des Avances (<?= count($avances ?? []) ?>)
        </button>
    </li>
</ul>

<div class="tab-content" id="fournisseurTabContent">
    
    <!-- ONGLET 1 : Liste des Fournisseurs -->
    <div class="tab-pane fade show active" id="liste-pane" role="tabpanel" tabindex="0">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3"># ID</th>
                                <th>Nom & Prénom</th>
                                <th>Téléphone</th>
                                <th>Adresse / Site</th>
                                <th class="text-end">Avances en cours ($)</th>
                                <th class="text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($fournisseurs)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Aucun fournisseur enregistré pour le moment.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($fournisseurs as $f): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-secondary">
                                            #<?= (int)$f['id_fournisseur'] ?>
                                        </td>
                                        <td class="fw-semibold text-dark">
                                            <?= htmlspecialchars($f['nom'] . ' ' . ($f['prenom'] ?? '')) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($f['telephone'] ?: '-') ?>
                                        </td>
                                        <td class="text-muted">
                                            <?= htmlspecialchars($f['adresse'] ?: '-') ?>
                                        </td>
                                        <td class="text-end fw-bold text-danger">
                                            $<?= number_format((float)($f['total_avance_encours'] ?? 0), 2, '.', ' ') ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (($f['statut'] ?? 'Actif') === 'Actif'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success">Actif</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ONGLET 2 : Historique des Avances -->
    <div class="tab-pane fade" id="avances-pane" role="tabpanel" tabindex="0">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Fournisseur</th>
                                <th>Montant Initial</th>
                                <th>Solde Restant</th>
                                <th>Observation</th>
                                <th>Agent</th>
                                <th class="text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($avances)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Aucun historique d'avance disponible.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($avances as $av): ?>
                                    <tr>
                                        <td class="ps-3 small text-muted">
                                            <?= date('d/m/Y H:i', strtotime($av['date_avance'])) ?>
                                        </td>
                                        <td class="fw-semibold">
                                            <?= htmlspecialchars($av['fournisseur_nom']) ?>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            $<?= number_format((float)$av['montant_avance'], 2, '.', ' ') ?>
                                        </td>
                                        <td class="fw-bold text-warning-emphasis">
                                            $<?= number_format((float)$av['solde_restant'], 2, '.', ' ') ?>
                                        </td>
                                        <td class="small text-muted">
                                            <?= htmlspecialchars($av['observation'] ?: '-') ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <?= htmlspecialchars($av['username'] ?? 'User') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($av['statut'] === 'En_cours'): ?>
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning">En cours</span>
                                            <?php else: ?>
                                                <span class="badge bg-success-subtle text-success border border-success">Solder / Apuré</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal 1 : Créer Fournisseur -->
<div class="modal fade" id="modalFournisseur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2 text-primary"></i>Nouveau Fournisseur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCreateFournisseur" autocomplete="off">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" placeholder="Ex: KAKULE" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Prénom</label>
                            <input type="text" name="prenom" class="form-control" placeholder="Ex: Jean-Baptiste">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" placeholder="Ex: +243 990 000 000">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Adresse / Localité</label>
                        <textarea name="adresse" class="form-control" rows="2" placeholder="Ex: Nobili, Depot Cacao"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="btnSaveFournisseur" class="btn btn-primary fw-semibold">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2 : Octroyer Avance -->
<div class="modal fade" id="modalAvance" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-hand-holding-usd me-2"></i>Octroyer une Avance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formProcessAvance" autocomplete="off">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sélectionner le Fournisseur <span class="text-danger">*</span></label>
                        <select name="id_fournisseur" class="form-select" required>
                            <option value="">-- Choisir un fournisseur --</option>
                            <?php foreach ($fournisseurs as $f): ?>
                                <option value="<?= (int)$f['id_fournisseur'] ?>">
                                    <?= htmlspecialchars($f['nom'] . ' ' . ($f['prenom'] ?? '')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Montant de l'avance ($ USD) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.1" name="montant_avance" class="form-control" placeholder="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observation / Motif</label>
                        <textarea name="observation" class="form-control" rows="2" placeholder="Ex: Avance sur campagne cacao Nobili"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="btnSaveAvance" class="btn btn-primary fw-semibold">Valider & Décaisser</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = "<?= \App\Config\Config::BASE_URL ?>";

    // 1. Soumission Création Fournisseur
    const formFournisseur = document.getElementById('formCreateFournisseur');
    if (formFournisseur) {
        formFournisseur.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSaveFournisseur');
            btn.disabled = true;

            fetch(baseUrl + '/fournisseur/store', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(err => {
                btn.disabled = false;
                alert('Erreur réseau ou serveur lors de l\'enregistrement.');
            });
        });
    }

    // 2. Soumission Octroi Avance
    const formAvance = document.getElementById('formProcessAvance');
    if (formAvance) {
        formAvance.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSaveAvance');
            btn.disabled = true;

            fetch(baseUrl + '/fournisseur/storeAvance', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(err => {
                btn.disabled = false;
                alert('Erreur réseau ou solde de caisse insuffisant.');
            });
        });
    }
});
</script>

<?php
require_once ROOT_PATH . '/app/Views/layouts/footer.php';
?>