<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Repositories\UtilisateurRepository;

class UtilisateurController extends Controller
{
    private UtilisateurRepository $utilisateurRepo;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->utilisateurRepo = new UtilisateurRepository();
    }

    public function index(): void
    {
        $utilisateurs = $this->utilisateurRepo->getAll();
        $roles        = $this->utilisateurRepo->getAllRoles();
        $postes       = $this->utilisateurRepo->getAllPostes();

        $this->render('utilisateurs/index', [
            'title'        => 'Gestion des Utilisateurs',
            'utilisateurs' => $utilisateurs,
            'roles'        => $roles,
            'postes'       => $postes
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->utilisateurRepo->create($_POST);

            if ($success) {
                $this->json(['success' => true, 'message' => 'Utilisateur créé avec succès.']);
            } else {
                $this->json(['success' => false, 'message' => 'Erreur lors de la création de l\'utilisateur.']);
            }
        }
    }
}