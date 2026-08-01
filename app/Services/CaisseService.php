<?php

namespace App\Services;

use App\Repositories\CaisseRepository;
use App\Core\Session;

class CaisseService
{
    private CaisseRepository $caisseRepository;

    public function __construct()
    {
        $this->caisseRepository = new CaisseRepository();
    }

    public function getSolde(): float
    {
        $posteId = (int)Session::get('user_poste_id');
        if ($posteId <= 0) {
            return 0.0;
        }
        return $this->caisseRepository->getSoldeByPoste($posteId);
    }

    public function getHistorique(): array
    {
        $posteId = (int)Session::get('user_poste_id');
        if ($posteId <= 0) {
            return [];
        }
        return $this->caisseRepository->getMouvementsByPoste($posteId);
    }

    public function processMouvement(array $postData): array
    {
        $type    = trim($postData['type_mouvement'] ?? $postData['type_operation'] ?? '');
        $montant = (float)($postData['montant'] ?? 0);
        $libelle = trim($postData['libelle'] ?? '');
        $posteId = (int)Session::get('user_poste_id');
        $userId  = (int)Session::get('user_id');

        if ($posteId <= 0 || $userId <= 0) {
            return ['success' => false, 'message' => 'Session expirée ou utilisateur/poste non défini.'];
        }

        if (!in_array($type, ['Entree', 'Sortie']) || $montant <= 0 || empty($libelle)) {
            return ['success' => false, 'message' => 'Informations invalides ou incomplètes.'];
        }

        // Contrôle de solde en cas de sortie
        if ($type === 'Sortie') {
            $soldeActuel = $this->getSolde();
            if ($soldeActuel < $montant) {
                return [
                    'success' => false,
                    'message' => "Solde insuffisant en caisse ($" . number_format($soldeActuel, 2) . ") pour effectuer cette sortie."
                ];
            }
        }

        $data = [
            'id_poste'           => $posteId,
            'type_mouvement'     => $type,
            'montant'            => $montant,
            'libelle'            => $libelle,
            'piece_justificative'=> $postData['piece_justificative'] ?? null,
            'id_utilisateur'     => $userId
        ];

        if ($this->caisseRepository->addMouvementTransaction($data)) {
            return ['success' => true, 'message' => "Mouvement de caisse enregistré avec succès !"];
        }

        return ['success' => false, 'message' => "Erreur système lors de l'enregistrement du mouvement."];
    }
}