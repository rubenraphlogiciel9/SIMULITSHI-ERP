<?php

namespace App\Services;

use App\Repositories\ExpeditionRepository;
use App\Repositories\StockRepository;
use App\Core\Session;
use App\Config\Database;
use PDO;

class ExpeditionService
{
    private ExpeditionRepository $expeditionRepository;
    private StockRepository $stockRepository;

    public function __construct()
    {
        $this->expeditionRepository = new ExpeditionRepository();
        $this->stockRepository = new StockRepository();
    }

    public function getHistoriqueTransferts(): array
    {
        return $this->expeditionRepository->getAllTransferts();
    }

    public function processTransfert(array $postData): array
    {
        $produitId   = (int)($postData['id_produit'] ?? 0);
        $quantite    = (float)($postData['quantite'] ?? 0);
        $posteDest   = (int)($postData['poste_destination'] ?? 0);
        $posteSource = (int)Session::get('user_poste_id');
        $userId      = (int)Session::get('user_id');

        if ($posteSource <= 0) {
            return ['success' => false, 'message' => 'Poste source non identifié dans la session.'];
        }

        if ($produitId <= 0 || $quantite <= 0 || $posteDest <= 0) {
            return ['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires avec des valeurs valides.'];
        }

        if ($posteSource === $posteDest) {
            return ['success' => false, 'message' => 'Le poste de destination ne peut pas être le même que le poste source.'];
        }

        // Vérifier si le poste source a assez de stock
        $stocksSource = $this->stockRepository->getStockByPoste($posteSource);
        $stockDisponible = 0;
        foreach ($stocksSource as $s) {
            if ((int)$s['id_produit'] === $produitId) {
                $stockDisponible = (float)$s['quantite_disponible_kg'];
                break;
            }
        }

        if ($stockDisponible < $quantite) {
            return ['success' => false, 'message' => "Stock insuffisant dans votre poste. Stock disponible : {$stockDisponible}"];
        }

        // Utilisation d'une transaction pour sécuriser le transfert
        $db = Database::getConnection();
        try {
            $db->beginTransaction();

            // 1. Enregistrer l'expédition
            $this->expeditionRepository->createTransfert([
                'quantite'          => $quantite,
                'id_produit'        => $produitId,
                'poste_source'      => $posteSource,
                'poste_destination' => $posteDest,
                'id_utilisateur'    => $userId
            ]);

            // 2. Déduire du stock source
            $nouveauStockSource = $stockDisponible - $quantite;
            $this->stockRepository->ajusterStock($posteSource, $produitId, $nouveauStockSource);

            // 3. Ajouter au stock destination (récupérer son stock actuel d'abord)
            $stmtDest = $db->prepare("SELECT quantite FROM stock WHERE id_poste = ? AND id_produit = ?");
            $stmtDest->execute([$posteDest, $produitId]);
            $currentDestStock = $stmtDest->fetchColumn();
            $nouveauStockDest = ($currentDestStock !== false ? (float)$currentDestStock : 0) + $quantite;

            $this->stockRepository->ajusterStock($posteDest, $produitId, $nouveauStockDest);

            $db->commit();
            return ['success' => true, 'message' => 'Expédition / Transfert effectué avec succès !'];

        } catch (\Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Erreur lors de la transaction : ' . $e->getMessage()];
        }
    }
}