<?php
//recuperer le fichier de configuration
include('config.php');

// Désactivation du rapport d'erreurs
error_reporting(0);

// Démarrer la session
session_start();

// Redirection vers la page de connexion si l'utilisateur n'est pas connecté en tant qu'administrateur
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Supprimer un avis de la base de données a partir de l'ID d'avis fourni
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

// Redirection vers le tableau de bord admin
header('Location: dashboard.php');
exit;
?>
