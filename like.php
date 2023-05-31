<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require_once 'config.php';

// Démarre la session
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Redirect the user or display an error message
    header('Location: login.php');
    exit;
}

// Vérifie si l'ID de l'article est spécifié dans la requête POST
if (isset($_POST['article_id'])) {
    $articleId = $_POST['article_id'];

    // Vérifie si l'utilisateur a déjà aimé l'article
    $sql = "SELECT COUNT(*) AS count FROM likes WHERE user_id = :user_id AND article_id = :article_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->bindParam(':article_id', $articleId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    //empêcher l'utilisateur d'aimer l'article plus d'une fois
    if ($result['count'] > 0) {
        header("Location: article.php?id=$articleId&error=already_liked");
        exit();
    }

    // Insère les j'aimes dans la base de données
    $sql = "INSERT INTO likes (user_id, article_id, date) VALUES (:user_id, :article_id, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->bindParam(':article_id', $articleId);
    $stmt->execute();

    // Rediriger vers la page de l'article
    header("Location: article.php?id=$articleId");
    exit();
}

//effectuer une requete pour compter les occurences dans la table "likes" pour l'ID du blog fourni
function getLikeCount($articleId) {
    global $pdo;
    $sql = "SELECT COUNT(*) AS like_count FROM likes WHERE article_id = :article_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':article_id', $articleId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['like_count'];
}
?>
