<?php
require_once ROOT_PATH . '/app/Views/layouts/header.php';
require_once ROOT_PATH . '/app/Views/layouts/sidebar.php';
require_once ROOT_PATH . '/app/Views/layouts/navbar.php';
?>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card card-stat bg-white p-3 border-start border-4 border-success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">Solde Caisse Actuel</span>
                    <h3 class="fw-bold text-dark mb-0">$<?= number_format((float)$soldeCaisse, 2, '.', ' ') ?></h3>
                </div>
                <div class="bg-success-subtle p-3 rounded-circle text-success">
                    <i class="fas fa-wallet fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($stocks)): ?>
        <?php foreach ($stocks as $stock): ?>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card card-stat bg-white p-3 border-start border-4 border-primary">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">Stock <?= htmlspecialchars($stock['nom_produit']) ?></span>
                            <h3 class="fw-bold text-dark mb-0"><?= number_format((float)$stock['total_kg'], 1, '.', ' ') ?> <small class="fs-6 text-muted">kg</small></h3>
                        </div>
                        <div class="bg-primary-subtle p-3 rounded-circle text-primary">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <h5 class="fw-bold text-dark mb-2"><i class="fas fa-info-circle text-info me-2"></i>Bienvenue dans SIMULITSHI ERP</h5>
        <p class="text-muted mb-0">
            Sélectionnez une rubrique dans le menu latéral pour enregistrer des achats de cacao/café, octroyer des avances aux fournisseurs, gérer les mouvements de caisse ou suivre les expéditions vers le siège.
        </p>
    </div>
</div>

<?php
require_once ROOT_PATH . '/app/Views/layouts/footer.php';
?>