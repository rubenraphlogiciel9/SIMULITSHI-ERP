<?php

namespace App\Core;

use App\Config\Config;

abstract class Controller
{
    /**
     * Charge une vue HTML/PHP et lui passe des données
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        $viewFile = ROOT_PATH . "/app/Views/{$view}.php";

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Erreur : La vue <strong>{$view}</strong> est introuvable.");
        }
    }

    /**
     * Redirige l'utilisateur vers une URL interne
     */
    protected function redirect(string $path): void
    {
        header("Location: " . Config::BASE_URL . '/' . ltrim($path, '/'));
        exit();
    }

    /**
     * Renvoie une réponse JSON (utilisé pour les requêtes AJAX / Fetch)
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }
}