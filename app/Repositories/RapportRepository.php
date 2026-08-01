<?php

namespace App\Repositories;

use App\Config\Database;
use PDO;
use Exception;

class RapportRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getSynthesePoste(int $posteId, string $dateDebut, string $dateFin): array
    {
        $achats = ['nb_achats' => 0, 'total_poids_kg' => 0, 'total_montant_achat' => 0, 'total_avances_recup' => 0];
        try {
            $sqlAchats = "SELECT 
                            COUNT(id_achat) as nb_achats,
                            COALESCE(SUM(poids_net), 0) as total_poids_kg,
                            COALESCE(SUM(montant), 0) as total_montant_achat,
                            COALESCE(SUM(montant_avance), 0) as total_avances_recup
                          FROM achat 
                          WHERE id_poste = :poste 
                          AND DATE(date_achat) BETWEEN :debut AND :fin";

            $stmtA = $this->db->prepare($sqlAchats);
            $stmtA->execute(['poste' => $posteId, 'debut' => $dateDebut, 'fin' => $dateFin]);
            $achats = $stmtA->fetch(PDO::FETCH_ASSOC) ?: $achats;
        } catch (Exception $e) {}

        $caisse = ['total_entrees' => 0, 'total_sorties' => 0];
        try {
            $sqlCaisse = "SELECT 
                            COALESCE(SUM(CASE WHEN type_operation = 'ENTREE' THEN montant ELSE 0 END), 0) as total_entrees,
                            COALESCE(SUM(CASE WHEN type_operation = 'SORTIE' THEN montant ELSE 0 END), 0) as total_sorties
                          FROM caisse 
                          WHERE id_poste = :poste 
                          AND DATE(date_operation) BETWEEN :debut AND :fin";

            $stmtC = $this->db->prepare($sqlCaisse);
            $stmtC->execute(['poste' => $posteId, 'debut' => $dateDebut, 'fin' => $dateFin]);
            $caisse = $stmtC->fetch(PDO::FETCH_ASSOC) ?: $caisse;
        } catch (Exception $e) {}

        $avances = ['total_avances_donnees' => 0];
        try {
            $sqlAvances = "SELECT 
                            COALESCE(SUM(montant_avance), 0) as total_avances_donnees
                           FROM avance_fournisseur 
                           WHERE id_poste = :poste 
                           AND DATE(date_avance) BETWEEN :debut AND :fin";

            $stmtAv = $this->db->prepare($sqlAvances);
            $stmtAv->execute(['poste' => $posteId, 'debut' => $dateDebut, 'fin' => $dateFin]);
            $avances = $stmtAv->fetch(PDO::FETCH_ASSOC) ?: $avances;
        } catch (Exception $e) {}

        $expeditions = ['nb_expeditions' => 0, 'total_expedie_kg' => 0];
        try {
            $sqlExpeditions = "SELECT 
                                COUNT(id_transfert) as nb_expeditions,
                                COALESCE(SUM(quantite), 0) as total_expedie_kg
                               FROM transfert_stock 
                               WHERE poste_source = :poste 
                               AND DATE(date_transfert) BETWEEN :debut AND :fin";

            $stmtE = $this->db->prepare($sqlExpeditions);
            $stmtE->execute(['poste' => $posteId, 'debut' => $dateDebut, 'fin' => $dateFin]);
            $expeditions = $stmtE->fetch(PDO::FETCH_ASSOC) ?: $expeditions;
        } catch (Exception $e) {}

        return [
            'achats'      => $achats,
            'caisse'      => $caisse,
            'avances'     => $avances,
            'expeditions' => $expeditions
        ];
    }

    public function getProducteursActivite(string $dateLimite): array
    {
        try {
            $sql = "SELECT f.*, 
                    MAX(a.date_achat) as dernier_achat,
                    CASE WHEN MAX(a.date_achat) >= :date_limite THEN 'ACTIF' ELSE 'INACTIF' END as statut_activite
                    FROM fournisseur f
                    LEFT JOIN achat a ON f.id_fournisseur = a.id_fournisseur
                    GROUP BY f.id_fournisseur";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['date_limite' => $dateLimite]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function getSituationDettesAvances(): array
    {
        try {
            $sql = "SELECT f.id_fournisseur, f.nom, f.prenom, f.telephone,
                    COALESCE(SUM(av.montant_avance), 0) as total_avances,
                    COALESCE(SUM(av.solde_restant), 0) as solde_dette_restant
                    FROM fournisseur f
                    INNER JOIN avance_fournisseur av ON f.id_fournisseur = av.id_fournisseur
                    WHERE av.statut = 'En_cours'
                    GROUP BY f.id_fournisseur";
                    
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

  public function getJournalAchats(string $debut, string $fin): array
    {
        try {
            $sql = "SELECT a.*, 
                           f.nom as fournisseur_nom, 
                           p.designation as nom_produit 
                    FROM achat a
                    LEFT JOIN fournisseur f ON a.id_fournisseur = f.id_fournisseur
                    LEFT JOIN produit p ON a.id_produit = p.id_produit
                    WHERE DATE(a.date_achat) BETWEEN :debut AND :fin
                    ORDER BY a.date_achat DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['debut' => $debut, 'fin' => $fin]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function getJournalAvances(string $debut, string $fin): array
    {
        try {
            $sql = "SELECT av.*, 
                           CONCAT(f.nom, ' ', COALESCE(f.prenom, '')) AS fournisseur_nom 
                    FROM avance_fournisseur av
                    LEFT JOIN fournisseur f ON av.id_fournisseur = f.id_fournisseur
                    WHERE DATE(av.date_avance) BETWEEN :debut AND :fin
                    ORDER BY av.date_avance DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['debut' => $debut, 'fin' => $fin]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function getJournalDepenses(string $debut, string $fin): array
    {
        try {
            $sql = "SELECT * FROM caisse WHERE UPPER(TRIM(type_operation)) = 'SORTIE' AND DATE(date_operation) BETWEEN :debut AND :fin ORDER BY date_operation DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['debut' => $debut, 'fin' => $fin]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            return [];
        }
    }
}