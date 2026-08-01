<?php
// Script temporaire de réinitialisation de mot de passe

$host = 'localhost';
$dbname = 'gestion_poste_achat';
$user = 'root';
$pass = ''; // Mets ton mot de passe MySQL si tu en as un dans XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Génération du vrai hash par ton propre serveur PHP
    $newPassword = 'admin123';
    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);

    // Mise à jour de l'utilisateur admin
    $stmt = $pdo->prepare("UPDATE utilisateur SET mot_passe = :hash WHERE username = 'admin'");
    $stmt->execute(['hash' => $newHash]);

    echo "<h2 style='color: green;'>✅ Mot de passe réinitialisé avec succès !</h2>";
    echo "<p>Identifiant : <strong>admin</strong></p>";
    echo "<p>Nouveau mot de passe : <strong>admin123</strong></p>";
    echo "<p>Nouveau Hash généré : <code>" . $newHash . "</code></p>";
    echo "<hr><p><a href='/SIMULITSHI-ERP/public/login'>Retourner à la page de connexion</a></p>";

} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Erreur BDD : " . $e->getMessage() . "</h2>";
}