<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Repositories\ProduitRepository;

class ProduitController extends Controller
{
    private ProduitRepository $produitRepository;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->produitRepository = new ProduitRepository();
    }

    public function index(): void
    {
        $produits = $this->produitRepository->getAll();

        $this->render('produits/index', [
            'title'    => 'Gestion des Produits / Spéculations',
            'produits' => $produits
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
        }

        $code = trim($_POST['code_produit'] ?? '');
        $nom  = trim($_POST['nom_produit'] ?? '');

        if (empty($code) || empty($nom)) {
            $this->json(['success' => false, 'message' => 'Le code et le nom du produit sont obligatoires.']);
            return;
        }

        $success = $this->produitRepository->create($_POST);

        if ($success) {
            $this->json(['success' => true, 'message' => 'Produit ajouté avec succès !']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la création du produit (vérifiez si le code existe déjà).']);
        }
    }

    public function toggleStatut(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
        }

        $id     = (int)($_POST['id_produit'] ?? 0);
        $statut = $_POST['statut'] ?? 'Inactif';

        if ($id <= 0) {
            $this->json(['success' => false, 'message' => 'Identifiant de produit invalide.']);
            return;
        }

        $success = $this->produitRepository->updateStatut($id, $statut);
        $this->json(['success' => $success, 'message' => $success ? 'Statut mis à jour.' : 'Erreur de mise à jour.']);
    }
}