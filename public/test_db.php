<?php

echo "<h2>Test connexion MySQL</h2>";

$host = getenv('DB_HOST') ?: 'sql110.infinityfree.com';
$db   = getenv('DB_NAME') ?: 'if0_42553842_gestion_poste_achat';
$user = getenv('DB_USER') ?: 'if0_42553842';
$pass = getenv('DB_PASS') ?: 'fz786rPGW0n';
$port = getenv('DB_PORT') ?: '3306';

echo "Host : " . $host . "<br>";
echo "Base : " . $db . "<br>";
echo "User : " . $user . "<br><br>";

try {

    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h3 style='color:green'>Connexion réussie ✅</h3>";

} catch (PDOException $e) {

    echo "<h3 style='color:red'>Connexion échouée ❌</h3>";
    echo $e->getMessage();

}