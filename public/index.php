<?php
declare(strict_types=1);

/**
 * SIMULITSHI ERP - Point d'entrée principal
 */

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('ROOT_PATH', dirname(__DIR__));

// Autoloader PSR-4
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = ROOT_PATH . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Core\Router;

$router = new Router();

// --- DÉCLARATION DES ROUTES ---

// Auth
$router->add('GET', '/login', 'AuthController@login');
$router->add('POST', '/login', 'AuthController@authenticate');
$router->add('POST', '/authenticate', 'AuthController@authenticate');
$router->add('GET', '/logout', 'AuthController@logout');

// Dashboard & Accueil
$router->add('GET', '/', 'DashboardController@index');
$router->add('GET', '/dashboard', 'DashboardController@index');

// Achats
$router->add('GET', '/achat', 'AchatController@index');
$router->add('POST', '/achat/store', 'AchatController@store');

// Avances
$router->add('GET', '/avance', 'AvanceController@index');
$router->add('POST', '/avance/store', 'AvanceController@store');

// Caisse
$router->add('GET', '/caisse', 'CaisseController@index');
$router->add('POST', '/caisse/store', 'CaisseController@store');

// Fournisseurs
$router->add('GET', '/fournisseur', 'FournisseurController@index');
$router->add('POST', '/fournisseur/store', 'FournisseurController@store');
$router->add('POST', '/fournisseur/storeAvance', 'FournisseurController@storeAvance');
$router->add('POST', '/fournisseur/avance/store', 'FournisseurController@storeAvance');

// Expéditions
$router->add('GET', '/expedition', 'ExpeditionController@index');
$router->add('POST', '/expedition/store', 'ExpeditionController@store');

// Stock
$router->add('GET', '/stock', 'StockController@index');
$router->add('POST', '/stock/store', 'StockController@store');
// Exemple dans ton routeur :
// Stock
$router->add('GET', '/stock', 'StockController@index');
$router->add('POST', '/stock/store', 'StockController@store');
// Stock

$router->add('POST', '/stock/ajuster', 'StockController@store');
// Produits
$router->add('GET', '/produit', 'ProduitController@index');
$router->add('POST', '/produit/store', 'ProduitController@store');
$router->add('POST', '/produit/toggle-statut', 'ProduitController@toggleStatut');

// Utilisateurs
$router->add('GET', '/utilisateur', 'UtilisateurController@index');
$router->add('POST', '/utilisateur/store', 'UtilisateurController@store');
$router->add('POST', '/utilisateur/toggle', 'UtilisateurController@toggleStatut');

// Expéditions
$router->add('GET', '/expedition', 'ExpeditionController@index');
$router->add('POST', '/expedition/store', 'ExpeditionController@store');

// Rapports
$router->add('GET', '/rapport', 'RapportController@index');

// Exécution du routage
$router->dispatch($_SERVER['REQUEST_URI']);