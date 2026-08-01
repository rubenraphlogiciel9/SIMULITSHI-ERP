<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\AuthService;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function login(): void
    {
        // Si déjà connecté, rediriger vers le dashboard
        if (AuthService::check()) {
            $this->redirect('/dashboard');
            return;
        }

        // Rendu de la vue d'authentification sans layout principal
        $this->render('auth/login');
    }

    /**
     * Traite la soumission AJAX du formulaire
     */
    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        try {
            $result = $this->authService->authenticate($username, $password);
            $this->json($result);
        } catch (\Throwable $e) {
            // Renvoie le message d'erreur exact dans le flux AJAX pour débugger
            $this->json([
                'success' => false, 
                'message' => 'DÉBOGAGE: ' . $e->getMessage() . ' dans ' . $e->getFile() . ':' . $e->getLine()
            ], 500);
        }
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(): void
    {
        $this->authService->logout();
        $this->redirect('/login');
    }
}