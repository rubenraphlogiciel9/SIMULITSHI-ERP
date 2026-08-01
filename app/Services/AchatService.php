<?php

namespace App\Services;

use App\Repositories\AchatRepository;
use App\Config\Database;
use App\Core\Session;
use PDO;

class AchatService
{
    private AchatRepository $achatRepository;
    private PDO $db;

    public function __construct()
    {
        $this->achatRepository = new AchatRepository();
        $this->db = Database::getConnection();
    }

    public function getListeAchats(): array
    {
        $posteId = (int)Session::get('user_poste_id');
        return $this->achatRepository->getAllByPoste($posteId);
    }

    public function processAchat(array $postData): array
    {
        $producteurId = (int)($postData['id_fournisseur'] ?? $postData['id_producteur'] ?? 0);
        $produitId    = (int)($postData['id_produit'] ?? 0);
        $poidsBrut    = (float)($postData['poids_brut'] ?? 0);
        $tare         = (float)($postData['tare'] ?? 0);
        
        // CORRECTION : Récupérer 'prix_kg' (envoyé par le formulaire) avec 'prix_unitaire' en secours
        $prixUnitaire = (float)($postData['prix_kg'] ?? $postData['prix_unitaire'] ?? 0);
        
        $qualite      = trim($postData['qualite'] ?? 'Bonne');
        $posteId      = (int)Session::get('user_poste_id');
        $userId       = (int)Session::get('user_id');

        // Validation des données de base
        if ($producteurId <= 0 || $produitId <= 0 || $poidsBrut <= 0 || $prixUnitaire <= 0) {
            return ['success' => false, 'message' => 'Veuillez remplir correctement tous les champs obligatoires.'];
        }

        $poidsNet = $poidsBrut - $tare;
        if ($poidsNet <= 0) {
            return ['success' => false, 'message' => 'Le poids net doit être supérieur à zéro.'];
        }

        $montantTotal = $poidsNet * $prixUnitaire;

        // Récupérer le solde restant des avances en cours pour ce producteur/fournisseur
        $stmtAvance = $this->db->prepare("SELECT COALESCE(SUM(solde_restant), 0) FROM avance_fournisseur WHERE id_fournisseur = :f AND statut = 'En_cours'");
        $stmtAvance->execute(['f' => $producteurId]);
        $avanceEnCours = (float)$stmtAvance->fetchColumn();

        // Calcul du montant à déduire de l'avance et du montant cash
        $montantAvanceDeduit = 0.00;
        if ($avanceEnCours > 0) {
            $montantAvanceDeduit = min($montantTotal, $avanceEnCours);
        }

        $montantPayeCash = $montantTotal - $montantAvanceDeduit;

        // Vérification de la disponibilité du solde en caisse (via le calcul dynamique entrées - sorties)
        if ($montantPayeCash > 0) {
            $stmtCaisse = $this->db->prepare("
                SELECT COALESCE(
                    SUM(
                        CASE 
                            WHEN UPPER(TRIM(type_operation)) = 'ENTREE' THEN montant 
                            ELSE -montant 
                        END
                    ), 0
                ) 
                FROM caisse 
                WHERE id_poste = :poste
            ");
            $stmtCaisse->execute(['poste' => $posteId]);
            $soldeCaisse = (float)$stmtCaisse->fetchColumn();

            if ($soldeCaisse < $montantPayeCash) {
                return [
                    'success' => false,
                    'message' => "Solde en caisse insuffisant ($" . number_format($soldeCaisse, 2) . "). Montant cash requis : $" . number_format($montantPayeCash, 2)
                ];
            }
        }

        // Préparation des données harmonisées avec le Repository
        $data = [
            'id_producteur'         => $producteurId,
            'id_produit'            => $produitId,
            'id_poste'              => $posteId,
            'poids_brut'            => $poidsBrut,
            'tare'                  => $tare,
            'poids_net'             => $poidsNet,
            'prix_kg'               => $prixUnitaire,
            'montant'               => $montantTotal,
            'montant_avance_deduit' => $montantAvanceDeduit,
            'montant_paye_cash'     => $montantPayeCash,
            'qualite'               => $qualite,
            'mode_paiement'         => ($montantPayeCash > 0) ? 'Cash' : 'Avance',
            'id_utilisateur'        => $userId
        ];

        $result = $this->achatRepository->createAchatTransaction($data);

        if ($result) {
            return [
                'success' => true,
                'message' => "Achat enregistré avec succès ! Total: $" . number_format($montantTotal, 2) . 
                           " (Cash: $" . number_format($montantPayeCash, 2) . 
                           ", Avance déduite: $" . number_format($montantAvanceDeduit, 2) . ")"
            ];
        }

        return ['success' => false, 'message' => "Une erreur système s'est produite lors de l'enregistrement."];
    }
}