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

// Supprimer un article de blog
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

// Redirection vers le tableau de bord admin
header('Location: dashboard.php');
exit;
?>
