<?php
use App\Core\Session;
?>
<div id="page-content-wrapper" class="w-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 shadow-sm">
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-secondary btn-sm me-3" id="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="mb-0 fw-semibold text-secondary"><?= $title ?? 'Tableau de bord' ?></h5>
        </div>

        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle fw-semibold text-dark" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle fa-lg me-1 text-primary"></i>
                    <?= htmlspecialchars(Session::get('user_name', 'Utilisateur')) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><span class="dropdown-item-text text-muted small">Connecté sous : <strong><?= Session::get('user_role') ?></strong></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger fw-semibold" href="<?= \App\Config\Config::BASE_URL ?>/logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid p-4">