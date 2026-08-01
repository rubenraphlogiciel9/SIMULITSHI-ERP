<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Services\RapportService;

class RapportController extends Controller
{
    private RapportService $rapportService;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->rapportService = new RapportService();
    }

    public function index(): void
    {
        // Dates par défaut : Aujourd'hui
        $dateDebut = $_GET['date_debut'] ?? date('Y-m-d');
        $dateFin   = $_GET['date_fin']   ?? date('Y-m-d');

        $rapport = $this->rapportService->getRapportComplet($dateDebut, $dateFin);

        $this->render('rapports/index', [
            'title'   => 'Rapports & Clôture d\'Activité',
            'rapport' => $rapport
        ]);
    }
}