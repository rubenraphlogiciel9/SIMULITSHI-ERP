<?php
namespace App\Config;

use PDO;
use PDOException;

class Database {
    public static function getConnection() {
        // Récupération depuis Render ou fallback sur XAMPP en local
        $host = getenv('DB_HOST') ?: 'localhost';
        $db   = getenv('DB_NAME') ?: 'nom_de_votre_base';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        $port = getenv('DB_PORT') ?: '3306';

        try {
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}