<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Services\ExpeditionService;
use App\Config\Database;
use PDO;

class ExpeditionController extends Controller
{
    private ExpeditionService $expeditionService;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->expeditionService = new ExpeditionService();
    }

    public function index(): void
    {
        $db = Database::getConnection();

        // Récupérer la liste des produits actifs
        $stmtProduit = $db->prepare("SELECT id_produit, designation AS nom_produit, unite FROM produit WHERE statut = 'Actif' ORDER BY designation ASC");
        $stmtProduit->execute();
        $produits = $stmtProduit->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Récupérer la liste de tous les postes d'achat
        $stmtPostes = $db->prepare("SELECT id_poste, nom_poste FROM poste_achat ORDER BY nom_poste ASC");
        $stmtPostes->execute();
        $postes = $stmtPostes->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Historique des transferts
        $transferts = $this->expeditionService->getHistoriqueTransferts();

        $this->render('expedition/index', [
            'title'      => 'Gestion des Expéditions',
            'produits'   => $produits,
            'postes'     => $postes,
            'transferts' => $transferts
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
            return;
        }

        $response = $this->expeditionService->processTransfert($_POST);
        $this->json($response);
    }
}