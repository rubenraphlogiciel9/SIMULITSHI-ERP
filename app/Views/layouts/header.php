<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Tableau de bord' ?> - <?= \App\Config\Config::APP_NAME ?></title>

    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Style personnalisé -->
    <link rel="stylesheet" href="<?= \App\Config\Config::BASE_URL ?>/public/assets/css/style.css">

    <script>
        const BASE_URL = "<?= \App\Config\Config::BASE_URL ?>";
    </script>
</head>
<body>
<div class="d-flex" id="wrapper">