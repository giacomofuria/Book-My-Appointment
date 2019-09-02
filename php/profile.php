<?php
	session_start();
	include "./util/sessionUtil.php";
	include "./layout/AppointmentTable.php";
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database
	if(!isLogged()){
		header('Location: ./../index.php');
		exit;
	}
	function getUserInfo($userId){
		global $bookMyAppointmentDb;
		$queryText = "SELECT * FROM USER WHERE userId='".$userId."';";

		$result = $bookMyAppointmentDb->performQuery($queryText);
		if(!$result){
			return false;
		}
		$numRow = mysqli_num_rows($result);
		$bookMyAppointmentDb->closeConnection();
		$userRow = $result->fetch_assoc();
		$bookMyAppointmentDb->closeConnection();
		return $userRow;
	}
	function loadConfig($userId){
		global $bookMyAppointmentDb;
		$queryText = "SELECT * FROM struttura_tabella_appuntamenti WHERE userId='".$userId."';";

		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		if($numRow == 0){
			return false;
		}
		$bookMyAppointmentDb->closeConnection();
		$userRow = $result->fetch_assoc();
		$bookMyAppointmentDb->closeConnection();
		return $userRow;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Profile - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<link rel="stylesheet" href="./../css/page.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/menu.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/profile.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/AppointmentTable.css" type="text/css" media="screen">
</head>
<body>
	<div id="left-side">
		<?php
			include "./layout/menu.php";
		?>
	</div>
	<div id="right-side">
		<?php
			include "./layout/top_bar.php";
		?>
		<div id="workspace">
			<div id="user_info">
				<?php
					$userInfo = getUserInfo($_SESSION['userId']);
				?>
				<p>Immagine del profilo</p>
				<?php
					if($userInfo['profile_image'] == null){
						// Metto l'immagine di default
						echo "<img class='profile_image' src='./../img/icon/set1/man.png' alt='Profile image'>";
					}else{
						echo "Img dal DB";
					}
				?>
				<p><?php echo $userInfo['first_name']." ".$userInfo['last_name'] ?></p>
				<p><?php echo "Professione: ".$userInfo['profession']?></p>
			</div>
			<div id="booking_table"> 
				<?php
					$tableConfiguration = loadConfig($_SESSION['userId']);
					if(!$tableConfiguration){
						echo "<p>Configura la tabella degli appuntamenti</p>";
					}else{
						echo "<p>Tabella degli appuntamenti</p>";
						$giorni = explode(',',$tableConfiguration['giorni']);
						$inizio = $tableConfiguration['oraInizio'];
						$fine = $tableConfiguration['oraFine'];
						$durata = $tableConfiguration['durataIntervalli'];
						$pause = explode(',',$tableConfiguration['intervalliPausa']);
						$appointmentTable = new AppointmentTable($giorni, $inizio, $fine, $durata, $pause);
						$appointmentTable->show();
					}
				?>
			</div>
		</div> <!-- fine workspace -->

	</div>
	<script type="text/javascript">
		// evidenzio il pulsante della pagina
		var btn = document.getElementById("profile_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#383838";
	</script>
</body>
</html>