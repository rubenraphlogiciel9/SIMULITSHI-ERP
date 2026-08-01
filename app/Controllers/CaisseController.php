<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Services\CaisseService;

class CaisseController extends Controller
{
    private CaisseService $caisseService;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->caisseService = new CaisseService();
    }

    public function index(): void
    {
        $solde      = $this->caisseService->getSolde();
        $mouvements = $this->caisseService->getHistorique();

        $this->render('caisse/index', [
            'title'      => 'Gestion de la Caisse',
            'solde'      => $solde,
            'mouvements' => $mouvements
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
        }

        $response = $this->caisseService->processMouvement($_POST);
        $this->json($response);
    }
}