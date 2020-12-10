<?php
	session_start();
	$connect=mysqli_connect('localhost', 'root', '', 'reservationsalles');
	$connect->query('SET NAMES utf8');

	$local=mysqli_fetch_assoc($connect->query("SELECT DATE(NOW()) as 'DAY'"));

	function verify_date($date){
		if($_POST['debut_day']!=$_POST['fin_day']){
			echo "Vous ne pouvez pas réserver une salle sur plusieurs jours.<br>Veuillez "?><a href="reservation-form.php">Réessayer</a><?php echo ".<br>";
			return 0;
		}

		else{
			if(!isset($connect)){
				$connect=mysqli_connect('localhost', 'root', '', 'reservationsalles');
				$connect->query('SET NAMES utf8');
			}

			$date['fin']=$date['debut']='';
			$date['fin'].=$date['fin_day'] . ' ' . $date['fin_time'];
			$date['debut'].=$date['debut_day'] . ' ' . $date['debut_time'];
			$debut=$date['debut'];
			$fin=$date['fin'];

			$day=mysqli_fetch_assoc($connect->query("SELECT DAYNAME('$debut') as 'NAME' "));

			if(strtolower($day['NAME'])=='sunday' || strtolower($day['NAME'])=='saturday'){
				echo "La réservation n'est possible que du Lundi au Vendredi.<br>Veuillez "?>
					<a href="reservation-form.php">Réessayer</a><?php echo ".<br>";
				return 0;
			}

			else{
				$debut_fmt=mysqli_fetch_assoc($connect->query("SELECT TIME_TO_SEC('$debut') as 'SECS'"));
				$fin_fmt=mysqli_fetch_assoc($connect->query("SELECT TIME_TO_SEC('$fin') as 'SECS'"));
				$today_fmt=mysqli_fetch_assoc($connect->query("SELECT TIME_TO_SEC(NOW()) as 'SECS'"));
				$today=mysqli_fetch_assoc($connect->query("SELECT DATE(NOW()) as 'DAY'"));

				if($date['debut_day']==$today['DAY']){
					if($debut_fmt['SECS']<$today_fmt['SECS']){
						echo "L'heure de votre réservation est dépassée.<br>Veuillez "?>
							<a href="reservation-form.php">Réessayer</a><?php echo ".<br>";
						return 0;
					}
					else{
					}
				}

				if($debut_fmt['SECS']>$fin_fmt['SECS']){
					echo "L'heure de fin doit être supérieure à l'heure du début de votre réservation.<br>Veuillez "?>
						<a href="reservation-form.php">Réessayer</a><?php echo ".<br>";
					return 0;
				}
				else if(($fin_fmt['SECS']-$debut_fmt['SECS']<1800) || ($fin_fmt['SECS']-$debut_fmt['SECS']>14400) ){
					echo "Veuillez respecter les conditions de durée de créneau.<br>"?><a href="reservation-form.php">Réessayez</a><?php
					return 0;
				}
				else{
					unset($date['debut_day'], $date['debut_time'], $date['fin_day'], $date['fin_time']);
					return $date;
				}
			}
		}
	}

	if(isset($_POST) && $_POST){
		$date=verify_date($_POST);
		$debut=$date['debut'];
		$fin=$date['fin'];

		if($date){
			$login=$_SESSION['user']['login'];
			$id=mysqli_fetch_assoc($connect->query("SELECT `id` FROM `utilisateurs` WHERE `login`='$login'"));
			$db_users=$connect->query("
				SELECT id_utilisateur, 
				DATE_FORMAT(`debut`, '%H:%i') AS 'debut_tm', 
				DATE_FORMAT(`fin`, '%H:%i') AS 'fin_tm'
				FROM `reservations` 
				WHERE DATE(debut)=DATE('$debut')");

			for($i=0;$i<mysqli_num_rows($db_users);$i++){
				$db_val=mysqli_fetch_assoc($db_users);
				$debut=strtotime($debut);
				$fin=strtotime($fin);

				if($id['id']==$db_val['id_utilisateur']){
					echo "Vous avez déjà effectué une réservation ce jour. Retour à l'";?><a href="index.php">Accueil</a><?php echo ".";
					exit();
				}

				else if(in_array(strtotime($db_val['debut_tm'] ), range($debut, $fin) ) ||
						in_array(strtotime($db_val['fin_tm'] ), range($debut, $fin) ) )
				{
					echo "Une réservation existe déjà sur ce créneau. Veuillez "?><a href="reservation-form.php">Réessayer</a><?php echo ".";
					exit();
				}

			}

			$stmt=$connect->prepare("INSERT INTO `reservations` (titre, description, debut, fin, id_utilisateur) VALUES (?,?,?,?,?)");
			$stmt->bind_param("ssssi", $date['titre'], $date['description'], $date['debut'], $date['fin'], $id['id']);
			$stmt->execute();
			echo "Votre réservation a bien été enregistrée. Retour à l'"?><a href="index.php">Accueil</a><?php echo ".";
			exit();
		}
		
		else{
			echo "Il y a eut une erreur. Veuillez "?><a href="reservation-form.php">Réessayer</a><?php echo ".";
			exit();
		}

	}

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

	<head>
		<title>Réserver une salle</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
		<link rel="stylesheet" type='text/css' href="salles.php?v=<?php echo time(); ?>">
		<script src="https://kit.fontawesome.com/9ddb75d515.js" crossorigin="anonymous"></script>
		<link href="https://fonts.googleapis.com/css2?family=Syne&display=swap" rel="stylesheet"> 
	</head>

	<body>

		<?php 
			if(isset($_SESSION['user']['login']) && $_SESSION['user']['login']){

				if(isset($_POST) && $_POST){?>
					<a href="index.php">Accueil</a>
					<?php
				}

				else{?>

					<form method="post" action="reservation-form.php">
						<h3>Du lundi au vendredi<br>De 08h00 à 19h00</h3><br>
						<h4>Créneau minimum : 30 minutes<br>Créneau maximum : 4 heures</h4><br>
						<label for="titre">Titre :</label>
						<input type="text" name="titre" id="titre" placeholder="Ex: Cours d'anglais" 
						pattern="[\w éèàçïÀÉÈÇ\?:\.;'\(\)/_\-]{2,255}" required><br>
						<label for="description">Description :</label>
						<input type="text" name="description" id="description" placeholder="Ex: Groupes 1 à 6" 
						pattern="[\w éèàçïÀÉÈÇ\?:\.;'\(\)/_\-]{2,255}"><br>
						<label for="debut_day">Début :</label>
						<input type="date" name="debut_day" id="debut_day" min="<?php echo $local['DAY']?>" required> 
						<input type="time" name="debut_time" id="debut_time" min="08:00" max="19:00" required><br>
						<label for="fin_day">Fin :</label>
						<input type="date" name="fin_day" id="fin_day" min="<?php echo $local['DAY']?>" required> 
						<input type="time" name="fin_time" id="fin_time" min="08:00" max="19:00" required><br>
						<input type="submit" class="submit_button" value="Valider">
					</form>

					<a href="index.php">Accueil</a>
					<?php
				}
			}

			else{
				echo "Vous devez être connecté pour accéder à cette page.<br>"?>
				<a href="connexion.php">Connexion</a><br><a href="index.php">Accueil</a><?php
			}
			
		$connect->close();
		?>

	</body>

</html>