<?php
//recuperer le fichier de configuration
include('config.php');

// Désactivation du rapport d'erreurs
error_reporting(0);

// Démarrer la session
session_start();

if(isset($_POST['submit'])){ //Vérifie si le formulaire a été soumis
    $email = $_POST['email']; // Récupère la valeur de l'adresse email depuis le formulaire
    $password = $_POST['password'];// Récupère la valeur du mot de passe depuis le formulaire

    // Prépare la requête SQL pour sélectionner les utilisateurs correspondant à l'adresse email et au mot de passe saisis
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email and password=:password");
    // Exécute la requête SQL en passant les valeurs de l'adresse email et du mot de passe
    $stmt->execute(array(':email'=>$email, ':password'=>$password));

    // Récupère la première ligne de résultat de la requête
    $role = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifie si des résultats ont été trouvés
    if($role){ 
        $_SESSION['user_id'] = $role['id']; // Stocke l'ID de l'utilisateur dans la session
        $_SESSION['username'] = $role['username']; // Stocke le nom d'utilisateur dans la session
        
        // Vérifie si le rôle de l'utilisateur est un simple utilisateur alors rediger le vers index.php
        if($role['role'] == 'user'){ 
            header('Location:index.php');
            exit; // Met fin à l'exécution du script

          //sinon, s'il est un admin rediger le vers dashboard.php
        } else if($role['role'] == 'admin'){
            $_SESSION['admin'] = true; // Définit une variable de session pour indiquer que l'utilisateur est un admin
            header('Location:dashboard.php');
            exit; // Met fin à l'exécution du script
        }
    } else {
        //si aucune correspondance trouvée,alors afficher msg d'erreur 
        $errorMsg = "Vérifiez vos informations svp ! l'un de votre E-mail or Password are wrong";
    }

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
    <title>Login</title>
    <!-- Bootstrap 5 stylesheet -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css"></lin>
    <script>
        <?php if(isset($errorMsg)): ?>
            alert("<?php echo $errorMsg; ?>");
        <?php endif; ?>
    </script>
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Lilita+One&family=Lobster+Two&family=Montserrat+Alternates:wght@200;500&family=Satisfy&family=Sigmar&display=swap');
       body {
            background-color: #f0f0f0;
            font-family: 'satisfy';
        }
         footer{
            font-family: 'Montserrat Alternates';
            font-weight:500;    
            background-color: #f1f1f1;        
        }
        form {
            font-family: 'Montserrat Alternates';
            font-weight:500;
            background-color: #fff;
            padding: 20px;
            border-radius:0 25px 25px 0;
        }
        img{
            border-radius:25px 0 0 25px;
        }
        .form-control:focus {
            border-color: #6c757d;
        }
        .btn-primary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-primary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .rounded-box {
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            border-radius: 40px;
            margin: auto;
            width: 100%;
            height:60vh;
    }        
        .rounded-container {
            border-radius: 40px;
            margin: auto;
            width: 50%;
            height:60vh;
    }
    </style>
</head>
<body>
    <div class="container rounded-container mt-2">
    <figure class="text-center text-secondary mt-4">
            <blockquote class="blockquote">
                <h1>Where Words Paint a Thousand Pictures.</h1>
                <h4>Blog site</h4>    
            </blockquote>
        </figure>
        <div class="d-flex m-3 rounded-box mt-2">
            <img class="col-md-6" src="pics/blog6.png" alt="picture not found">
            <div class="col-md-6">
                <form method="post" action="login.php" style="height:100%">
                    <fieldset>
                        <h1 class="text-center mb-4 mt-4">Sign In</h1>
                        <div class="mb-4">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="example@gmail.com" required>
                        </div>
                        <div class="mb-5">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="*******" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
                        </div>
                        <div class="text-center">
                            Not a member ? <a class="text-info text-decoration-none " href="signup.php">Sign up</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
	<footer class="text-center text-white fixed-bottom">
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
			<a class="text-dark text-decoration-none " href="#">Ahmed Amine Ben Ghalba</a>
		</div>
	</footer>
    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
