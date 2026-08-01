<?php
namespace App\Config;

use PDO;
use PDOException;

class Database {
    public static function getConnection() {
        // Récupération depuis Render ou fallback sur XAMPP en local
        $host = 'sql110.infinityfree.com';
        $db   = 'if0_42553842_gestion_poste_achat';
        $user = 'if0_42553842';
        $pass = 'fz786rPGW0n';
        $port = '3306';

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