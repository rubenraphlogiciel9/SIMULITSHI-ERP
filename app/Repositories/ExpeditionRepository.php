<?php

namespace App\Repositories;

use App\Config\Database;
use PDO;

class ExpeditionRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllTransferts(): array
    {
        $sql = "SELECT t.*, p.designation AS nom_produit, p.unite,
                       ps.nom_poste AS nom_poste_source, 
                       pd.nom_poste AS nom_poste_destination,
                       u.nom AS nom_utilisateur
                FROM transfert_stock t
                INNER JOIN produit p ON t.id_produit = p.id_produit
                INNER JOIN poste_achat ps ON t.poste_source = ps.id_poste
                INNER JOIN poste_achat pd ON t.poste_destination = pd.id_poste
                INNER JOIN utilisateur u ON t.id_utilisateur = u.id_utilisateur
                ORDER BY t.date_transfert DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function createTransfert(array $data): bool
    {
        $sql = "INSERT INTO transfert_stock (date_transfert, quantite, id_produit, poste_source, poste_destination, id_utilisateur)
                VALUES (NOW(), :quantite, :id_produit, :poste_source, :poste_destination, :id_utilisateur)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'quantite'          => $data['quantite'],
            'id_produit'        => $data['id_produit'],
            'poste_source'      => $data['poste_source'],
            'poste_destination' => $data['poste_destination'],
            'id_utilisateur'    => $data['id_utilisateur']
        ]);
    }
}