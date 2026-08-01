<?php
require_once ROOT_PATH . '/app/Views/layouts/header.php';
require_once ROOT_PATH . '/app/Views/layouts/sidebar.php';
require_once ROOT_PATH . '/app/Views/layouts/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="fas fa-seedling text-success me-2"></i>Gestion des Produits & Spéculations</h4>
    <button class="btn btn-primary fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalProduit">
        <i class="fas fa-plus-circle me-2"></i>Nouveau Produit
    </button>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Code / ID</th>
                        <th>Nom du Produit</th>
                        <th>Unité</th>
                        <th>Statut</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($produits)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">Aucun produit configuré.</td></tr>
                    <?php else: ?>
                        <?php foreach ($produits as $p): ?>
                            <?php 
                                // Détection dynamique des clés
                                $codeAffiche  = $p['code_produit'] ?? $p['code'] ?? $p['code_prod'] ?? $p['id_produit'] ?? $p['id'] ?? 'N/A';
                                $nomAffiche   = $p['nom_produit'] ?? $p['nom'] ?? $p['designation'] ?? $p['libelle'] ?? 'Produit sans nom';
                                $uniteAffiche = $p['unite'] ?? $p['unite_mesure'] ?? 'Kg';
                                $idProduit    = $p['id_produit'] ?? $p['id'] ?? 0;
                            ?>
                            <tr>
                                <td class="ps-3 fw-bold text-primary">
                                    <code><?= htmlspecialchars($codeAffiche) ?></code>
                                </td>
                                <td class="fw-semibold">
                                    <?= htmlspecialchars($nomAffiche) ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fw-bold"><?= htmlspecialchars($uniteAffiche) ?></span>
                                </td>
                                <td>
                                    <?php if (($p['statut'] ?? '') === 'Actif'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <?php if (($p['statut'] ?? '') === 'Actif'): ?>
                                        <button onclick="toggleStatutProduit(<?= (int)$idProduit ?>, 'Inactif')" class="btn btn-sm btn-outline-danger">
                                            Désactiver
                                        </button>
                                    <?php else: ?>
                                        <button onclick="toggleStatutProduit(<?= (int)$idProduit ?>, 'Actif')" class="btn btn-sm btn-outline-success">
                                            Activer
                                        </button>
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

<!-- Modal Nouveau Produit -->
<div class="modal fade" id="modalProduit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-box me-2 text-primary"></i>Ajouter un produit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="produitForm" autocomplete="off">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Code Produit <span class="text-danger">*</span></label>
                        <input type="text" name="code_produit" class="form-control" placeholder="Ex: CAC-01, CAF-ROB" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom du Produit <span class="text-danger">*</span></label>
                        <input type="text" name="nom_produit" class="form-control" placeholder="Ex: Cacao Marchand, Café Arabica" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Unité de mesure <span class="text-danger">*</span></label>
                        <select name="unite" class="form-select" required>
                            <option value="Kg" selected>Kg</option>
                            <option value="Tonne">Tonne</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="btnSaveProduit" class="btn btn-primary fw-semibold">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= \App\Config\Config::BASE_URL ?>/public/assets/js/produits.js"></script>

<?php
require_once ROOT_PATH . '/app/Views/layouts/footer.php';
?>