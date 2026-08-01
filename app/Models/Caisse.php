<?php

namespace App\Models;

class Caisse
{
    public int $id_caisse;
    public int $id_poste;
    public float $solde_actuel;
    public string $devise;
    public string $derniere_mise_a_jour;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->id_caisse             = (int)($data['id_caisse'] ?? 0);
            $this->id_poste              = (int)($data['id_poste'] ?? 0);
            $this->solde_actuel          = (float)($data['solde_actuel'] ?? 0.0);
            $this->devise                = $data['devise'] ?? 'USD';
            $this->derniere_mise_a_jour  = $data['derniere_mise_a_jour'] ?? date('Y-m-d H:i:s');
        }
    }
}