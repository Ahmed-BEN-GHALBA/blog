<?php
//recuperer le fichier de configuration
include('config.php');

// Désactivation du rapport d'erreurs
error_reporting(0);

// Démarrer la session
session_start();

// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location:login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ajouter le logo de site dans la tab de navigateur -->
    <link rel="icon" href="pics/logo3.png"> 
    <title>My Blog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css"></link>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Lilita+One&family=Lobster+Two&family=Montserrat+Alternates:wght@200;500&family=Satisfy&family=Sigmar&display=swap');

        ::-webkit-scrollbar {
            width: 1px;
        }
        
        body {
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #E7E7E7;
            display: flex;
            justify-content: space-between;
        }

        ul {
            display: contents;
        }

        .text {
            background-color: #f0f0f0;
            font-family: 'satisfy';
        }

        .container,
        footer {
            font-family: 'Montserrat Alternates';
            font-weight: 500;
        }

        footer {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light ">
        <ul class="container navbar-nav">
            <li class="nav-item">
                <img src="pics/logo4.png" alt="Logo" width="65" height="65" class="d-inline-block align-text-top"
                    style="border-radius: 17px;">
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" href="newblog.php">Add New Blog</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" href="reviews.php">Reviews</a>
            </li>
            <?php
            //vérifier si l'utilisateur connecté est un administrateur
            if (isset($_SESSION['admin'])) {
                //Si oui, alors afficher un lien vers le tableau de bord dans la barre de navigation
                echo "<li class='nav-item'>";
                echo "<a class='nav-link font-weight-bold' href='dashboard.php'>Dashboard</a>";
                echo "</li>";
            }
            ?>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" onMouseOver="this.style.color='red'"
                    onmouseout="this.style.color='gray'" href="logout.php">Logout</a>
            </li>
        </ul>
    </nav>

    <div class="container">
        <div class="row d-flex justify-content-between">
            <?php
            // Prépare une instruction (statement) SQL pour sélectionner toutes les lignes de la table "articles" et les trier par ordre décroissant d'identifiant.
            $stmt = $pdo->prepare("SELECT * FROM articles ORDER BY id DESC");
            // Exécute l'instruction préparée.
            $stmt->execute();
            // Récupère tous les résultats de l'instruction exécutée et les stocke dans la variable "$articles" sous forme d'un tableau associatif.
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Boucle foreach pour parcourir le tableau des blogs
            foreach ($articles as $article) {
                echo "<div class='card m-3' style='background-color:#EFF2F3 ;width: 20rem;height:65vh;box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;  border-radius: 25px;'>";
                echo "<div class='card-body d-flex flex-column'>";
                //Afficher le titre du Blog
                echo "<h3 class='card-title text-center text-secondary font-weight-bold'>" . $article['title'] . "</h3>";
                echo "<div class='text' style='background-color:white;box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px; border-radius: 25px;'>";
                //Afficher le contenu du Blog
                echo "<p class='card-text m-2' style='overflow:hidden; height:22vh;  filter: blur(2px);'>" . $article['content'] . "</p>";
                echo "</div>";
                //Afficher l'auteur' du Blog
                echo "<p class='mt-auto text-center'>Published by <b>" . $article['author'] . "</b></p>";
                //vérifier si le blog appartient à l'utilisateur connecté
                $isUserBlog = ($article['author'] === $_SESSION['username']);
                if ($isUserBlog) {
                    //Si oui, alors afficher un bouton pour éditer le blog
                    echo "<a class='btn btn-light btn-outline-info mt-auto' href='update_blog.php?blog_id=" . $article['id'] . "'>Edit</a>";
                }
                //Afficher un bouton pour ouvrir et lire le blog
                echo "<a class='btn btn-secondary mt-auto' width: 18rem; href='article.php?id=" . $article['id'] . "'>Read More</a>";
                echo "</div>";
                //Afficher la date du Blog
                echo "<div class='card-footer text-center text-muted'>" . date('d-m-Y', strtotime($article['date'])) . "";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <footer class="text-center text-white">
        <div class="container mb-0">
            <!-- Section: Social media -->
            <section>
                <!-- Facebook -->
                <a class="btn btn-link btn-floating btn-lg text-dark m-1" href="#!" role="button"
                    data-mdb-ripple-color="dark"><i class="fab fa-facebook-f"></i></a>
                <!-- Twitter -->
                <a class="btn btn-link btn-floating btn-lg text-dark m-1" href="#!" role="button"
                    data-mdb-ripple-color="dark"><i class="fab fa-twitter"></i></a>
                <!-- Google -->
                <a class="btn btn-link btn-floating btn-lg text-dark m-1" href="#!" role="button"
                    data-mdb-ripple-color="dark"><i class="fab fa-google"></i></a>
                <!-- Instagram -->
                <a class="btn btn-link btn-floating btn-lg text-dark m-1" href="#!" role="button"
                    data-mdb-ripple-color="dark"><i class="fab fa-instagram"></i></a>
                <!-- Linkedin -->
                <a class="btn btn-link btn-floating btn-lg text-dark m-1" href="#!" role="button"
                    data-mdb-ripple-color="dark"><i class="fab fa-linkedin"></i></a>
                <!-- Github -->
                <a class="btn btn-link btn-floating btn-lg text-dark m-1" href="#!" role="button"
                    data-mdb-ripple-color="dark"><i class="fab fa-github"></i></a>
            </section>
        </div>
        <!-- Copyright -->
        <div class="text-center text-dark p-4" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2023 Copyright:
            <a class="text-dark font-weight-bold text-decoration-none" href="#">Ahmed Amine Ben Ghalba</a>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>

</html>