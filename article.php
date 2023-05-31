<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require_once 'config.php';

//recuperer le fichier qui inclu la fonction getlikecount 
require_once 'like.php';


// Vérifier si l'ID de l'article est spécifié dans l'URL
if (isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
	// Si l'ID de l'article n'est pas spécifié, rediriger vers la page d'accueil
	header("Location: index.php");
	exit();
}

// Requête SQL pour récupérer tous les articles avec l'ID spécifié
$sql = "SELECT * FROM articles WHERE id = :id";
// Prépare la requête SQL pour exécution
$stmt = $pdo->prepare($sql);
// Lie la valeur de la variable $id au paramètre :id de la requête SQL
$stmt->bindParam(':id', $id);
// Exécute la requête SQL
$stmt->execute();
// Récupère la résultat sous forme de tableau associatif
$row = $stmt->fetch(PDO::FETCH_ASSOC);


// Requête SQL pour compter le nombre de likes pour un article spécifique
$likesSql = "SELECT COUNT(*) AS like_count FROM likes WHERE article_id = :id";
// Prépare la requête SQL pour exécution
$likesStmt = $pdo->prepare($likesSql);
// Lie la valeur de la variable $id au paramètre :id de la requête SQL
$likesStmt->bindParam(':id', $id);
// Exécute la requête SQL
$likesStmt->execute();
// Récupère la résultat sous forme de tableau associatif
$likesRow = $likesStmt->fetch(PDO::FETCH_ASSOC);
// Récupère la valeur du champ 'like_count' du tableau associatif
$likeCount = $likesRow['like_count'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>My Blog</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ajouter le logo de site dans la tab de navigateur -->
	<link rel="icon" href="pics/logo3.png"> 
	<!-- Bootstrap 5 stylesheet et font icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
	</link>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
	<style>
        @import url('https://fonts.googleapis.com/css2?family=Acme&family=Dancing+Script:wght@700&family=Lilita+One&family=Lobster+Two&family=Montserrat+Alternates:wght@200;500&family=Oswald:wght@500&family=Satisfy&family=Sigmar&display=swap');

		body {
			background-color: #f8f9fa;
		}

		h2 {
			color: #343a40;
			margin-top: 2rem;
			margin-bottom: 1rem;
			font-family: acme;
            font-weight: 200;
		}

		p {
			color: #6c757d;
			font-size: 1.3rem;
		}

		nav,
		a {
			font-family: 'Montserrat Alternates';
			font-weight: 500;
			font-weight: bold;
		}

		.row {
			width: 85vw;
		}

		.blog_content {
			height: 35vh;
			overflow: auto;
			padding: 10px;
		}

		.blog_content::-webkit-scrollbar {
            width: 6px;
        }

        .blog_content::-webkit-scrollbar-thumb {
            border-radius: 100px;
            background: rgba(48, 144, 199, 0.6);
            opacity: 0.9;
            border: 6px solid rgba(0, 0, 0, 0.2);
        }
	</style>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #f1f1f1;">
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
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<!-- Afficher le titre du Blog -->
				<h2>
					<?php echo $row['title']; ?>
				</h2>
				<!-- Afficher le contenu du Blog -->
				<p class="blog_content">
					<?php echo $row['content']; ?>
				</p>
				<hr>
				<!-- Créer un formulaire qui utilise la méthode POST -->
				<form action="like.php" method="post">
					<input type="hidden" name="article_id" value="<?php echo $id; ?>">
					<button type="submit" class="btn btn-link" style="background: none; border: none;"><i
							class='far fa-heart' style='font-size:30px;color:red'></i></button>
					<!-- Afficher le nombre de likes en appelant la fonction getLikeCount -->
					<span style="font-size:20px">
						<?php echo getLikeCount($id); ?>
					</span>
				</form>
				<hr>
				<!-- Afficher l'auteur' du Blog -->
				<p>
					<?php echo $row['author']; ?>
				</p>
				<!-- Afficher la date du Blog -->
				<p>
					<?php echo $row['date']; ?>
				</p>
			</div>
		</div>
	</div>

	<footer class="text-center text-white fixed-bottom mt-5" style="background-color: #f1f1f1;">
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
	<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>

</html>