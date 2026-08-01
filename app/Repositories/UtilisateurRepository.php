<?php

namespace App\Repositories;

use App\Config\Database;
use PDO;

class UtilisateurRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Recherche un utilisateur par son username
     */
    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT u.*, r.libelle AS role_nom, p.nom_poste 
                FROM utilisateur u
                INNER JOIN role r ON u.id_role = r.id_role
                INNER JOIN poste_achat p ON u.id_poste = p.id_poste
                WHERE u.username = :username 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Récupère tous les utilisateurs avec leurs rôles et postes
     */
    public function getAll(): array
    {
        $sql = "SELECT u.*, r.libelle AS role_nom, p.nom_poste 
                FROM utilisateur u
                LEFT JOIN role r ON u.id_role = r.id_role
                LEFT JOIN poste_achat p ON u.id_poste = p.id_poste
                ORDER BY u.id_utilisateur DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Récupère la liste des rôles pour les selects
     */
    public function getAllRoles(): array
    {
        return $this->db->query("SELECT * FROM role ORDER BY libelle ASC")->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Récupère la liste des postes d'achat pour les selects
     */
    public function getAllPostes(): array
    {
        return $this->db->query("SELECT * FROM poste_achat ORDER BY nom_poste ASC")->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Crée un nouvel utilisateur avec mot de passe haché
     */
    public function create(array $data): bool
    {
        $passwordHash = password_hash($data['mot_passe'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO utilisateur (nom, postnom, prenom, username, mot_passe, statut, id_role, id_poste) 
                VALUES (:nom, :postnom, :prenom, :username, :mot_passe, :statut, :id_role, :id_poste)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nom'        => $data['nom'],
            'postnom'    => $data['postnom'] ?? null,
            'prenom'     => $data['prenom'] ?? null,
            'username'   => $data['username'],
            'mot_passe'  => $passwordHash,
            'statut'     => $data['statut'] ?? 'Actif',
            'id_role'    => $data['id_role'],
            'id_poste'   => $data['id_poste']
        ]);
    }
}