<?php
//recuperer le fichier de configuration
include('config.php');

//recuperer le fichier qui inclu la fonction getlikecount 
require_once 'like.php';


// Désactivation du rapport d'erreurs
error_reporting(0);

// Démarrer la session
session_start();

// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté en tant qu'un administrateur
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Récupèrer la liste des blogs
$stmt = $pdo->prepare("SELECT * FROM articles ORDER BY date DESC");
$stmt->execute();
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupèrer la liste des utilisateurs
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupèrer la liste des avis
$stmt = $pdo->prepare("SELECT * FROM reviews");
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Récupèrer le nombre de "j'aime" pour chaque article
foreach ($blogs as &$blog) {
    $blog['like_count'] = getLikeCount($blog['id']);
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
    <title>Dashboard</title>
    <!-- Bootstrap 5 stylesheet et font icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
    </link>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Acme&family=Dancing+Script:wght@700&family=Lilita+One&family=Lobster+Two&family=Montserrat+Alternates:wght@200;500&family=Oswald:wght@500&family=Satisfy&family=Sigmar&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');

        ::-webkit-scrollbar {
            width: 1px;
        }

        .content::-webkit-scrollbar {
            width: 6px;
        }

        .content::-webkit-scrollbar-thumb {
            border-radius: 100px;
            background: rgba(48, 144, 199, 0.6);
            opacity: 0.9;
            border: 6px solid rgba(0, 0, 0, 0.2);
        }

        span {
            cursor: pointer;
            display: flex;
            justify-content: space-around;
            margin: auto;
            width: 100%;
            font-family: 'Montserrat Alternates', cursive;
            font-weight: bold;
            font-size: 50PX;
            background-image: linear-gradient(#3090C7, #3090C7);
            background-size: 100% 6px;
            background-repeat: no-repeat;
            background-position: 100% 0%;
            transition: background-size .7s, background-position .5s ease-in-out;
        }

        span:hover {
            width: 100%;
            background-size: 100% 100%;
            background-position: 0% 100%;
            transition: background-position .7s, background-size .5s ease-in-out;
            color: white;
        }

        h1,
        th {
            font-family: acme;
            font-weight: 200;
        }

        .table {
            text-align: center;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 0.0625em 0.0625em, rgba(0, 0, 0, 0.25) 0px 0.125em 0.5em, rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;
        }

        .content {
            width: 40vw;
            height: 20vh;
            overflow: auto;
            padding: 10PX;
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        }

        nav {
            background-color: #E7E7E7;
            display: flex;
            justify-content: space-between;
            font-family: 'Montserrat Alternates';
            font-weight: 500;
        }

        nav,
        a {
            font-size: 20PX;
            font-weight: bold;
        }

        footer {
            font-family: 'Montserrat Alternates';
            font-weight: 500;
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
            <li>
                <a class="text-secondary text-left text-decoration-none h3" style="font-family: Oswald;">
                    Welcome "
                    <?php echo $_SESSION['username']; ?> "
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" href="#Blogs">Blogs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" href="#Users">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" href="#reviews">Reviews</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" onMouseOver="this.style.color='#3090C7'"
                    onmouseout="this.style.color='gray'" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" onMouseOver="this.style.color='red'"
                    onmouseout="this.style.color='gray'" href="logout.php">Logout</a>
            </li>
        </ul>
    </nav>

    <span> Admin Dashboard </span>
    <hr>
    <div class="container">
        <h1 id="Blogs">List of Blogs :</h1>
        <hr>
        <table class="table table-striped align-middle">
            <thead>
                <tr class="h5">
                    <th>ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Author</th>
                    <th>Likes</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Boucle foreach pour parcourir le tableau des blogs -->
                <?php foreach ($blogs as $blog) { ?>
                    <tr>
                        <!-- Affichage de l'ID du BLog -->
                        <td>
                            <?php echo $blog['id']; ?>
                        </td>
                        <!-- Affichage du titre du BLog -->
                        <td>
                            <?php echo $blog['title']; ?>
                        </td>
                        <!-- Affichage du contenu du BLog -->
                        <td>
                            <div class="content">
                                <?php echo $blog['content']; ?>
                            </div>
                        </td>
                        <!-- Affichage d'auteur du BLog -->
                        <td>
                            <?php echo $blog['author']; ?>
                        </td>
                        <!-- Affichage du nbre de j'aime du BLog -->
                        <td>
                            <?php echo $blog['like_count']; ?>
                        </td>
                        <!-- Affichage du date de creation du BLog -->
                        <td>
                            <?php echo $blog['date']; ?>
                        </td>
                        <!-- boutton pour supprimer une BLog -->
                        <td>
                            <!-- direger vers le fichier de suppression avec passage de l'id du Blog dans l'URL -->
                            <a href="delete_blog.php?id=<?php echo $blog['id']; ?>"
                                class="btn btn-danger btn-lg btn-50">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <hr>
        <h1 id="Users">List of Users :</h1>
        <hr>
        <table class="table table-striped align-middle">
            <thead>
                <tr class="h5">
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Delete</th>
                    <th>Change Role</th>
                </tr>
            </thead>
            <tbody>
                <!-- Boucle foreach pour parcourir le tableau des utilisateurs -->
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <!-- Affichage de l'ID de l'utilisateur -->
                        <td>
                            <?php echo $user['id']; ?>
                        </td>
                        <!-- Affichage du nom d'utilisateur -->
                        <td>
                            <?php echo $user['username']; ?>
                        </td>
                        <!-- Affichage de l'e-mail de l'utilisateur -->
                        <td>
                            <?php echo $user['email']; ?>
                        </td>
                        <!-- Affichage du mot de passe de l'utilisateur -->
                        <td>
                            <?php echo $user['password']; ?>
                        </td>
                        <!-- Affichage du rôle de l'utilisateur -->
                        <td>
                            <?php echo $user['role']; ?>
                        </td>
                        <!-- boutton pour supprimer un utilisateur -->
                        <td>
                            <!-- direger vers le fichier de suppression avec passage de l'id d'utilisateur dans l'URL -->
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger w-75">Delete
                                User</a>
                        </td>
                        <!-- boutton non-utilisable pour changer le role d'un utilisateur -->
                        <td>
                            <a href="#" class="btn btn-secondary disabled w-75">Disabled</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <hr>
        <h1 id="reviews">List of Reviews :</h1>
        <hr>
        <table class="table table-striped align-middle">
            <thead>
                <tr class="h5">
                    <th>ID</th>
                    <th>Author</th>
                    <th>Content</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Boucle foreach pour parcourir le tableau des avis -->
                <?php foreach ($reviews as $review) { ?>
                    <tr>
                        <!-- Affichage de l'ID d'avis -->
                        <td>
                            <?php echo $review['id']; ?>
                        </td>
                        <!-- Affichage de redigeur d'avis -->
                        <td>
                            <?php echo $review['author']; ?>
                        </td>
                        <!-- Affichage du contenu d'avis -->
                        <td>
                            <?php echo $review['content']; ?>
                        </td>
                        <!-- boutton pour supprimer un avis -->
                        <td>
                            <!-- direger vers le fichier de suppression avec passage de l'id d'avis dans l'URL -->
                            <a href="delete_review.php?id=<?php echo $review['id']; ?>"
                                class="btn btn-outline-danger btn-light btn-lg btn-75">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <hr>
    </div>
    <hr><br>
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
    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>