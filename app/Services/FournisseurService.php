<?php

namespace App\Services;

use App\Repositories\FournisseurRepository;
use App\Repositories\CaisseRepository;
use App\Config\Database;
use App\Core\Session;
use PDO;

class FournisseurService
{
    private FournisseurRepository $fournisseurRepository;
    private CaisseRepository $caisseRepository;
    private PDO $db;

    public function __construct()
    {
        $this->fournisseurRepository = new FournisseurRepository();
        $this->caisseRepository = new CaisseRepository();
        $this->db = Database::getConnection();
    }

    public function getListeFournisseurs(): array
    {
        return $this->fournisseurRepository->getAllWithAvances();
    }

    public function getHistoriqueAvances(): array
    {
        $posteId = (int)Session::get('user_poste_id');
        return $this->fournisseurRepository->getHistoriqueAvancesByPoste($posteId);
    }

    public function saveFournisseur(array $postData): array
    {
        $nom       = trim($postData['nom'] ?? '');
        $prenom    = trim($postData['prenom'] ?? '');
        $telephone = trim($postData['telephone'] ?? '');
        $adresse   = trim($postData['adresse'] ?? '');

        if (empty($nom)) {
            return ['success' => false, 'message' => 'Le nom du fournisseur est obligatoire.'];
        }

        $data = [
            'nom'       => $nom,
            'prenom'    => $prenom,
            'telephone' => $telephone,
            'adresse'   => $adresse,
            'statut'    => 'Actif'
        ];

        if ($this->fournisseurRepository->createFournisseur($data)) {
            return ['success' => true, 'message' => 'Fournisseur enregistré avec succès.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de l’enregistrement du fournisseur.'];
    }

    public function processAvance(array $postData): array
    {
        $fournisseurId = (int)($postData['id_fournisseur'] ?? 0);
        $montant       = (float)($postData['montant_avance'] ?? 0);
        $observation   = trim($postData['observation'] ?? '');
        $posteId       = (int)Session::get('user_poste_id');
        $userId        = (int)Session::get('user_id');

        if ($fournisseurId <= 0 || $montant <= 0) {
            return ['success' => false, 'message' => 'Veuillez sélectionner un fournisseur et un montant valide.'];
        }

        // Vérification du solde en caisse calculé dynamiquement
        $soldeCaisse = $this->caisseRepository->getSoldeByPoste($posteId);

        if ($soldeCaisse < $montant) {
            return [
                'success' => false,
                'message' => "Solde en caisse insuffisant ($" . number_format($soldeCaisse, 2) . ") pour accorder cette avance."
            ];
        }

        $data = [
            'id_fournisseur' => $fournisseurId,
            'id_poste'       => $posteId,
            'montant_avance' => $montant,
            'observation'   => $observation,
            'id_utilisateur' => $userId
        ];

        if ($this->fournisseurRepository->createAvanceTransaction($data)) {
            return [
                'success' => true,
                'message' => "Avance de $" . number_format($montant, 2) . " octroyée avec succès !"
            ];
        }

        return ['success' => false, 'message' => "Une erreur système s'est produite lors de l'enregistrement."];
    }
}