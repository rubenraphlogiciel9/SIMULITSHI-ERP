<?php

namespace App\Models;

class Stock
{
    public int $id_stock;
    public int $id_poste;
    public int $id_produit;
    public float $quantite_disponible_kg;
    public string $derniere_mise_a_jour;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->id_stock               = (int)($data['id_stock'] ?? 0);
            $this->id_poste               = (int)($data['id_poste'] ?? 0);
            $this->id_produit             = (int)($data['id_produit'] ?? 0);
            $this->quantite_disponible_kg = (float)($data['quantite_disponible_kg'] ?? 0.0);
            $this->derniere_mise_a_jour   = $data['derniere_mise_a_jour'] ?? date('Y-m-d H:i:s');
        }
    }
}