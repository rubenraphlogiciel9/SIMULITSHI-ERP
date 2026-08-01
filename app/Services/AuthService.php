<?php

namespace App\Services;

use App\Repositories\UtilisateurRepository;
use App\Core\Session;

class AuthService
{
    private UtilisateurRepository $utilisateurRepository;

    public function __construct()
    {
        $this->utilisateurRepository = new UtilisateurRepository();
    }

public function authenticate(string $username, string $password): array
{
    $username = trim($username);
    $password = trim($password);

    if (empty($username) || empty($password)) {
        return ['success' => false, 'message' => 'Veuillez remplir tous les champs.'];
    }

    $user = $this->utilisateurRepository->findByUsername($username);

    // Test 1 : L'utilisateur existe-t-il ?
    if (!$user) {
        return ['success' => false, 'message' => 'Erreur : Nom d\'utilisateur introuvable dans la base de données.'];
    }

    // Test 2 : Le mot de passe correspond-il ?
    if (!password_verify($password, $user['mot_passe'])) {
        return ['success' => false, 'message' => 'Erreur : Le mot de passe ne correspond pas au hash BDD.'];
    }

    if (isset($user['statut']) && $user['statut'] === 'Inactif') {
        return ['success' => false, 'message' => 'Votre compte est désactivé.'];
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $nomComplet = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '') . ' ' . ($user['postnom'] ?? ''));

    Session::set('user_id', $user['id_utilisateur']);
    Session::set('user_nom', $nomComplet ?: $user['username']);
    Session::set('user_username', $user['username']);
    Session::set('user_role', $user['role_nom'] ?? 'Administrateur');
    Session::set('user_role_id', $user['id_role']);
    Session::set('user_poste_id', $user['id_poste']);
    Session::set('user_poste_nom', $user['nom_poste'] ?? 'Dépôt Central');

    return ['success' => true, 'message' => 'Connexion réussie ! Redirection...'];
}
    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public function logout(): void
    {
        Session::destroy();
    }
}