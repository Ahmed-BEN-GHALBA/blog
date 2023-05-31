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

// Définir les variables "content" et "error".
$content = '';
$contentErr = '';

// Traiter la soumission du formulaire.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Valider le contenu.
	if (empty($_POST['content'])) {
		$contentErr = 'Content is required';
	} else {
		$content = $_POST['content'];
	}

	// Insérer l'avis dans la base de données s'il n'y a pas d'erreurs.
	if (empty($contentErr)) {
		// Récupérer les informations utilisateur depuis la session.
		$userId = $_SESSION['user_id'];
		$author = $_SESSION['username'];

		//Définit une requête SQL pour insérer les données de l'auteur, du contenu et la date actuelle dans la table "reviews", puis prépare cette requête
		$sql = "INSERT INTO reviews (author, content, date) VALUES (:author, :content, NOW())";
		//Prépare la requête SQL.
		$stmt = $pdo->prepare($sql);
		//Lie les paramètres ":author" et ":content" aux valeurs des variables correspondantes.
		$stmt->bindParam(':author', $author);
		$stmt->bindParam(':content', $content);
		// Exécute la requête préparée.
		$stmt->execute();

		// Redirige vers la même page pour éviter la réémission du formulaire.
		header('Location: ' . $_SERVER['PHP_SELF']);
		exit();
	}
}

// Définit une requête SQL pour sélectionner toutes les lignes de la table "reviews" et les trier par ordre décroissant de date.
$sql = "SELECT * FROM reviews ORDER BY date DESC";
//Prépare la requête SQL.
$stmt = $pdo->prepare($sql);
// Exécute la requête préparée.
$stmt->execute();
// Récupère tous les résultats de la requête exécutée et les stocke dans la variable "$reviews" sous forme d'un tableau associatif
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- ajouter le logo de site dans la tab de navigateur -->
	<link rel="icon" href="pics/logo3.png">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
	</link>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="stylePage.css">
	<title>Reviews</title>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-light ">
		<ul class="container navbar-nav">
			<li class="nav-item">
				<img src="pics/logo4.png" alt="Logo" width="65" height="65" class="d-inline-block align-text-top"
					style="border-radius: 17px;">
			</li>
			<li class="nav-item">
				<a class="nav-link font-weight-bold" href="index.php">Home</a>
			</li>
			<li class="nav-item">
				<a class="nav-link font-weight-bold" href="newblog.php">Add New Blog</a>
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

	<div class="card p-4 mt-5 corp">
		<section class="tabs">
			<div class="container">
				<div id="tab-1" class="tab-item tab-border">
					<h2 class="tab-name">Our community experiences</h2>
				</div>
				<div id="tab-2" class="tab-item">
					<h2 class="tab-name">Have something to say?</h2>
				</div>
			</div>
		</section>

		<section class="tab-content">
			<div class="container">
				<div id="tab-1-content" class="tab-content-item show">
					<div class="mt-4 ">
						<div id="reviews-carousel" class="carousel slide" data-bs-ride="carousel">
							<div class="carousel-inner w-75 d-block m-auto comments">
								<?php $i = 0; ?>
								<!-- Boucle foreach pour parcourir le tableau reviews -->
								<?php foreach ($reviews as $review): ?>
										<div class="carousel-content">
											<div class="card-header">
												<p>
													<!-- Afficher le contenu de reviews -->
													<?php echo $review['content']; ?>
												</p>
											</div>
											<hr>
											<div class="card-footer text-muted">
												<p>
													<!-- Afficher l'auteur et la date d'ajout d'avis -->
													Published by
													<?php echo $review['author']; ?> In
													<?php echo date('d-m-Y', strtotime($review['date'])); ?>
												</p>
											</div>
										</div>
									</div>
									<?php $i++; ?>
								<?php endforeach; ?>
							</div>

							<!-- Carousel controls -->
							<button class="carousel-control-prev" type="button" data-bs-target="#reviews-carousel"
								data-bs-slide="prev">
								<span class="carousel-control-prev-icon btn btn-primary" aria-hidden="true"></span>
								<span class="visually-hidden">Previous</span>
							</button>
							<button class="carousel-control-next" type="button" data-bs-target="#reviews-carousel"
								data-bs-slide="next">
								<span class="carousel-control-next-icon btn btn-primary" aria-hidden="true"></span>
								<span class="visually-hidden">Next</span>
							</button>
						</div>
					</div>

				</div>
				<div id="tab-2-content" class="tab-content-item">
					<div class="form-floating m-4">
						<p class="rate">We value your feedback! Please take a moment <br> to rate our site and share
							your thoughts.</p>
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form-floating">
							<textarea name="content" id="content" class="form-control" style="height:150px"></textarea>
							<br>

							<input type="submit" value="Submit" class="btn btn-secondary btn-outline-light btn-block">
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>



	<footer class="text-center text-white fixed-bottom">
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
	<script>
		const tabItems = document.querySelectorAll('.tab-item'); // Sélectionne tous les éléments avec la classe 'tab-item'
		const tabContentItems = document.querySelectorAll('.tab-content-item'); // Sélectionne tous les éléments avec la classe 'tab-content-item'

		function selectItem(e) {
			removeBorder(); //Appelle la fonction removeBorder()
			removeShow(); //Appelle la fonction removeShow()
			
			this.classList.add('tab-border'); // ajouter bordure a cette tab item
			
			const tabContentItem = document.querySelector(`#${this.id}-content`);  // Ajoute la classe 'show' à cet élément
			
			tabContentItem.classList.add('show'); // ajouter show class
		}
		
		function removeBorder() {
			//parcourir tous les éléments de tabItems
			tabItems.forEach(item => {
				item.classList.remove('tab-border'); // Supprime la classe 'tab-border' de chaque élément
			});
		}
		
		function removeShow() {
			//parcourir tous les éléments de tabContentItems
			tabContentItems.forEach(item => {
				item.classList.remove('show'); // Supprime la classe 'show' de l'élément
			});
		}
		// on execute la fonction selectItem() lorsque on ecoute un evenement (click) sur chaque element du tabItems
		tabItems.forEach(item => {
			item.addEventListener('click', selectItem);
		});
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>