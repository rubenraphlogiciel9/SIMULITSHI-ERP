<?php
require_once ROOT_PATH . '/app/Views/layouts/header.php';
require_once ROOT_PATH . '/app/Views/layouts/sidebar.php';
require_once ROOT_PATH . '/app/Views/layouts/navbar.php';

// Sécurisation des tableaux au cas où le contrôleur envoie null
$achats       = $achats ?? [];
$producteurs  = $producteurs ?? [];
$produits     = $produits ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="fas fa-shopping-basket text-success me-2"></i>Achats Cacao / Café</h4>
    <button class="btn btn-success fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAchat">
        <i class="fas fa-plus-circle me-2"></i>Nouvel Achat
    </button>
</div>

<!-- Table des Derniers Achats -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Code</th>
                        <th>Date</th>
                        <th>Fournisseur</th>
                        <th>Produit</th>
                        <th class="text-end">Poids Net (kg)</th>
                        <th class="text-end">P.U ($)</th>
                        <th class="text-end">Total ($)</th>
                        <th class="text-end">Cash ($)</th>
                        <th class="text-end">Avance ($)</th>
                        <th class="pe-3">Qualité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($achats)): ?>
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">Aucun achat enregistré pour ce poste.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($achats as $a): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-primary"><?= htmlspecialchars($a['numero_achat'] ?? $a['code_achat'] ?? '') ?></td>
                                <td class="small text-muted"><?= isset($a['date_achat']) ? date('d/m/Y H:i', strtotime($a['date_achat'])) : '-' ?></td>
                                <td><?= htmlspecialchars($a['producteur_nom'] ?? $a['fournisseur_nom'] ?? '') ?></td>
                                <td><span class="badge bg-secondary-subtle text-secondary border"><?= htmlspecialchars($a['nom_produit'] ?? '') ?></span></td>
                                <td class="text-end fw-semibold"><?= number_format((float)($a['poids_net'] ?? 0), 2, '.', ' ') ?></td>
                                <td class="text-end">$<?= number_format((float)($a['prix_kg'] ?? 0), 2, '.', ' ') ?></td>
                                <td class="text-end fw-bold text-dark">$<?= number_format((float)($a['montant'] ?? 0), 2, '.', ' ') ?></td>
                                <td class="text-end text-success fw-semibold">$<?= number_format((float)($a['montant_cash'] ?? 0), 2, '.', ' ') ?></td>
                                <td class="text-end text-warning fw-semibold">$<?= number_format((float)($a['montant_avance'] ?? 0), 2, '.', ' ') ?></td>
                                <td class="pe-3">
                                    <?php $qualite = $a['qualite'] ?? 'Bonne'; ?>
                                    <span class="badge bg-<?= $qualite === 'Bonne' ? 'success' : ($qualite === 'Moyenne' ? 'warning' : 'danger') ?>">
                                        <?= htmlspecialchars($qualite) ?>
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

<!-- Modal Nouvel Achat -->
<div class="modal fade" id="modalAchat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-cart-plus me-2 text-success"></i>Enregistrer un Achat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="achatForm" autocomplete="off">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fournisseur / Producteur <span class="text-danger">*</span></label>
                            <select name="id_producteur" class="form-select" required>
                                <option value="">-- Sélectionner un producteur --</option>
                                <?php foreach ($producteurs as $p): ?>
                                    <option value="<?= $p['id_fournisseur'] ?>">
                                        <?= htmlspecialchars(trim(($p['nom'] ?? '') . ' '  . ($p['prenom'] ?? ''))) ?> (<?= htmlspecialchars($p['telephone'] ?? 'Sans tél') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Produit <span class="text-danger">*</span></label>
                            <select name="id_produit" class="form-select" required>
                                <option value="">-- Sélectionner un produit --</option>
                                <?php foreach ($produits as $prod): ?>
                                    <option value="<?= $prod['id_produit'] ?>">
                                        <?= htmlspecialchars($prod['designation'] ?? '') ?> (<?= htmlspecialchars($prod['unite'] ?? 'Kg') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Poids Brut (kg) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="poids_brut" id="poids_brut" class="form-control" placeholder="0.00" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tare / Sacs (kg)</label>
                            <input type="number" step="0.01" min="0" name="tare" id="tare" class="form-control" value="0.00">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Poids Net (kg)</label>
                            <input type="number" step="0.01" name="poids_net" id="poids_net" class="form-control bg-light" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Prix Unitaire par Kg ($) <span class="text-danger">*</span></label>
                            <!-- CORRECTION : name="prix_kg" au lieu de prix_unitaire -->
                            <input type="number" step="0.01" min="0" name="prix_kg" id="prix_unitaire" class="form-control" placeholder="0.00" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Montant Total ($)</label>
                            <!-- CORRECTION : ajout de name="montant" -->
                            <input type="number" step="0.01" name="montant" id="montant_total" class="form-control bg-light fw-bold text-success" readonly>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Qualité du Produit</label>
                            <select name="qualite" class="form-select">
                                <option value="Bonne" selected>Bonne</option>
                                <option value="Moyenne">Moyenne</option>
                                <option value="Mauvaise">Mauvaise</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="btnSubmitAchat" class="btn btn-success fw-semibold">
                        <i class="fas fa-save me-2"></i>Valider l'Achat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= \App\Config\Config::BASE_URL ?>/public/assets/js/achats.js"></script>

<?php
require_once ROOT_PATH . '/app/Views/layouts/footer.php';
?>