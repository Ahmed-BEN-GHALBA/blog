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

// Vérifie si le bouton de soumission "submit" a été envoyé avec la méthode POST
if (isset($_POST['submit'])) {
	// Récupère la valeur du champ "title" depuis les données du formulaire.
	$title = $_POST['title'];
	// Récupère la valeur du champ "content" depuis les données du formulaire.
	$content = $_POST['content'];
	// Récupère le nom d'utilisateur depuis la session et le stocke dans la variable "$author"
	$author = $_SESSION['username'];
	// Génère la date actuelle au format 'Y-m-d H:i:s' et la stocke dans la variable "$date".
	$date = date('Y-m-d H:i:s');
	// Prépare une requête pour insérer les valeurs dans la table "articles".
	$stmt = $pdo->prepare("INSERT INTO articles (title, content, author, date) VALUES (:title, :content, :author, :date)");
	// Exécute la requête préparée avec les valeurs à insérer fournies dans un tableau associatif.
	$stmt->execute(array(':title' => $title, ':content' => $content, ':author' => $author, ':date' => $date));
	// Si la requête s'est exécutée avec succès, redirige l'utilisateur vers la page "index.php".
	if ($stmt) {
		header('Location:index.php');
		exit;
		// Sinon, affiche un message d'erreur indiquant l'échec de l'ajout du nouvel article de blog.
	} else {
		echo "Failed to add new blog post";
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>New Blog</title>
	<!-- ajouter le logo de site dans la tab de navigateur -->
	<link rel="icon" href="pics/logo3.png"> 
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css"></link>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Lilita+One&family=Lobster+Two&family=Montserrat+Alternates:wght@200;500&family=Satisfy&family=Sigmar&display=swap');

		body {
			background-color: #f1f1f1;
			font-family: 'Montserrat Alternates';
			overflow: hidden;
		}

		nav {
			background-color: #E7E7E7;
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
		<h2 class="text-center mb-3 text-secondary font-weight-bold mt-2">New Blog</h2>
		<div>
			<div>
				<form method="post">
					<div class="form-floating mb-3">
						<label for="title">Title :</label>
						<input type="text" name="title" class="form-control" id="title"
							placeholder="type in your blog title" required>
					</div>
					<div class="form-floating mb-3">
						<label for="content">Content :</label>
						<textarea name="content" rows="6" cols="50" class="form-control" id="content"
							placeholder="Speak your mind " required></textarea>
					</div>
					<div class="d-grid gap-2">
						<input type="submit" name="submit" value="Submit" class="btn btn-primary btn-block">
					</div>
				</form>
			</div>
		</div>
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

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
</body>

</html>