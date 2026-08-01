<?php

namespace App\Middleware;

use App\Core\Session;
use App\Config\Config;

class AuthMiddleware
{
    /**
     * Exige que l'utilisateur soit connecté pour accéder à la page
     */
    public static function handle(): void
    {
        if (!Session::has('user_id')) {
            header('Location: ' . Config::BASE_URL . '/login');
            exit();
        }
    }
}