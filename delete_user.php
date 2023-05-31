<?php
//récupérer le fichier de configuration
include('config.php');

// Désactiver le rapport d'erreurs
error_reporting(0);

// Démarrer la session
session_start();

// Redirection vers la page de connexion si l'utilisateur n'est pas connecté en tant qu'administrateur
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Vérifier si l'ID de l'utilisateur est fourni
if (isset($_GET['id'])) {
    // Récupérer l'ID de l'utilisateur depuis la chaîne de requête
    $userId = $_GET['id'];

    // Supprimer l'utilisateur de la base de données
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId);
    $stmt->execute();

    // Rediriger vers la page de tableau de bord des utilisateurs
    header('Location: dashboard.php#Users');
    exit;
}

?>
