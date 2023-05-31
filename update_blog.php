<?php
//recuperer le fichier de configuration
include('config.php');

// Désactivation du rapport d'erreurs
error_reporting(0);

// Démarrer la session
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer l'ID du blog à partir de l'URL
$blog_id = $_GET['blog_id'];

// Récupère le blog associé à l'identifiant donné
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :blog_id");
$stmt->bindParam(':blog_id', $blog_id);
$stmt->execute();
$blog = $stmt->fetch(PDO::FETCH_ASSOC);


// Vérifie si le blog existe et appartient à l'utilisateur connecté
if (!$blog || $blog['author'] !== $_SESSION['username']) {
    // Rediriger l'utilisateur vers l'index.php
    header('Location: index.php');
    exit;
}


// Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le contenu du blog mis à jour à partir du formulaire
    $updatedContent = $_POST['content'];

    // Mettre à jour le contenu du blog dans la base de données
    $stmt = $pdo->prepare("UPDATE articles SET content = :content WHERE id = :blog_id");
    $stmt->bindParam(':content', $updatedContent);
    $stmt->bindParam(':blog_id', $blog_id);
    $stmt->execute();

    // Rediriger l'utilisateur vers la page de blog mise à jour
    header('Location: article.php?id=' . $blog_id);
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
    <title>Edit Blog</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css"></link>
    <style>
		@import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Lilita+One&family=Lobster+Two&family=Montserrat+Alternates:wght@200;500&family=Satisfy&family=Sigmar&display=swap');
        body {
			background-color: #f1f1f1;
			font-family: 'Montserrat Alternates';
			overflow: hidden;
        }
		nav{
			background-color:#E7E7E7;
		}
        form {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 0.0625em 0.0625em, rgba(0, 0, 0, 0.25) 0px 0.125em 0.5em, rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;
        }
        .form-control:focus {
            border-color: #6c757d;
            box-shadow: none;
        }
        .btn-primary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-primary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
		<ul class="container navbar-nav">
			<li class="nav-item">
				<a href="index.php">
					<img src="pics/logo4.png" alt="Logo" width="65" height="65" class="d-inline-block align-text-top"
					style="border-radius: 17px;">
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link font-weight-bold" href="index.php">Home</a>
			</li>
            <li class="nav-item">
				<a class="nav-link font-weight-bold" href="reviews.php">Reviews</a>
			</li>
			<li class="nav-item">
				<a class="nav-link font-weight-bold" onMouseOver="this.style.color='red'" onmouseout="this.style.color='gray'" href="logout.php">Logout</a>
			</li>
		</ul>
	</nav>

    <div class="container">
        <h2 class="text-center mb-3 text-secondary font-weight-bold mt-2">Edit Blog</h2>
        <form method="POST">
            <div class="form-group">
                <label for="content">Content :</label>
                <textarea class="form-control" id="content" name="content" rows="10"><?php echo $blog['content']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Save</button>
        </form>
    </div>

    <footer class="text-center text-white fixed-bottom" style="background-color: #f1f1f1;">
		<div class="container">
			<!-- Section: Social media -->
			<section class="mb-1">
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
