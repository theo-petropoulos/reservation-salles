<?php
	session_start();
	$connect=mysqli_connect('localhost', 'root', '', 'reservationsalles');
	$connect->query("SET NAMES utf8, @@lc_time_names = 'fr_FR'");
	$var_week=0;

	function time_to_sec($tab){
		$tab_r=$tab[0]*10*3600 +
		$tab[1]*3600 +
		$tab[3]*10*60 +
		$tab[4]*60;
		return $tab_r;
	}
	
	if(isset($_POST) && $_POST){
		if($_POST['inc']>0){
			for($inc=0;$inc<$_POST['inc'];$inc++){
				$_SESSION['var_week']=$_SESSION['var_week'] + 7;
				$var_week=intval($_SESSION['var_week']);
			}
		}
		else if($_POST['inc']<0){
			for($_POST['inc'];$_POST['inc']<0;$_POST['inc']++){
				$_SESSION['var_week']=$_SESSION['var_week'] - 7;
				$var_week=intval($_SESSION['var_week']);
			}
		}
		else{
			echo "Il y a eut une erreur. Revenir à l'";?><a href="index.php">Accueil</a><?php
		}
	}
	else{
		$_SESSION['var_week']=0;
		$var_week=0;
	}
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

	<head>
		<title>Accueil</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
		<link rel="stylesheet" type='text/css' href="salles.php?v=<?php echo time(); ?>">
		<script src="https://kit.fontawesome.com/9ddb75d515.js" crossorigin="anonymous"></script>
		<link href="https://fonts.googleapis.com/css2?family=Syne&display=swap" rel="stylesheet"> 
	</head>

	<body>
		<main id="block_right">

		<table>

			<thead>
				<tr id="tr_head">
				<th id="th_hr">Heure</th><?php
				for($j=0;$j<7;$j++){
					$connect->query("SELECT DATE_FORMAT(`debut`, '%d/%m/%Y %H:%i')");
					$date=mysqli_fetch_assoc($connect->query("
						SELECT DATE_FORMAT(DATE_ADD(DATE(NOW()), INTERVAL +$j +$var_week DAY), '%d/%m/%Y') AS 'today', 
						DATE_FORMAT(DATE_ADD(DATE(NOW()), INTERVAL +$j +$var_week DAY), '%d/%m/%Y %H:%i') AS 'today_h', 
						DAYNAME(DATE_ADD(DATE(NOW()), INTERVAL +$j +$var_week DAY)) AS 'today_name'
						") );
					$today=$date['today'];
					$today_h=$date['today_h'];
					$today_name=$date['today_name'];
					$today_name[0]=strtoupper($today_name[0]);
					$reservations_list=$connect->query("
						SELECT titre, description, id_utilisateur, 
						DATE_FORMAT(`debut`, '%H:%i') AS `debut`, 
						DATE_FORMAT(`fin`, '%H:%i') AS `fin` FROM `reservations` 
						WHERE DATE_FORMAT(DATE(`debut`), '%d/%m/%Y %H:%i')='$today_h' 
						ORDER BY `debut` ");
					for($k=0;$k<mysqli_num_rows($reservations_list);$k++){
						$tab[$j][$k]=mysqli_fetch_assoc($reservations_list);
					}
					?>
					<th><?php echo $today_name . "<br>" . $today?></th><?php
				}
				?>
				</tr>
			</thead>

			<tbody>
				<tr id="tr_body">
				<td id="col_time"><?php
					for($i=0;$i<21;$i++){
						$j=32400+$i*1800;
						?><span class="col_time_hi"><?php echo gmdate("H:i", $j);?></span><?php echo "<br>";
					}
				?>
				</td>
				<?php
				for($j=0;$j<7;$j++){
					$k=0;
					?><td><?php
					if(isset($tab[$j][$k]) && $tab[$j][$k]){
						while(isset($tab[$j][$k]) && $tab[$j][$k]){
							$perc=((strtotime($tab[$j][$k]['fin'])-strtotime($tab[$j][$k]['debut']))/36000*100);
							$tab_deb=time_to_sec($tab[$j][$k]['debut']);
							$tab_fin=time_to_sec($tab[$j][$k]['fin']);
							if(isset($tab[$j][$k-1]['debut']) && $tab[$j][$k-1]['debut']){
								$tab_finm1=time_to_sec($tab[$j][$k-1]['fin']);
							}
							if(isset($tab[$j][$k+1]['debut']) && $tab[$j][$k+1]['debut']){
								$tab_debp1=time_to_sec($tab[$j][$k+1]['debut']);
							}
							?><div class="slot_blank<?php echo $j . "-" . $k;?>"></div>
							<style>
								.slot_blank<?php echo $j . "-" . $k;?>{
									<?php
									$height=0;
										if($k==0){
											$height= ($tab_deb-32400)/36000*100 + 1;
										}
										else if(isset($tab_debp1) && $tab_debp1 && isset($tab_finm1) && $tab_finm1){
											$height=($tab_deb - $tab_finm1 )/36000*100;
										}
										else if(!isset($tab_debp1)){
											$height=(68400-$tab_fin)/36000*100;
										}
										?>
									height:<?php echo $height;?>%;
									background:transparent;
								}
							</style>
							
							<div class="slot_res<?php echo $j . "-" . $k;?>"><?php
								$user_id=$tab[$j][$k]['id_utilisateur'];
								$user=mysqli_fetch_assoc($connect->query("SELECT login FROM `utilisateurs` WHERE id='$user_id'"));
								?><h2><?php echo 
								$tab[$j][$k]['titre'] ?></h2><h3><?php echo  
								$tab[$j][$k]['description'] ?></h3><h4><?php echo  
								"Réservé par :<br>"; ?></h4><h2><?php echo $user['login'];?></h2>
								<form method="get" action="reservation.php" id="form_slot">
									<input type="checkbox" checked hidden name="slot" id="slot" value="<?php echo $today_h?>">
									<button type="submit" id="submit_slot">Voir la réservation</button>
								</form>
							</div>
							<style>
								.slot_res<?php echo $j . "-" . $k;?>{
									height:<?php echo $perc?>%;
									width:100%;
									background:#4787c6;
									border:2px solid black;
									display:flex;
									flex-direction:column;
									align-self:center;
									align-items:center;
									text-align:center;
									overflow:hidden;
								}

								.slot_res<?php echo $j . "-" . $k;?>:hover #form_slot{
									visibility:visible;
									opacity:1;
									transition:all 0.8s ease-in;
								}

								.slot_res<?php echo $j . "-" . $k;?>:hover{
									height:50%;
									transition:all 0.8s ease-in;
								}
							</style>
							<?php
							$k++;
						}
						?><div class="slot_blank_end_<?php echo $j . "_" . $k;?>"></div>

						<style>
							<?php
								$height=(68400-$tab_fin)/36000*100;
							?>
								.slot_blank_end_<?php echo $j . "_" . $k;?>{
									height:<?php echo $height?>%;
									background:transparent;
									position:relative;
								}
						</style>
						<?php
					}
					else{
						?><div class="slot_blank"></div><?php
					}
					?></td><?php
					}
					?>
					</tr>
			</tbody>

		</table>

			<div id="nav_buttons">
				<form action="planning.php" method="post">
					<input type="checkbox" checked hidden name="inc" id="inc" value="-1">
					<button type="submit" class="btn btn-success">
						<i class="fas fa-chevron-left" id="ch_left_1"></i><i class="fas fa-chevron-left" id="ch_left_2"></i>
					</button>
				</form>

				<h1>MyAgenda</h1>

				<form action="planning.php" method="post">
					<input type="checkbox" checked hidden name="inc" id="inc" value="1">
					<button type="submit" class="btn btn-success">
						<i class="fas fa-chevron-right" id="ch_right_1"></i><i class="fas fa-chevron-right" id="ch_right_2"></i>
					</button>
				</form>
			</div>

		</main>

		<footer>
			<a href="index.php">Accueil</a>
		</footer>

		<?php
		$connect->close();
		?>
	</body>

</html>