<?php
	session_start();
	$connect=mysqli_connect('localhost', 'root', '', 'reservationsalles');
	$connect->query('SET NAMES utf8');
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<title>Connexion</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Geo&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" href="salles.css?v=<?php echo time(); ?>">
		<script src="https://kit.fontawesome.com/9ddb75d515.js" crossorigin="anonymous"></script>
	</head>

	<body id="body_connexion">
		<?php

			if(!isset($_SESSION['user'])) {
				if($_POST){
					$login=$_POST['login'];$password=$_POST['password'];
					$stmt=$connect->prepare('SELECT * FROM utilisateurs WHERE login=? ');
					$stmt->bind_param("s", $login);
					$stmt->execute();
					$result = $stmt->get_result();
					$user = $result->fetch_assoc();

					if(empty($user)){
						?>
						<main class="err_connexion"><p><?php
						echo "Identifiant ou mot de passe incorrect.<br>";?><div id="back2index"><a href="connexion.php">Réessayez</a><br><a href="">Mot de passe oublié ?</a></div></p>
						</main>
						<?php
						exit();
					}

					else{
						if($login==$user['login']){
							if(password_verify($password, $user['password'])){
								?><main class="err_connexion"><p><?php
								echo "Vous êtes maintenant connecté. <br>";?><div id="back2index"><a href="index.php">Accueil</a></div>
								</p></main><?php
								$_SESSION['user']=['login'=>$user['login'], 'password'=>$user['password']];
								exit();
							}
							else{
								?>
								<main class="err_connexion"><p><?php
								echo "Identifiant ou mot de passe incorrect.<br>";?><div id="back2index"><a href="connexion.php">Réessayez</a><br><a href="">Mot de passe oublié ?</a></div>
								</p></main>
								<?php
								exit();
							}
						}
					}

				}

				else{
			?>
			<main id="form_connexion">
				<form method="post" action="connexion.php">
					<label for="login">Login :</label>
					<input type="text" id="login" name="login" placeholder="Ex: John-Doe68" required>
					<label for="password">Mot de passe :</label>
					<input type="password" id="password" name="password" required>
					<input type="submit" id="submit_button" value="Envoyer">
				</form>
				<div id="back2index"><p>Retour à l' <a href="index.php">Accueil</a></p></div>
			</main>
			<?php
		}

	}

		else{
			?><main class="err_connexion"><?php
			echo "Vous êtes déjà connecté.<br>";?><div id="back2index"><p>Retour à l' <a href="index.php">Accueil</a>.</p></div></main><?php
		}
		

	$connect->close();
	?>
		
	</body>
</html>