<?php

namespace App\Models;

class User
{
    public int $id_utilisateur;
    public string $nom;
    public ?string $postnom;
    public ?string $prenom;
    public string $username;
    public string $mot_passe;
    public string $statut;
    public int $id_role;
    public int $id_poste;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->id_utilisateur = (int)($data['id_utilisateur'] ?? 0);
            $this->nom            = $data['nom'] ?? '';
            $this->postnom        = $data['postnom'] ?? null;
            $this->prenom         = $data['prenom'] ?? null;
            $this->username       = $data['username'] ?? '';
            $this->mot_passe      = $data['mot_passe'] ?? '';
            $this->statut         = $data['statut'] ?? 'Actif';
            $this->id_role        = (int)($data['id_role'] ?? 0);
            $this->id_poste       = (int)($data['id_poste'] ?? 0);
        }
    }

    public function getNomComplet(): string
    {
        return trim("{$this->prenom} {$this->nom} {$this->postnom}");
    }
}