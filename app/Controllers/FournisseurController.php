<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Services\FournisseurService;

class FournisseurController extends Controller
{
    private FournisseurService $fournisseurService;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->fournisseurService = new FournisseurService();
    }

    public function index(): void
    {
        $fournisseurs = $this->fournisseurService->getListeFournisseurs();
        $avances      = $this->fournisseurService->getHistoriqueAvances();

        $this->render('fournisseurs/index', [
            'title'        => 'Fournisseurs & Avances',
            'fournisseurs' => $fournisseurs,
            'avances'      => $avances
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
        }

        $response = $this->fournisseurService->saveFournisseur($_POST);
        $this->json($response);
    }

    public function storeAvance(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
        }

        $response = $this->fournisseurService->processAvance($_POST);
        $this->json($response);
    }
}