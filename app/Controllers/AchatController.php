<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Services\AchatService;
use App\Config\Database;
use PDO;

class AchatController extends Controller
{
    private AchatService $achatService;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->achatService = new AchatService();
    }

    public function index(): void
    {
        $db = Database::getConnection();

        // 1. Producteurs actifs pour la liste déroulante
        $stmtProd = $db->prepare("
            SELECT id_fournisseur, nom, prenom, telephone 
            FROM fournisseur 
            WHERE statut = 'Actif' 
            ORDER BY nom ASC, prenom ASC
        ");
        $stmtProd->execute();
        $producteurs = $stmtProd->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // 2. Produits actifs (Cacao, Café, etc.)
        $stmtProduit = $db->prepare("
            SELECT id_produit, designation, unite 
            FROM produit 
            WHERE statut = 'Actif' 
            ORDER BY designation ASC
        ");
        $stmtProduit->execute();
        $produits = $stmtProduit->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // 3. Historique des achats du dépôt/poste
        $achats = $this->achatService->getListeAchats();

        // 4. Rendu de la vue
        $this->render('achats/index', [
            'title'       => 'Achats Cacao / Café',
            'producteurs' => $producteurs,
            'produits'    => $produits,
            'achats'      => $achats
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
            return;
        }

        $response = $this->achatService->processAchat($_POST);
        $this->json($response);
    }
}