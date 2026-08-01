<?php

namespace App\Models;

class Achat
{
    public int $id_achat;
    public string $code_achat;
    public string $date_achat;
    public int $id_fournisseur;
    public int $id_produit;
    public int $id_poste;
    public float $poids_brut;
    public float $tare;
    public float $poids_net;
    public float $prix_unitaire;
    public float $montant_total;
    public float $montant_avance_deduit;
    public float $montant_paye_cash;
    public string $qualite;
    public int $id_utilisateur;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->id_achat              = (int)($data['id_achat'] ?? 0);
            $this->code_achat           = $data['code_achat'] ?? '';
            $this->date_achat           = $data['date_achat'] ?? date('Y-m-d H:i:s');
            $this->id_fournisseur       = (int)($data['id_fournisseur'] ?? 0);
            $this->id_produit           = (int)($data['id_produit'] ?? 0);
            $this->id_poste             = (int)($data['id_poste'] ?? 0);
            $this->poids_brut           = (float)($data['poids_brut'] ?? 0.0);
            $this->tare                 = (float)($data['tare'] ?? 0.0);
            $this->poids_net            = (float)($data['poids_net'] ?? 0.0);
            $this->prix_unitaire        = (float)($data['prix_unitaire'] ?? 0.0);
            $this->montant_total        = (float)($data['montant_total'] ?? 0.0);
            $this->montant_avance_deduit= (float)($data['montant_avance_deduit'] ?? 0.0);
            $this->montant_paye_cash    = (float)($data['montant_paye_cash'] ?? 0.0);
            $this->qualite              = $data['qualite'] ?? 'Bonne';
            $this->id_utilisateur       = (int)($data['id_utilisateur'] ?? 0);
        }
    }
}