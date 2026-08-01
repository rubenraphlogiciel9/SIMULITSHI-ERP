<?php

namespace App\Repositories;

use App\Config\Database;
use PDO;
use Exception;

class CaisseRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Calcule le solde actuel du poste en faisant la somme des 'ENTREE' moins les 'SORTIE'.
     * Insensible à la casse (ENTREE, Entree, entree).
     */
    public function getSoldeByPoste(int $posteId): float
    {
        $sql = "SELECT 
                    SUM(
                        CASE 
                            WHEN UPPER(TRIM(type_operation)) = 'ENTREE' THEN montant 
                            ELSE -montant 
                        END
                    ) AS solde_actuel
                FROM caisse 
                WHERE id_poste = :poste";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['poste' => $posteId]);
        
        return (float)($stmt->fetchColumn() ?: 0.0);
    }

    /**
     * Récupère l'historique des opérations avec le calcul du solde cumulé après chaque opération.
     * Insensible à la casse pour le calcul progressif (solde_apres).
     */
    public function getMouvementsByPoste(int $posteId, int $limit = 50): array
    {
        $sql = "SELECT 
                    c.*, 
                    c.type_operation AS type_mouvement,
                    c.date_operation AS date_mouvement,
                    u.username,
                    SUM(
                        CASE 
                            WHEN UPPER(TRIM(c.type_operation)) = 'ENTREE' THEN c.montant 
                            ELSE -c.montant 
                        END
                    ) OVER (
                        PARTITION BY c.id_poste 
                        ORDER BY c.date_operation ASC, c.id_caisse ASC
                    ) AS solde_apres
                FROM caisse c
                LEFT JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                WHERE c.id_poste = :poste
                ORDER BY c.date_operation DESC, c.id_caisse DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':poste', $posteId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Enregistre un nouveau mouvement de caisse dans la table `caisse`.
     */
    public function addMouvementTransaction(array $data): bool
    {
        try {
            $sql = "INSERT INTO caisse (type_operation, montant, libelle, piece_justificative, id_poste, id_utilisateur, date_operation)
                    VALUES (:type, :montant, :libelle, :piece, :poste, :user, NOW())";

            $stmt = $this->db->prepare($sql);
            
            $piece = !empty($data['piece_justificative']) ? trim($data['piece_justificative']) : null;
            $type  = $data['type_mouvement'] ?? $data['type_operation'] ?? 'ENTREE';

            return $stmt->execute([
                'type'    => trim($type),
                'montant' => (float)($data['montant'] ?? 0),
                'libelle' => trim($data['libelle'] ?? ''),
                'piece'   => $piece,
                'poste'   => (int)($data['id_poste'] ?? 0),
                'user'    => (int)($data['id_utilisateur'] ?? 0)
            ]);

        } catch (Exception $e) {
            error_log("Erreur d'insertion CaisseRepository : " . $e->getMessage());
            return false;
        }
    }
    public function getSoldeActuel(int $posteId): float
    {
        return $this->getSoldeByPoste($posteId);
    }
}