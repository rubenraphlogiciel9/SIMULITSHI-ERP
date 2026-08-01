<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - <?= \App\Config\Config::APP_NAME ?></title>

    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= \App\Config\Config::BASE_URL ?>/public/assets/css/login.css">

    <script>
        const BASE_URL = "<?= \App\Config\Config::BASE_URL ?>";
    </script>
</head>
<body class="login-body">

    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-leaf text-white"></i>
            </div>
            <h4 class="fw-bold mb-1">ETS SIMULITSHI</h4>
            <p class="text-white-50 mb-0 small">Gestion Intégrée des Postes d'Achat</p>
        </div>

        <div class="login-body-content">
            <form id="loginForm" autocomplete="off">
                <div class="mb-3">
                    <label for="username" class="form-label text-secondary fw-semibold">Nom d'utilisateur</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label text-secondary fw-semibold">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                        <span class="input-group-text" id="togglePassword">
                            <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="btn btn-primary-custom text-white w-100 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> SE CONNECTER
                </button>
            </form>
        </div>

        <div class="card-footer bg-light text-center py-3 border-0">
            <small class="text-muted">&copy; 2026 Établissement Simulitshi — v1.0</small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    <script src="<?= \App\Config\Config::BASE_URL ?>/public/assets/js/login.js"></script>
</body>
</html>