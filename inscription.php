<?php
	session_start();
	$connect=mysqli_connect('localhost', 'root', '', 'reservationsalles');
	$connect->query('SET NAMES utf8');
	$db=$connect->query('SELECT * FROM utilisateurs');
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<title>Inscription</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Geo&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" type='text/css' href="salles.php?v=<?php echo time(); ?>">
		<script src="https://kit.fontawesome.com/9ddb75d515.js" crossorigin="anonymous"></script>
		<link href="https://fonts.googleapis.com/css2?family=Syne&display=swap" rel="stylesheet"> 
	</head>

	<body id="body_inscription">
		<?php
			if(isset($_SESSION['user']) && $_SESSION['user']['login'] && $_SESSION['user']['password']){
				?><main id="deja_inscrit"><?php
				echo "Vous êtes déjà inscrit.";?><div id="back2index"><p>Retour à <a href="index.php">l'Accueil</a>.</p></div><?php
				?></main><?php
				exit();
			}

			else if($_POST){
				?>
					<?php
					$err=0;
					$login=$_POST['login'];$password=$_POST['password'];$cpassword=$_POST['cpassword'];

					if($password!=$cpassword){
						?><main class="err_connexion"><?php
						echo "Les mots de passe ne correspondent pas.";?><div id="back2index"><a href="inscription.php">Réessayer</a></div><?php
						$err++;?>
						</main><?php
						exit();
					}

					for($i=0; $i<mysqli_num_rows($db); $i++){
						$row=mysqli_fetch_assoc($db);

						if($login==$row['login']){?>
							<main class="err_connexion"><?php
							$err++;
							echo("Ce nom d'utilisateur existe déjà.");?>
							<div id="back2index"><a href="inscription.php">Réessayer</a></div><?php
							?></main><?php
							exit();
						}
					}

					if($err==0){
						$stmt=$connect->prepare("INSERT INTO `utilisateurs` (login, password) 
										VALUES (?,? ) ");
						$password=password_hash($password, PASSWORD_DEFAULT);
						$stmt->bind_param("ss", $login, $password);
						$stmt->execute();
						?><main class="succ_connexion"><?php
						echo "Votre inscription a bien été enregistrée.";?><div id="back2index"><p>Retour à <a href="index.php">l'Accueil</a>.</p></div><?php 
						?></main><?php

						$_SESSION['user']=['login'=>$_POST['login'], 'password'=>$_POST['password']];
					}
					?>
			<?php
			}

			else{
		?>
		<main id="form_inscription">
			<form method="post" action="inscription.php">
				<label for="login">Entrez votre identifiant : </label>
				<input type="text" id="login" name="login" placeholder="Ex: John-Doe68" pattern="[A-Za-z0-9_-]{2,255}" required>
				<label for="password">Entrez votre mot de passe : </label>
				<input type="password" id="password" name="password" required>
				<label for="cpassword">Confirmez le mot de passe : </label>
				<input type="password" id="cpassword" name="cpassword" required>
				<input type="submit" id="submit_button" value="Envoyer">
			</form>
			<div id="back2index"><p>Retour à l' <a href="index.php">Accueil</a></p></div>

		</main>

		<?php
	}

	$connect->close();
	?>
	
	</body>

</html>