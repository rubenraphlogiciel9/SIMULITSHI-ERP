<?php
require_once ROOT_PATH . '/app/Views/layouts/header.php';
require_once ROOT_PATH . '/app/Views/layouts/sidebar.php';
require_once ROOT_PATH . '/app/Views/layouts/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="fas fa-users text-primary me-2"></i>Gestion des Fournisseurs & Avances</h4>
    <div>
        <button class="btn btn-outline-primary fw-semibold me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalFournisseur">
            <i class="fas fa-user-plus me-2"></i>Nouveau Fournisseur
        </button>
        <button class="btn btn-warning fw-semibold shadow-sm text-dark" data-bs-toggle="modal" data-bs-target="#modalAvance">
            <i class="fas fa-hand-holding-usd me-2"></i>Accorder une Avance
        </button>
    </div>
</div>

<!-- Onglets -->
<ul class="nav nav-tabs mb-3" id="fournisseurTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active fw-bold" id="list-tab" data-bs-toggle="tab" data-bs-target="#list-pane">
            <i class="fas fa-list me-2"></i>Liste des Fournisseurs
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="avances-tab" data-bs-toggle="tab" data-bs-target="#avances-pane">
            <i class="fas fa-history me-2"></i>Historique des Avances
        </button>
    </li>
</ul>

<div class="tab-content" id="fournisseurTabContent">
    
    <!-- Tab 1 : Liste des Fournisseurs -->
    <div class="tab-pane fade show active" id="list-pane">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Nom & Prénom</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                                <th class="text-end">Avance en Cours ($)</th>
                                <th class="pe-3">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($fournisseurs)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">Aucun fournisseur enregistré.</td></tr>
                            <?php else: ?>
                                <?php foreach ($fournisseurs as $f): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-dark"><?= htmlspecialchars($f['nom'] . ' ' . $f['prenom']) ?></td>
                                        <td><?= htmlspecialchars($f['telephone'] ?: '-') ?></td>
                                        <td><?= htmlspecialchars($f['adresse'] ?: '-') ?></td>
                                        <td class="text-end fw-bold text-danger">
                                            $<?= number_format((float)$f['total_avance_encours'], 2, '.', ' ') ?>
                                        </td>
                                        <td class="pe-3">
                                            <span class="badge bg-<?= $f['statut'] === 'Actif' ? 'success' : 'secondary' ?>">
                                                <?= htmlspecialchars($f['statut']) ?>
                                            </span>
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

    <!-- Tab 2 : Historique des Avances -->
    <div class="tab-pane fade" id="avances-pane">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Fournisseur</th>
                                <th class="text-end">Montant Initial ($)</th>
                                <th class="text-end">Solde Restant ($)</th>
                                <th>Observation</th>
                                <th>Agent</th>
                                <th class="pe-3">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($avances)): ?>
                                <tr><td colspan="7" class="text-center py-4 text-muted">Aucune avance enregistrée.</td></tr>
                            <?php else: ?>
                                <?php foreach ($avances as $av): ?>
                                    <tr>
                                        <td class="ps-3 small text-muted"><?= date('d/m/Y H:i', strtotime($av['date_avance'])) ?></td>
                                        <td class="fw-bold"><?= htmlspecialchars($av['fournisseur_nom']) ?></td>
                                        <td class="text-end fw-semibold">$<?= number_format((float)$av['montant_avance'], 2, '.', ' ') ?></td>
                                        <td class="text-end fw-bold text-danger">$<?= number_format((float)$av['solde_restant'], 2, '.', ' ') ?></td>
                                        <td><?= htmlspecialchars($av['observation'] ?: '-') ?></td>
                                        <td class="small text-muted"><?= htmlspecialchars($av['username']) ?></td>
                                        <td class="pe-3">
                                            <span class="badge bg-<?= $av['statut'] === 'En_cours' ? 'warning text-dark' : 'success' ?>">
                                                <?= htmlspecialchars($av['statut']) ?>
                                            </span>
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

<!-- Modal Nouveau Fournisseur -->
<div class="modal fade" id="modalFournisseur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2 text-primary"></i>Nouveau Fournisseur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="fournisseurForm" autocomplete="off">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Prénom</label>
                        <input type="text" name="prenom" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" placeholder="+243 ...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Adresse / Localité</label>
                        <input type="text" name="adresse" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="btnSubmitFournisseur" class="btn btn-primary fw-semibold">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Accorder Avance -->
<div class="modal fade" id="modalAvance" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-hand-holding-usd me-2 text-warning"></i>Accorder une Avance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="avanceForm" autocomplete="off">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fournisseur <span class="text-danger">*</span></label>
                        <select name="id_fournisseur" class="form-select" required>
                            <option value="">-- Sélectionner un fournisseur --</option>
                            <?php foreach ($fournisseurs as $f): ?>
                                <option value="<?= $f['id_fournisseur'] ?>">
                                    <?= htmlspecialchars($f['nom'] . ' ' . $f['prenom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Montant de l'Avance ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="1" name="montant_avance" class="form-control" placeholder="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observation / Motif</label>
                        <textarea name="observation" class="form-control" rows="2" placeholder="Motif de l'avance..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="btnSubmitAvance" class="btn btn-warning fw-semibold text-dark">Valider l'Avance</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= \App\Config\Config::BASE_URL ?>/public/assets/js/fournisseurs.js"></script>

<?php
require_once ROOT_PATH . '/app/Views/layouts/footer.php';
?>