<?php
use App\Core\Session;

$userRole = Session::get('user_role', '');
$userPoste = Session::get('user_poste_nom', 'Dépôt Central');
?>
<div class="bg-dark text-white border-end" id="sidebar-wrapper">
    <div class="sidebar-heading text-center py-4 fs-5 fw-bold border-bottom bg-gradient-dark">
        <i class="fas fa-leaf text-success me-2"></i>SIMULITSHI ERP
    </div>
    
    <div class="px-3 py-2 text-muted small border-bottom bg-dark-subtle">
        <div><i class="fas fa-map-marker-alt me-1 text-primary"></i> <strong>Poste :</strong> <?= htmlspecialchars($userPoste) ?></div>
        <div><i class="fas fa-user-shield me-1 text-warning"></i> <strong>Rôle :</strong> <?= htmlspecialchars($userRole) ?></div>
    </div>

    <div class="list-group list-group-flush my-2">
        <!-- Tableau de Bord (Accessible à tous) -->
        <a href="<?= \App\Config\Config::BASE_URL ?>/dashboard" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2">
            <i class="fas fa-tachometer-alt me-2 text-info"></i>Tableau de bord
        </a>

        <!-- Section Achats -->
        <?php if (in_array($userRole, ['PDG', 'Administrateur', 'Acheteur'])): ?>
            <div class="sidebar-category text-uppercase fs-7 text-muted px-3 mt-3 mb-1">Achats</div>
            <a href="<?= \App\Config\Config::BASE_URL ?>/achat" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2">
                <i class="fas fa-shopping-basket me-2 text-success"></i>Achats Cacao / Café
            </a>
            <!--  <a href="<?= \App\Config\Config::BASE_URL ?>/avance" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2">
                <i class="fas fa-hand-holding-usd me-2 text-warning"></i>Avances Producteurs
            </a>-->
            <a href="<?= \App\Config\Config::BASE_URL ?>/fournisseur" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2">
                <i class="fas fa-users me-2 text-primary"></i>Gestion Producteurs
            </a>
        <?php endif; ?>

        <!-- Section Trésorerie -->
        <?php if (in_array($userRole, ['PDG', 'Administrateur', 'Caissier'])): ?>
            <div class="sidebar-category text-uppercase fs-7 text-muted px-3 mt-3 mb-1">Trésorerie</div>
            <a href="<?= \App\Config\Config::BASE_URL ?>/caisse" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2">
                <i class="fas fa-wallet me-2 text-danger"></i>Caisse & Mouvements
            </a>
        <?php endif; ?>

        <!-- Section Stock & Logistique -->
        <?php if (in_array($userRole, ['PDG', 'Administrateur', 'Acheteur'])): ?>
            <div class="sidebar-category text-uppercase fs-7 text-muted px-3 mt-3 mb-1">Stock & Expéditions</div>
            <a href="<?= \App\Config\Config::BASE_URL ?>/stock" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2">
                <i class="fas fa-boxes me-2 text-info"></i>État du Stock
            </a>
            <a href="<?= \App\Config\Config::BASE_URL ?>/expedition" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2">
                <i class="fas fa-truck-loading me-2 text-warning"></i>Expéditions usine
            </a>
            <a href="<?= \App\Config\Config::BASE_URL ?>/produit" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2">
                <i class="fas fa-seedling me-2 text-success"></i>Produits & Spéculations
            </a>
        <?php endif; ?>

        <!-- Section Administration -->
        <?php if (in_array($userRole, ['PDG', 'Administrateur'])): ?>
            <div class="sidebar-category text-uppercase fs-7 text-muted px-3 mt-3 mb-1">Administration</div>
            <a href="<?= \App\Config\Config::BASE_URL ?>/utilisateur" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2">
                <i class="fas fa-user-cog me-2 text-light"></i>Utilisateurs & Postes
            </a>
            <a href="<?= \App\Config\Config::BASE_URL ?>/rapport" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2">
                <i class="fas fa-chart-line me-2 text-primary"></i>Rapports Globaux
            </a>
        <?php endif; ?>
    </div>
</div>