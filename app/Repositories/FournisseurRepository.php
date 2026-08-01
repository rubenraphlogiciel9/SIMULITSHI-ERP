<?php

namespace App\Repositories;

use App\Config\Database;
use App\Repositories\CaisseRepository;
use PDO;
use Exception;

class FournisseurRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Récupère tous les fournisseurs avec le solde cumulé de leurs avances en cours
     */
    public function getAllWithAvances(): array
    {
        $sql = "SELECT f.*, 
                       COALESCE(SUM(CASE WHEN af.statut = 'En_cours' THEN af.solde_restant ELSE 0 END), 0) AS total_avance_encours
                FROM fournisseur f
                LEFT JOIN avance_fournisseur af ON f.id_fournisseur = af.id_fournisseur
                GROUP BY f.id_fournisseur
                ORDER BY f.nom ASC";

        return $this->db->query($sql)->fetchAll() ?: [];
    }

    /**
     * Ajoute un nouveau fournisseur
     */
    public function createFournisseur(array $data): bool
    {
        $sql = "INSERT INTO fournisseur (nom, prenom, telephone, adresse, statut) 
                VALUES (:nom, :prenom, :telephone, :adresse, :statut)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nom'       => $data['nom'],
            'prenom'    => $data['prenom'],
            'telephone' => $data['telephone'],
            'adresse'   => $data['adresse'],
            'statut'    => $data['statut'] ?? 'Actif'
        ]);
    }

    /**
     * Enregistre l'octroi d'une avance (avec enregistrement du mouvement de caisse)
     */
  public function createAvanceTransaction(array $data): bool
{
    try {
        $this->db->beginTransaction();

        // 1. Insertion dans la table avance_fournisseur
        $sqlAvance = "INSERT INTO avance_fournisseur (id_fournisseur, id_poste, montant_avance, solde_restant, observation, statut, id_utilisateur)
                      VALUES (:fournisseur, :poste, :montant, :solde, :obs, 'En_cours', :user)";
        
        $stmtAvance = $this->db->prepare($sqlAvance);
        $stmtAvance->execute([
            'fournisseur' => $data['id_fournisseur'],
            'poste'       => $data['id_poste'],
            'montant'     => $data['montant_avance'],
            'solde'       => $data['montant_avance'],
            'obs'         => $data['observation'],
            'user'        => $data['id_utilisateur']
        ]);

        // 2. Diminuer la caisse en insérant une SORTIE dans la table `caisse`
        // C'est cette ligne qui fait diminuer le solde calculé par CaisseRepository !
        $sqlCaisse = "INSERT INTO caisse (type_operation, montant, libelle, id_poste, id_utilisateur, date_operation)
                      VALUES ('SORTIE', :montant, :libelle, :poste, :user, NOW())";
                      
        $stmtCaisse = $this->db->prepare($sqlCaisse);
        $stmtCaisse->execute([
            'montant' => $data['montant_avance'],
            'libelle' => "Avance octroyée au fournisseur ID #" . $data['id_fournisseur'],
            'poste'   => $data['id_poste'],
            'user'    => $data['id_utilisateur']
        ]);

        $this->db->commit();
        return true;

    } catch (Exception $e) {
        if ($this->db->inTransaction()) {
            $this->db->rollBack();
        }
        error_log("Erreur AvanceTransaction : " . $e->getMessage());
        return false;
    }
}

    /**
     * Historique des avances
     */
    public function getHistoriqueAvancesByPoste(int $posteId): array
    {
        $sql = "SELECT af.*, 
                       CONCAT(f.nom, ' ', COALESCE(f.prenom, '')) AS fournisseur_nom,
                       f.telephone,
                       u.username
                FROM avance_fournisseur af
                INNER JOIN fournisseur f ON af.id_fournisseur = f.id_fournisseur
                INNER JOIN utilisateur u ON af.id_utilisateur = u.id_utilisateur
                WHERE af.id_poste = :poste
                ORDER BY af.date_avance DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['poste' => $posteId]);
        return $stmt->fetchAll() ?: [];
    }
    
}