<?php
require_once ROOT_PATH . '/app/Views/layouts/header.php';
require_once ROOT_PATH . '/app/Views/layouts/sidebar.php';
require_once ROOT_PATH . '/app/Views/layouts/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="fas fa-boxes text-info me-2"></i>Stock du Poste</h4>
    <button class="btn btn-info text-white fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalStock">
        <i class="fas fa-edit me-2"></i>Ajuster Stock / Inventaire
    </button>
</div>

<div class="row g-4 mb-4">
    <?php if (empty($stocks)): ?>
        <div class="col-12 text-muted">Aucun stock disponible enregistré.</div>
    <?php else: ?>
        <?php foreach ($stocks as $s): ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-secondary-subtle text-secondary border fw-bold"><?= htmlspecialchars($s['unite'] ?? 'kg') ?></span>
                            <small class="text-muted">Mis à jour: <?= isset($s['derniere_mise_a_jour']) && $s['derniere_mise_a_jour'] ? date('d/m/Y', strtotime($s['derniere_mise_a_jour'])) : 'N/A' ?></small>
                        </div>
                        <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($s['nom_produit'] ?? '') ?></h5>
                        <div class="display-6 fw-bold text-info mt-2">
                            <?= number_format((float)($s['quantite_disponible_kg'] ?? 0), 2, '.', ' ') ?> <span class="fs-6 text-muted"><?= htmlspecialchars($s['unite'] ?? 'kg') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Ajustement Stock -->
<div class="modal fade" id="modalStock" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-boxes text-info me-2"></i>Ajuster le Stock</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockForm" autocomplete="off">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Produit <span class="text-danger">*</span></label>
                        <select name="id_produit" class="form-select" required>
                            <option value="">-- Sélectionner un produit --</option>
                            <?php foreach ($produits as $p): ?>
                                <option value="<?= $p['id_produit'] ?>"><?= htmlspecialchars($p['nom_produit']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nouvelle Quantité Réelle (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="quantite_disponible_kg" class="form-control" placeholder="0.00" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="btnSubmitStock" class="btn btn-info text-white fw-semibold">Enregistrer Ajustement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= \App\Config\Config::BASE_URL ?>/public/assets/js/stock.js"></script>

<?php
require_once ROOT_PATH . '/app/Views/layouts/footer.php';
?>