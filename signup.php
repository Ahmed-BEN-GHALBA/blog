<?php
// Inclure le fichier de configuration
include('config.php');

// Désactivation du rapport d'erreurs
error_reporting(0);

// Démarrer la session
session_start();

//récupèrer les informations soumises par le formulaire d'inscription.
if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier si le nom d'utilisateur ou l'e-mail existe déjà dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:username OR email=:email");
    $stmt->execute(array(':username'=>$username, ':email'=>$email));
    $count = $stmt->rowCount();

    if($count == 0){
        // Le nom d'utilisateur et l'e-mail sont disponibles, insérer le nouvel utilisateur dans la base de données
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $role = 'user'; // définir le rôle par défaut sur "user"
        $stmt->bindParam(':role', $role);

        if($stmt->execute()){
            // Inscription réussie, rediriger vers la page de connexion
            header('Location: login.php');
            exit;
        } else {
            //un message d'erreur est affiché
            echo '<script type="text/javascript">';
            echo ' alert("An error occurred, please try again.")';
            echo '</script>';
        }
    } else {
        //un message d'erreur est affiché
        echo '<script type="text/javascript">';
        echo ' alert("Account already exists ! ")'; 
        echo '</script>';
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
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css"></lin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">    
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Lilita+One&family=Lobster+Two&family=Montserrat+Alternates:wght@200;500&family=Satisfy&family=Sigmar&display=swap');
       body {
            background-color: #f0f0f0;
            font-family: 'satisfy';
        }
        footer{
            font-family: 'Montserrat Alternates';
        }
        form {
            font-family: 'Montserrat Alternates';
            font-weight:500;
            background-color: #fff;
            padding: 20px;
            border-radius:25px 0 0 25px;
        }
        img{
            border-radius:0 25px 25px 0;
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
            <div class="col-md-6">
                <form method="post" action="signup.php" style="height:100%">
                <h2 class="text-center mb-4">Join Our Blog Community!</h2>
                    <div class="mb-3">
                        <input type="text" name="username" class="form-control" id="username" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                    </div>
                    <div class="mb-5">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                    </div>
                    <div class="d-grid gap-2 mb-3">
                        <input type="submit" name="submit" value="sign me up!" class="btn btn-secondary">
                    </div>
                    <div class="text-center">
                        Already a member ? <a class="text-info text-decoration-none" href="login.php">Sign in</a>
                    </div>
                </form>
            </div>
            <img class="col-md-6" src="pics/blog2.png" alt="picture not found">
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
			<a class="text-dark text-decoration-none" href="#">Ahmed Amine Ben Ghalba</a>
		</div>
	</footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script></body>
</html>                   
