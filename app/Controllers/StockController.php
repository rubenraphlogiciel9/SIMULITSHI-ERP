<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Services\StockService;
use App\Config\Database;
use PDO;

class StockController extends Controller
{
    private StockService $stockService;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->stockService = new StockService();
    }

    public function index(): void
    {
        $db = Database::getConnection();

        // CORRECTION : Suppression de code_produit qui n'existe pas dans la table
        $stmtProduit = $db->prepare("
            SELECT id_produit, designation AS nom_produit, unite 
            FROM produit 
            WHERE statut = 'Actif' 
            ORDER BY designation ASC
        ");
        $stmtProduit->execute();
        $produits = $stmtProduit->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Récupérer l'état du stock actuel pour le poste
        $stocks = $this->stockService->getStockActuel();

        $this->render('stock/index', [
            'title'    => 'Gestion des Stocks',
            'stocks'   => $stocks,
            'produits' => $produits
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
            return;
        }

        $response = $this->stockService->processAjustement($_POST);
        $this->json($response);
    }
}