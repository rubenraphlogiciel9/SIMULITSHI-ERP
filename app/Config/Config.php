<?php

namespace App\Config;

class Config
{
    // URL de base du projet sur XAMPP
    public const BASE_URL = 'http://localhost/SIMULITSHI-ERP';

    // Nom de l'application
    public const APP_NAME = 'SIMULITSHI ERP';

    // Configuration de la Base de Données
    public const DB_HOST = 'localhost';
    public const DB_NAME = 'gestion_poste_achat';
    public const DB_USER = 'root';
    public const DB_PASS = ''; // Mettre ton mot de passe phpMyAdmin si tu en as défini un
    public const DB_CHARSET = 'utf8mb4';
}