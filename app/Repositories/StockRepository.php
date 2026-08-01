<?php

namespace App\Repositories;

use App\Config\Database;
use PDO;
use Exception;

class StockRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getStockByPoste(int $posteId): array
    {
        $sql = "SELECT p.id_produit, p.designation AS nom_produit, p.unite,
                       COALESCE(s.quantite, 0) AS quantite_disponible_kg,
                       s.derniere_mise_a_jour
                FROM produit p
                LEFT JOIN stock s ON p.id_produit = s.id_produit AND s.id_poste = :poste
                WHERE p.statut = 'Actif'
                ORDER BY p.designation ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['poste' => $posteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function ajusterStock(int $posteId, int $produitId, float $nouvelleQuantite): bool
    {
        $sql = "INSERT INTO stock (id_poste, id_produit, quantite, derniere_mise_a_jour)
                VALUES (:poste, :produit, :qte, NOW())
                ON DUPLICATE KEY UPDATE 
                    quantite = :qte_update, 
                    derniere_mise_a_jour = NOW()";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'poste'      => $posteId,
            'produit'    => $produitId,
            'qte'        => $nouvelleQuantite,
            'qte_update' => $nouvelleQuantite
        ]);
    }

    /**
     * Ajout sécurisé pour alimenter les 10 rapports clés sans altérer le reste
     */
    public function getStockActuelMagasin(int $posteId): array
    {
        try {
            $sql = "SELECT p.id_produit, p.designation AS nom_produit,
                           COALESCE(s.quantite, 0) as quantite_disponible_kg
                    FROM produit p
                    LEFT JOIN stock s ON p.id_produit = s.id_produit AND s.id_poste = :poste
                    WHERE p.statut = 'Actif'";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['poste' => $posteId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            return $this->getStockByPoste($posteId);
        }
    }
}