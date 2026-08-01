<?php

namespace App\Repositories;

use App\Config\Database;
use PDO;
use Exception;

class AchatRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllByPoste(int $posteId, int $limit = 50): array
    {
        $sql = "SELECT a.*, 
                       CONCAT(pr.nom, ' ', COALESCE(pr.prenom, '')) AS producteur_nom,
                       pr.telephone AS producteur_phone,
                       p.designation AS nom_produit,
                       u.username
                FROM achat a
                INNER JOIN fournisseur pr ON a.id_fournisseur = pr.id_fournisseur
                INNER JOIN produit p ON a.id_produit = p.id_produit
                INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
                WHERE a.id_poste = :poste
                ORDER BY a.date_achat DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':poste', $posteId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function createAchatTransaction(array $data): bool
    {
        try {
            $this->db->beginTransaction();

            // 1. Génération du Numéro d'Achat Unique
            $numeroAchat = 'ACH-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            // 1.5 Gestion automatique de l'id_prix pour contourner la contrainte FOREIGN KEY
            $idPrix = $data['id_prix'] ?? null;
            if (!$idPrix) {
                $stmtPrix = $this->db->query("SELECT id_prix FROM prix_jour LIMIT 1");
                $idPrix = $stmtPrix->fetchColumn();

                if (!$idPrix) {
                    $stmtInsPrix = $this->db->prepare("INSERT INTO prix_jour (date_application, prix_kg, id_produit, id_utilisateur) VALUES (NOW(), :prix, :produit, :user)");
                    $stmtInsPrix->execute([
                        'prix'    => $data['prix_kg'] ?? 0,
                        'produit' => $data['id_produit'],
                        'user'    => $data['id_utilisateur']
                    ]);
                    $idPrix = $this->db->lastInsertId();
                }
            }

            // Récupération sécurisée du montant total de l'achat
            $montantTotal = (float)($data['montant'] ?? ($data['poids_net'] * $data['prix_kg']));
            $producteurId = (int)($data['id_producteur'] ?? 0);
            $modePaiement = $data['mode_paiement'] ?? 'Cash';

            // --- CORRECTION RADICALE DU CASH ET DE L'AVANCE ---
            $montantCash = 0.00;
            $montantAvanceDeduit = 0.00;

            if ($modePaiement === 'Avance') {
                // Si tout est payé par avance déduite
                $montantAvanceDeduit = $montantTotal;
                $montantCash = 0.00;
            } elseif ($modePaiement === 'Mixte') {
                // Si l'utilisateur a spécifié une partie en avance et le reste en cash dans le formulaire
                $montantAvanceDeduit = (float)($data['montant_avance'] ?? $data['montant_avance_deduit'] ?? 0);
                $montantCash = max(0, $montantTotal - $montantAvanceDeduit);
            } else {
                // Par défaut ou 'Cash' : tout va dans le cash
                $montantCash = $montantTotal;
                $montantAvanceDeduit = 0.00;
            }

            // 2. Insertion dans la table `achat` avec les montants explicites
            $sqlAchat = "INSERT INTO achat (
                            numero_achat, date_achat, id_fournisseur, id_produit, id_poste, id_prix, 
                            poids_brut, nombre_sacs, tare, taux_humidite, refaction, poids_net, 
                            prix_kg, montant, mode_paiement, qualite, montant_cash, montant_avance, id_utilisateur
                        ) VALUES (
                            :numero_achat, NOW(), :id_fournisseur, :id_produit, :id_poste, :id_prix, 
                            :poids_brut, :nombre_sacs, :tare, :taux_humidite, :refaction, :poids_net, 
                            :prix_kg, :montant, :mode_paiement, :qualite, :montant_cash, :montant_avance, :id_utilisateur
                        )";
            
            $stmtAchat = $this->db->prepare($sqlAchat);
            $stmtAchat->execute([
                'numero_achat'   => $numeroAchat,
                'id_fournisseur' => $producteurId, 
                'id_produit'     => $data['id_produit'],
                'id_poste'       => $data['id_poste'],
                'id_prix'        => $idPrix,
                'poids_brut'     => $data['poids_brut'] ?? 0.00,
                'nombre_sacs'    => $data['nombre_sacs'] ?? 0,
                'tare'           => $data['tare'] ?? 0.00,
                'taux_humidite'  => $data['taux_humidite'] ?? 0.00,
                'refaction'      => $data['refaction'] ?? 0.00,
                'poids_net'      => $data['poids_net'],
                'prix_kg'        => $data['prix_kg'],
                'montant'        => $montantTotal,
                'mode_paiement'  => $modePaiement,
                'qualite'        => $data['qualite'] ?? 'Bonne',
                'montant_cash'   => $montantCash,
                'montant_avance' => $montantAvanceDeduit,
                'id_utilisateur' => $data['id_utilisateur']
            ]);

            $idAchat = $this->db->lastInsertId();

            // 3. Mise à jour du Stock (`stock`)
            $sqlStock = "INSERT INTO stock (id_poste, id_produit, quantite)
                         VALUES (:poste, :produit, :poids_net)
                         ON DUPLICATE KEY UPDATE quantite = quantite + :poids_net_update";
            
            $stmtStock = $this->db->prepare($sqlStock);
            $stmtStock->execute([
                'poste'            => $data['id_poste'],
                'produit'          => $data['id_produit'],
                'poids_net'        => $data['poids_net'],
                'poids_net_update' => $data['poids_net']
            ]);

            // 4. Mouvement de stock (`mouvement_stock`)
            $sqlMvtStock = "INSERT INTO mouvement_stock (date_mouvement, type_mouvement, quantite, motif, id_produit, id_poste, id_achat, id_utilisateur)
                            VALUES (NOW(), 'ENTREE', :quantite, :motif, :produit, :poste, :achat, :user)";
            $stmtMvtStock = $this->db->prepare($sqlMvtStock);
            $stmtMvtStock->execute([
                'quantite' => $data['poids_net'],
                'motif'    => "Achat Cacao/Café [N°: {$numeroAchat}]",
                'produit'  => $data['id_produit'],
                'poste'    => $data['id_poste'],
                'achat'    => $idAchat,
                'user'     => $data['id_utilisateur']
            ]);

            // 5. Sortie de caisse UNIQUEMENT si du cash est impliqué
            if ($montantCash > 0) {
                $sqlMvtCaisse = "INSERT INTO caisse (type_operation, montant, libelle, id_poste, id_utilisateur, date_operation)
                                 VALUES ('SORTIE', :montant, :libelle, :poste, :user, NOW())";
                $stmtMvtCaisse = $this->db->prepare($sqlMvtCaisse);
                $stmtMvtCaisse->execute([
                    'montant' => $montantCash,
                    'libelle' => "Paiement achat N° {$numeroAchat}",
                    'poste'   => $data['id_poste'],
                    'user'    => $data['id_utilisateur']
                ]);
            }

            // 6. Déduction progressive sur les avances fournisseurs (FIFO)
            if ($montantAvanceDeduit > 0) {
                $sqlGetAvances = "SELECT id_avance, solde_restant 
                                  FROM avance_fournisseur 
                                  WHERE id_fournisseur = :f AND statut = 'En_cours' 
                                  ORDER BY date_avance ASC";
                $stmtGetAvances = $this->db->prepare($sqlGetAvances);
                $stmtGetAvances->execute(['f' => $producteurId]);
                $avances = $stmtGetAvances->fetchAll(PDO::FETCH_ASSOC);

                $resteADeduire = $montantAvanceDeduit;

                foreach ($avances as $av) {
                    if ($resteADeduire <= 0) break;

                    $solde  = (float)$av['solde_restant'];
                    $deduit = min($solde, $resteADeduire);
                    $nouveauSolde = $solde - $deduit;
                    $nouveauStatut = ($nouveauSolde <= 0) ? 'Solde' : 'En_cours';

                    $sqlUpdAv = "UPDATE avance_fournisseur 
                                 SET solde_restant = :solde, statut = :statut 
                                 WHERE id_avance = :id";
                    $stmtUpdAv = $this->db->prepare($sqlUpdAv);
                    $stmtUpdAv->execute([
                        'solde'  => $nouveauSolde,
                        'statut' => $nouveauStatut,
                        'id'     => $av['id_avance']
                    ]);

                    $resteADeduire -= $deduit;
                }
            }

            $this->db->commit();
            return true;

       } catch (Exception $e) {
            $this->db->rollBack();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => "SQL Error: " . $e->getMessage()]);
            exit;
        }
    }
}