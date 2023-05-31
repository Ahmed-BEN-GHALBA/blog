<?php
// Définition des paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog";

// Création d'un objet PDO ( PHP Data Objects ) et configuration du mode de gestion des erreurs
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
