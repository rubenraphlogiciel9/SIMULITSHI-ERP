<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Core\Session;
use App\Config\Database;
use PDO;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Sécuriser l'accès : réorienter vers /login si non authentifié
        AuthMiddleware::handle();
    }

    public function index(): void
    {
        $db = Database::getConnection();
        $posteId = (int) Session::get('user_poste_id');

        // 1. Récupération des stocks (designation au lieu de nom_produit, quantite au lieu de quantite_disponible_kg)
        $stmtStock = $db->prepare("
            SELECT 
                p.designation AS nom_produit, 
                COALESCE(SUM(s.quantite), 0) AS total_kg 
            FROM produit p 
            LEFT JOIN stock s ON p.id_produit = s.id_produit AND s.id_poste = :poste 
            GROUP BY p.id_produit, p.designation
        ");
        $stmtStock->execute(['poste' => $posteId]);
        $stocks = $stmtStock->fetchAll(PDO::FETCH_ASSOC);

        // 2. Calcul du solde actuel de la caisse (Somme ENTREE - Somme SORTIE)
        $stmtCaisse = $db->prepare("
            SELECT 
                COALESCE(SUM(CASE WHEN type_operation = 'ENTREE' THEN montant ELSE -montant END), 0) AS solde
            FROM caisse 
            WHERE id_poste = :poste
        ");
        $stmtCaisse->execute(['poste' => $posteId]);
        $caisse = $stmtCaisse->fetch(PDO::FETCH_ASSOC);

        $soldeCaisse = $caisse ? (float)$caisse['solde'] : 0.00;

        $this->render('dashboard/index', [
            'title'       => 'Tableau de bord',
            'stocks'      => $stocks,
            'soldeCaisse' => $soldeCaisse
        ]);
    }
}