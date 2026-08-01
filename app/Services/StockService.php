<?php

namespace App\Services;

use App\Repositories\StockRepository;
use App\Core\Session;

class StockService
{
    private StockRepository $stockRepository;

    public function __construct()
    {
        $this->stockRepository = new StockRepository();
    }

    /**
     * Récupère l'état du stock actuel pour le poste d'achat de l'utilisateur connecté.
     */
    public function getStockActuel(): array
    {
        $posteId = (int)Session::get('user_poste_id');
        
        if ($posteId <= 0) {
            return [];
        }

        return $this->stockRepository->getStockByPoste($posteId);
    }

    /**
     * Traite le réajustement manuel du stock d'un produit.
     */
    public function processAjustement(array $postData): array
    {
        $produitId        = (int)($postData['id_produit'] ?? 0);
        $nouvelleQuantite = (float)($postData['quantite_disponible_kg'] ?? 0);
        $posteId          = (int)Session::get('user_poste_id');

        // Vérification de la session utilisateur
        if ($posteId <= 0) {
            return [
                'success' => false, 
                'message' => 'Session expirée ou poste non identifié.'
            ];
        }

        // Validation des entrées du formulaire
        if ($produitId <= 0 || $nouvelleQuantite < 0) {
            return [
                'success' => false, 
                'message' => 'Veuillez sélectionner un produit valide et saisir une quantité positive ou nulle.'
            ];
        }

        // Exécution de l'ajustement via le Repository
        if ($this->stockRepository->ajusterStock($posteId, $produitId, $nouvelleQuantite)) {
            return [
                'success' => true, 
                'message' => 'Stock réajusté avec succès !'
            ];
        }

        return [
            'success' => false, 
            'message' => 'Erreur système lors de la mise à jour du stock.'
        ];
    }
}