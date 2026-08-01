<?php

namespace App\Repositories;

use App\Config\Database;
use PDO;

class ProduitRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM produit ORDER BY id_produit DESC";
        return $this->db->query($sql)->fetchAll() ?: [];
    }

    public function getActiveProducts(): array
    {
        $sql = "SELECT * FROM produit WHERE statut = 'Actif' ORDER BY id_produit DESC";
        return $this->db->query($sql)->fetchAll() ?: [];
    }

    public function create(array $data): bool
    {
        // 1. Récupération dynamique des colonnes existantes dans la table `produit`
        $stmtCols = $this->db->query("DESCRIBE produit");
        $existingColumns = $stmtCols->fetchAll(PDO::FETCH_COLUMN);

        // 2. Préparation des valeurs issues du formulaire
        $codeValue   = strtoupper(trim($data['code_produit'] ?? $data['code'] ?? ''));
        $nomValue    = trim($data['nom_produit'] ?? $data['nom'] ?? $data['designation'] ?? '');
        $uniteValue  = trim($data['unite'] ?? $data['unite_mesure'] ?? '');
        $statutValue = $data['statut'] ?? 'Actif';

        // 3. Mapping dynamique selon la structure réelle de la table dans MySQL
        $fields = [];
        $params = [];

        // Mapping Code
        if (in_array('code_produit', $existingColumns)) {
            $fields['code_produit'] = ':code';
            $params['code'] = $codeValue;
        } elseif (in_array('code', $existingColumns)) {
            $fields['code'] = ':code';
            $params['code'] = $codeValue;
        } elseif (in_array('code_prod', $existingColumns)) {
            $fields['code_prod'] = ':code';
            $params['code'] = $codeValue;
        }

        // Mapping Nom / Désignation
        if (in_array('nom_produit', $existingColumns)) {
            $fields['nom_produit'] = ':nom';
            $params['nom'] = $nomValue;
        } elseif (in_array('nom', $existingColumns)) {
            $fields['nom'] = ':nom';
            $params['nom'] = $nomValue;
        } elseif (in_array('designation', $existingColumns)) {
            $fields['designation'] = ':nom';
            $params['nom'] = $nomValue;
        }

        // Mapping Unité
        if (in_array('unite', $existingColumns)) {
            $fields['unite'] = ':unite';
            $params['unite'] = $uniteValue;
        } elseif (in_array('unite_mesure', $existingColumns)) {
            $fields['unite_mesure'] = ':unite';
            $params['unite'] = $uniteValue;
        }

        // Mapping Statut
        if (in_array('statut', $existingColumns)) {
            $fields['statut'] = ':statut';
            $params['statut'] = $statutValue;
        }

        // 4. Construction et exécution de la requête SQL
        $columnList = implode(', ', array_keys($fields));
        $valueList  = implode(', ', array_values($fields));

        $sql = "INSERT INTO produit ({$columnList}) VALUES ({$valueList})";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateStatut(int $idProduit, string $statut): bool
    {
        $sql = "UPDATE produit SET statut = :statut WHERE id_produit = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['statut' => $statut, 'id' => $idProduit]);
    }
}