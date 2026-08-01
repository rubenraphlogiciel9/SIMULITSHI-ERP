<?php

namespace App\Models;

class Fournisseur
{
    public int $id_fournisseur;
    public string $nom;
    public string $prenom;
    public string $telephone;
    public string $adresse;
    public string $statut;
    public string $date_creation;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->id_fournisseur = (int)($data['id_fournisseur'] ?? 0);
            $this->nom            = $data['nom'] ?? '';
            $this->prenom         = $data['prenom'] ?? '';
            $this->telephone      = $data['telephone'] ?? '';
            $this->adresse        = $data['adresse'] ?? '';
            $this->statut         = $data['statut'] ?? 'Actif';
            $this->date_creation  = $data['date_creation'] ?? date('Y-m-d H:i:s');
        }
    }
}