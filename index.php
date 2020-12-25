<?php
	session_start();
	$connect=mysqli_connect('localhost', 'root', '', 'reservationsalles');

	if(isset($_POST['disconnect']) && $_POST['disconnect']){
		session_destroy();
		header("Refresh:0");
	}

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

	<head>
		<title>Accueil</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
		<link rel="stylesheet" type='text/css' href="css/salles.php?v=<?php echo time(); ?>">
		<script src="https://kit.fontawesome.com/9ddb75d515.js" crossorigin="anonymous"></script>
		<link href="https://fonts.googleapis.com/css2?family=Syne&display=swap" rel="stylesheet"> 
	</head>

	<body>
		<a href="pages/inscription.php">Inscription</a><br>
		<a href="pages/connexion.php">Connexion</a><br>
		<a href="pages/profil.php">Profil</a><br>
		<a href="pages/planning.php">Planning</a><br>
		<a href="pages/reservation-form.php">Réserver</a><br>
		<form method="post" action="index.php"><input type="checkbox" hidden checked name="disconnect" id="disconnect">
			<input type="submit" id="disconnect_button" value="Déconnecter"></form>
	</body>
</html>